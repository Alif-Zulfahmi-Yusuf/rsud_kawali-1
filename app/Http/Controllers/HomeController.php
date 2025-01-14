<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluasiPegawai;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\map;

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
    public function index()
    {
        $userId = Auth::id();

        // Ambil data evaluasi berdasarkan user yang sedang login
        $evaluasi = EvaluasiPegawai::where('user_id', $userId)
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
        ]);
    }
}