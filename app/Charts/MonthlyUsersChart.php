<?php

namespace App\Charts;

use App\Models\EvaluasiPegawai;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlyUsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        // Ambil ID pegawai dari sesi
        $pegawaiId = session('pegawai');
        $pegawai = User::find($pegawaiId);

        // Jika tidak ada pegawai terpilih, tampilkan data default
        if (!$pegawai) {
            return $this->chart->barChart()
                ->setTitle('Evaluasi Pegawai')
                ->setSubtitle('Performa dalam 12 Bulan Terakhir')
                ->addData('Hasil Kerja', [])
                ->addData('Perilaku Kerja', [])
                ->setXAxis(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        }

        // Ambil data evaluasi pegawai
        $evaluasi = EvaluasiPegawai::where('user_id', $pegawaiId)
            ->whereRaw('bulan >= DATE_SUB(NOW(), INTERVAL 12 MONTH)')
            ->selectRaw('MONTH(bulan) as month, YEAR(bulan) as year, nilai, rating')
            ->get();

        // Inisialisasi data
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $categories = ['Di Bawah Ekspektasi', 'Sesuai Ekspektasi', 'Di Atas Ekspektasi'];
        $hasilKerjaData = [];
        $perilakuKerjaData = collect();

        if ($evaluasi->isNotEmpty()) {
            foreach ($evaluasi as $data) {
                // Proses data hasil kerja
                if (substr($data->rating, 0, 2) == 'di') {
                    $hasilKerjaData[] = 'Di ' . ucwords(str_replace('_', ' ', substr($data->rating, 2)));
                } else {
                    $hasilKerjaData[] = ucwords(str_replace('_', ' ', $data->rating));
                }

                // Proses data perilaku kerja
                $perilakuKerjaData[] = collect([$data->nilai])->map(function ($nilai) {
                    $nilaiMap = [
                        'dibawah_ekspektasi' => 1,
                        'sesuai_ekspektasi' => 2,
                        'diatas_ekspektasi' => 3,
                    ];

                    $nilaiArray = is_array(json_decode($nilai, true)) ? json_decode($nilai, true) : [$nilai];

                    $mappedValues = collect($nilaiArray)->map(fn($item) => $nilaiMap[$item] ?? 0)->filter(fn($val) => $val > 0);

                    $average = $mappedValues->isNotEmpty() ? $mappedValues->avg() : 0;

                    if ($average < 1.5) {
                        return "Di Bawah Ekspektasi";
                    } elseif ($average <= 2.5) {
                        return "Sesuai Ekspektasi";
                    } else {
                        return "Di Atas Ekspektasi";
                    }
                })->first();
            }
        } else {
            // Jika tidak ada data evaluasi
            $hasilKerjaData[] = 'Tidak Ada Data';
            $perilakuKerjaData[] = 'Tidak Ada Data';
        }

        return $this->chart->barChart()
            ->setTitle('Evaluasi Pegawai: ' . $pegawai->name)
            ->setSubtitle('Performa dalam 12 Bulan Terakhir')
            ->addData('Hasil Kerja', $hasilKerjaData)
            ->addData('Perilaku Kerja', json_decode($perilakuKerjaData))
            ->setXAxis($months)
            ->setGrid(false)
            ->setDataLabels(false)
            ->setColors(['#007bff', '#28a745']);
    }
}