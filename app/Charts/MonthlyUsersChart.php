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
        // dd($hasilKerjaData);
        // dd($perilakuKerjaData);

        $performanceResults = [
            'January' => 'Sesuai Ekspektasi',
            'February' => 'Di Atas Ekspektasi',
            'March' => 'Sesuai Ekspektasi',
            'April' => 'Di Bawah Ekspektasi',
            'May' => 'Sesuai Ekspektasi',
            'June' => 'Di Atas Ekspektasi',
            'July' => 'Sesuai Ekspektasi',
            'August' => 'Di Bawah Ekspektasi',
            'September' => 'Sesuai Ekspektasi',
            'October' => 'Di Atas Ekspektasi',
            'November' => 'Sesuai Ekspektasi',
            'December' => 'Di Bawah Ekspektasi',
        ];

        // Prepare data for the chart
        $months = array_keys($performanceResults);
        $hasilKerja = [];
        $perilakuKerja = []; // Assuming you have a similar array for behavior

        // Convert qualitative data to numerical values for the chart
        foreach ($hasilKerjaData as $result) {
            switch ($result) {
                case 'Sesuai Ekspektasi':
                    $hasilKerja[] = 2; // Assign a numerical value
                    break;
                case 'Di Atas Ekspektasi':
                    $hasilKerja[] = 3; // Assign a numerical value
                    break;
                case 'Di Bawah Ekspektasi':
                    $hasilKerja[] = 1; // Assign a numerical value
                    break;
            }
        }

        foreach ($perilakuKerjaData as $result) {
            switch ($result) {
                case 'Sesuai Ekspektasi':
                    $perilakuKerja[] = 2; // Assign a numerical value
                    break;
                case 'Di Atas Ekspektasi':
                    $perilakuKerja[] = 3; // Assign a numerical value
                    break;
                case 'Di Bawah Ekspektasi':
                    $perilakuKerja[] = 1; // Assign a numerical value
                    break;
            }
        }

        return $this->chart->barChart()
            ->setTitle('Evaluasi Pegawai: ' . $pegawai->name)
            ->setSubtitle('Performa dalam 12 Bulan Terakhir')
            ->addData('Hasil Kerja', $hasilKerja) // 12 Data
            ->addData('Perilaku Kerja', $perilakuKerja) // 12 Data
            ->setXAxis($months)
            ->setGrid(false)
            ->setColors(['#007bff', '#28a745']);
    }
}