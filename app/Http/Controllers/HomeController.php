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
            $evaluasi = EvaluasiPegawai::where('user_id', $user->id)
                ->whereRaw('bulan >= DATE_SUB(NOW(), INTERVAL 12 MONTH)')
                ->selectRaw('MONTH(bulan) as month, YEAR(bulan) as year, nilai, rating')
                ->first();

            // Inisialisasi data default jika evaluasi tidak ditemukan
            $hasilKerjaData = [];
            $perilakuKerjaData = collect();

            if ($evaluasi) {
                // Proses data hasil kerja
                if (substr($evaluasi->rating, 0, 2) == 'di') {
                    $hasilKerjaData[] = 'Di ' . ucwords(str_replace('_', ' ', substr($evaluasi->rating, 2)));
                } else {
                    $hasilKerjaData[] = ucwords(str_replace('_', ' ', $evaluasi->rating));
                }

                // Proses data perilaku kerja
                $perilakuKerjaData = collect([$evaluasi->nilai])->map(function ($nilai) {
                    $nilaiMap = [
                        'dibawah_ekspektasi' => 1,
                        'sesuai_ekspektasi' => 2,
                        'diatas_ekspektasi' => 3,
                    ];

                    // Ubah nilai JSON atau string menjadi array nilai
                    $nilaiArray = is_array(json_decode($nilai, true)) ? json_decode($nilai, true) : [$nilai];

                    // Map nilai ke angka menggunakan $nilaiMap
                    $mappedValues = collect($nilaiArray)->map(function ($item) use ($nilaiMap) {
                        return $nilaiMap[$item] ?? 0; // Default 0 jika nilai tidak valid
                    })->filter(fn($val) => $val > 0); // Hanya gunakan nilai valid (> 0)

                    // Hitung rata-rata
                    $average = $mappedValues->isNotEmpty() ? $mappedValues->avg() : 0;

                    // Map rata-rata ke teks
                    if ($average < 1.5) {
                        return "Di Bawah Ekspektasi";
                    } elseif ($average <= 2.5) {
                        return "Sesuai Ekspektasi";
                    } else {
                        return "Di Atas Ekspektasi";
                    }
                });
            } else {
                // Jika tidak ada data evaluasi, tambahkan placeholder data kosong
                $hasilKerjaData[] = 'Tidak Ada Data';
                $perilakuKerjaData[] = 'Tidak Ada Data';
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

        abort(403, 'Akses ditolak');
    }

    public function getEvaluasiPegawai($id, MonthlyUsersChart $chart)
    {
        try {
            $chartData = $chart->build($id);
            return view('backend.dash.dashboard_atasan', compact('chartData'));
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data evaluasi pegawai.', ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal mengambil data evaluasi pegawai.');
        }
    }

    public function uwu(Request $request)
    {
        $request->validate([
            'pegawai' => 'required',
        ]);

        session()->put('pegawai', $request->pegawai);

        return redirect()->route('dashboard.index');
    }
}
