<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryPerilaku;

class Category_perilaku_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data yang akan di-seed
        $categories = [
            ['name' => 'Berorientasi Pelayanan'],
            ['name' => 'Akuntabel'],
            ['name' => 'Kompeten'],
            ['name' => 'Harmonis'],
            ['name' => 'Loyal'],
            ['name' => 'Adaptif'],
            ['name' => 'Kolaboratif'],
        ];

        // Loop untuk insert data ke tabel
        foreach ($categories as $category) {
            CategoryPerilaku::create($category);
        }
    }
}
