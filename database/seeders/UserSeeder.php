<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Admin - PERBAIKI EMAIL INI
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@kampus.id', // UBAH MENJADI admin@kampus.id
            'password' => Hash::make('secret'),
            'role' => 'admin',
        ]);

        $mahasiswa = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@kampus.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@kampus.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@kampus.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@kampus.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ],
            [
                'name' => 'Rizki Pratama',
                'email' => 'rizki.pratama@kampus.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ]
        ];

        foreach ($mahasiswa as $mhs) {
            User::create($mhs);
        }

        $this->command->info('Seeder berhasil dibuat');
        $this->command->info(' 1 admin (admin@kampus.id / secret)');
        $this->command->info(' 5 mahasiswa (password: password123)');
    }
}
