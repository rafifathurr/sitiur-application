<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Master\Classification;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $array_insert = [
            [
                'name' => 'Biasa',
            ],
            [
                'name' => 'Kilat',
            ],
        ];

        Classification::insert($array_insert);
    }
}
