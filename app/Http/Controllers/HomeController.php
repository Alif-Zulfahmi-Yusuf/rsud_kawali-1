<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluasiPegawai;
use Illuminate\Support\Facades\Auth;

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
            ->selectRaw('MONTH(bulan) as month, YEAR(bulan) as year, nilai, rating')
            ->get();

        $hasilKerjaData = array_fill(0, 12, "Tidak Ada Data"); // Default nilai untuk setiap bulan
        $perilakuKerjaData = array_fill(0, 12, "Tidak Ada Data");

        foreach ($evaluasi->groupBy('month') as $month => $dataByMonth) {
            $nilaiBulanan = $dataByMonth->pluck('nilai')->map(fn($nilai) => json_decode($nilai, true))->flatten();
            $mappedValues = $nilaiBulanan->map(function ($value) {
                return match ($value) {
                    'dibawah_ekspetasi' => 1,
                    'sesuai_ekspetasi' => 2,
                    'diatas_ekspetasi' => 3,
                    default => 0,
                };
            })->filter(fn($val) => $val > 0);

            $average = $mappedValues->isNotEmpty() ? $mappedValues->avg() : 0;

            if ($average < 1.5) {
                $hasilKerjaData[$month - 1] = "Di Bawah Ekspektasi";
            } elseif ($average <= 2.5) {
                $hasilKerjaData[$month - 1] = "Sesuai Ekspektasi";
            } else {
                $hasilKerjaData[$month - 1] = "Di Atas Ekspektasi";
            }

            $perilakuKerjaData[$month - 1] = $dataByMonth->pluck('rating')->unique()->first() ?? "Tidak Ada Data";
        }

        return view('backend.dash.dashboard', [
            'hasilKerjaData' => json_encode($hasilKerjaData),
            'perilakuKerjaData' => json_encode($perilakuKerjaData),
        ]);
    }
}