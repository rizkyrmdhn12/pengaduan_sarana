<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * ============================================================
 * SiswaSeeder - Seeder untuk mengisi data siswa demo
 * ============================================================
 * Membuat 5 akun siswa demo untuk keperluan testing/presentasi
 * Semua siswa demo menggunakan password yang sama: siswa123
 * ============================================================
 */
class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Array berisi data 5 siswa demo
        // NIS digunakan sebagai primary key dan juga username login
        $siswas = [
            ['nis' => 2024001, 'nama_siswa' => 'Muhamad Rizky',    'kelas' => 'XI RPL 4', 'password' => Hash::make('siswa123'), 'created_at' => now(), 'updated_at' => now()],
            ['nis' => 2024002, 'nama_siswa' => 'Angga Nugraha',     'kelas' => 'XI RPL 4', 'password' => Hash::make('siswa123'), 'created_at' => now(), 'updated_at' => now()],
            ['nis' => 2024003, 'nama_siswa' => 'Fachri Rachman',     'kelas' => 'XI RPL 4',  'password' => Hash::make('siswa123'), 'created_at' => now(), 'updated_at' => now()],
            ['nis' => 2024004, 'nama_siswa' => 'Nazwa Umay',    'kelas' => 'XI RPL 4',  'password' => Hash::make('siswa123'), 'created_at' => now(), 'updated_at' => now()],
            ['nis' => 2024005, 'nama_siswa' => 'Alisa Khairan',   'kelas' => 'XI RPL 1',   'password' => Hash::make('siswa123'), 'created_at' => now(), 'updated_at' => now()],
        ];

        // Masukkan semua data siswa ke tabel 'siswas' sekaligus
        DB::table('siswas')->insert($siswas);
    }
}
