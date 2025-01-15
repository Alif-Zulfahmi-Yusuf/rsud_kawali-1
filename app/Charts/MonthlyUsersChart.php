<?php

namespace App\Charts;

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
        $pegawai = User::where('id', session('pegawai'))->first()->name ?? 'Pegawai';

        return $this->chart->barChart()
            ->setTitle('Evaluasi Pegawai ' . $pegawai)
            ->setSubtitle('Performa dalam 12 Bulan Terakhir')
            ->addData('Hasil Kerja', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
            ->addData('Perilaku Kerja', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
            ->setXAxis(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
    }
}
