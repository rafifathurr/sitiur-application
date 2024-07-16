<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Master\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $array_insert = [
            [
                'name' => 'Mabes Polri',
                'parent_id' => null,
                'level' => 0,
            ],
            [
                'name' => 'Korlantas Polri',
                'parent_id' => 1,
                'level' => 1,
            ],
            [
                'name' => 'Polda Aceh',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Bali',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Banten',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Bengkulu',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda DI Yogyakarta',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Gorontalo',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Jambi',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Jawa Barat',
                'parent_id' => 1,
                'level' => 2,
            ],

            [
                'name' => 'Polda Jawa Tengah',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Jawa Timur',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Kalimantan Barat',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Kalimantan Selatan',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Kalimantan Tengah',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Kalimantan Timur',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Kalimantan Utara',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Kepulauan Bangka Belitung',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Kepulauan Riau',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Lampung',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Maluku',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Maluku Utara',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Metro Jaya',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda NTB',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda NTT',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Papua',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Papua Barat',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Riau',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Sulawesi Barat',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Sulawesi Selatan',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Sulawesi Tengah',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Sulawesi Tenggara',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Sulawesi Utara',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Sumatera Barat',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Sumatera Selatan',
                'parent_id' => 1,
                'level' => 2,
            ],
            [
                'name' => 'Polda Sumatera Utara',
                'parent_id' => 1,
                'level' => 2,
            ],
        ];

        Institution::insert($array_insert);
    }
}
