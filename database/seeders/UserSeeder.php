<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'username' => 'lamtiur',
            'name' => 'Lamtiur',
            'email' => 'lamtiur@apda.go.id',
            'password' => bcrypt('l@mt1urkorlantas2024!@'),
        ]);

        $admin->assignRole('admin');

        $user = User::create([
            'username' => 'gregorius',
            'name' => 'Bripda Gregorius Sinaga',
            'email' => 'gregorius@apda.go.id',
            'password' => bcrypt('gr3g0r1uskorlantas2024!@'),
        ]);

        $user->assignRole('user');
    }
}
