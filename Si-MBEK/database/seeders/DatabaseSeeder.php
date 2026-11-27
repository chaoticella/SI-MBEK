<?php

namespace Database\Seeders;

use App\Models\Kandang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Pengelola & Pegawai)
        User::create([
            'name' => 'Natasha Alicia (Pengelola)',
            'email' => 'pengelola@simbek.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola',
            'status_aktif' => true,
        ]);

        User::create([
            'name' => 'M. Galang Ramadhan (Pegawai Kandang)',
            'email' => 'pegawai@simbek.com',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
            'status_aktif' => true,
        ]);

        User::create([
            'name' => 'Sri Muthia Azzahra (Pegawai)',
            'email' => 'muthia@simbek.com',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
            'status_aktif' => true,
        ]);

        User::create([
            'name' => 'Kelly Daniella (Pegawai Nonaktif)',
            'email' => 'kelly.nonaktif@simbek.com',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
            'status_aktif' => false,
        ]);

        // 2. Seed Kandang data matching the screenshots
        Kandang::create([
            'blok_kandang' => 'Blok 1',
            'kapasitas_maksimal' => 25,
            'kategori' => 'jantan',
            'status_aktif' => true,
            'keterangan' => 'Kandang kambing jantan indukan ras Kacang dan Saanen.',
        ]);

        Kandang::create([
            'blok_kandang' => 'Blok 2',
            'kapasitas_maksimal' => 30,
            'kategori' => 'betina',
            'status_aktif' => true,
            'keterangan' => 'Kandang kambing betina produktif, ras Boer dan Etawa.',
        ]);

        Kandang::create([
            'blok_kandang' => 'Blok 3',
            'kapasitas_maksimal' => 10,
            'kategori' => 'isolasi',
            'status_aktif' => true,
            'keterangan' => 'Kandang isolasi & karantina kambing bergejala sakit (Pneumonia, Cacingan).',
        ]);

        Kandang::create([
            'blok_kandang' => 'Blok 4',
            'kapasitas_maksimal' => 20,
            'kategori' => 'betina',
            'status_aktif' => true,
            'keterangan' => 'Kandang anakan betina lepas sapih ras Jawarandu.',
        ]);

        Kandang::create([
            'blok_kandang' => 'Blok 5',
            'kapasitas_maksimal' => 15,
            'kategori' => 'jantan',
            'status_aktif' => true,
            'keterangan' => 'Kandang penggemukan pejantan siap kawin.',
        ]);
    }
}
