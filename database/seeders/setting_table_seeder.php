<?php

namespace Database\Seeders;

use App\Models\setting;
use Illuminate\Database\Seeder;


class setting_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Pengaturan Aplikasi',
                'description' => 'Pengaturan Aplikasi',
                'address' => '-',
            ],
        ];

        foreach ($data as $item) {
            setting::create($item);
        }
    }

    public function data()
    {
        return [
            [
                'name' => 'Pengaturan Aplikasi',
                'description' => 'Pengaturan Aplikasi',
                'address' => '-',
            ],
        ];
    }
}