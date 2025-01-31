<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EvaluasiPegawai;
use function PHPSTORM_META\map;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Charts\MonthlyUsersChart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(MonthlyUsersChart $chart)
    {
        $chart = $chart->build();
        $user = Auth::user();

        // Bagian Pegawai
        if ($user->hasRole('Pegawai')) {
            // Ambil data evaluasi berdasarkan user yang sedang login
            $evaluasiList = EvaluasiPegawai::where('user_id', $user->id)
                ->whereRaw('bulan >= DATE_SUB(NOW(), INTERVAL 12 MONTH)')
                ->selectRaw('MONTH(bulan) as month, YEAR(bulan) as year, nilai, rating')
                ->get();

            // Inisialisasi array kosong untuk menyimpan hasil kerja dan perilaku kerja
            $hasilKerjaData = array_fill(0, 12, "Tidak Ada Data");
            $perilakuKerjaData = array_fill(0, 12, "Tidak Ada Data");

            // Mapping nilai perilaku kerja
            $nilaiMap = [
                'dibawah_ekspektasi' => 1,
                'sesuai_ekspektasi' => 2,
                'diatas_ekspektasi' => 3,
            ];

            foreach ($evaluasiList as $evaluasi) {
                // Konversi bulan ke indeks array (0-based index)
                $monthIndex = (int) $evaluasi->month - 1;

                // Proses data hasil kerja
                if (substr($evaluasi->rating, 0, 2) == 'di') {
                    $hasilKerjaData[$monthIndex] = 'Di ' . ucwords(str_replace('_', ' ', substr($evaluasi->rating, 2)));
                } else {
                    $hasilKerjaData[$monthIndex] = ucwords(str_replace('_', ' ', $evaluasi->rating));
                }

                // Proses data perilaku kerja
                $nilaiArray = is_array(json_decode($evaluasi->nilai, true)) ? json_decode($evaluasi->nilai, true) : [$evaluasi->nilai];

                // Map nilai ke angka menggunakan $nilaiMap
                $mappedValues = collect($nilaiArray)->map(fn($item) => $nilaiMap[$item] ?? 0)->filter(fn($val) => $val > 0);

                // Hitung rata-rata nilai perilaku kerja
                $average = $mappedValues->isNotEmpty() ? $mappedValues->avg() : 0;

                // Tentukan kategori perilaku kerja berdasarkan rata-rata nilai
                if ($average < 1.5) {
                    $perilakuKerjaData[$monthIndex] = "Di Bawah Ekspektasi";
                } elseif ($average <= 2.5) {
                    $perilakuKerjaData[$monthIndex] = "Sesuai Ekspektasi";
                } else {
                    $perilakuKerjaData[$monthIndex] = "Di Atas Ekspektasi";
                }
            }

            return view('backend.dash.dashboard', [
                'hasilKerjaData' => json_encode($hasilKerjaData),
                'perilakuKerjaData' => json_encode($perilakuKerjaData),
                'chart' => $chart
            ]);
        }

        // Bagian Atasan
        if ($user->hasRole('Atasan')) {
            // Log data debug untuk membantu pelacakan
            Log::info('Memulai proses pengambilan data evaluasi pegawai oleh atasan.', [
                'user_id' => $user->id,
            ]);

            $pegawaiList = EvaluasiPegawai::with(['user', 'user.pangkat'])
                ->whereIn('status', ['selesai', 'revisi'])
                ->where('is_submit', 1)
                ->whereHas('rencanaPegawai', function ($query) use ($user) {
                    $query->whereHas('rencanaAtasan', function ($queryAtasan) use ($user) {
                        $queryAtasan->where('user_id', $user->id);
                    });
                })
                ->get()
                ->unique('user_id');

            if ($pegawaiList->isEmpty()) {
                Log::warning('Tidak ada data evaluasi ditemukan untuk atasan.', [
                    'user_id' => $user->id,
                ]);
            }

            return view('backend.dash.dashboard_atasan', compact('pegawaiList', 'chart'));
        }

        if ($user->hasRole('Admin')) {
            // Hitung jumlah pengguna berdasarkan peran
            $totalAtasan = User::role('Atasan')->count(); // Menghitung pengguna dengan role Atasan
            $totalPegawai = User::role('Pegawai')->count(); // Menghitung pengguna dengan role Pegawai

            return view('backend.dash.dashboard_admin', compact('totalAtasan', 'totalPegawai'));
        }


        abort(403, 'Akses ditolak');
    }


    public function getEvaluasiPegawai(Request $request)
    {
        $request->validate([
            'pegawai' => 'required',
        ]);

        session()->put('pegawai', $request->pegawai);

        return redirect()->route('dashboard.index');
    }
}