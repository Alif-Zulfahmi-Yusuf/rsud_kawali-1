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
            ->selectRaw('MONTH(bulan) as month, YEAR(bulan) as year, nilai, rating')
            ->get();

        // Default nilai untuk setiap bulan
        $hasilKerjaData = $evaluasi->pluck('rating');

        $perilakuKerjaData = $evaluasi->pluck('nilai');

        dd($hasilKerjaData, $perilakuKerjaData);

        return view('backend.dash.dashboard', [
            'hasilKerjaData' => json_encode($hasilKerjaData),
            'perilakuKerjaData' => json_encode($perilakuKerjaData),
        ]);
    }
}