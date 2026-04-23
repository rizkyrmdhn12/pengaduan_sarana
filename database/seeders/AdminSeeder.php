<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * ============================================================
 * AdminSeeder - Seeder untuk mengisi data awal tabel admins
 * ============================================================
 * Seeder adalah class khusus untuk memasukkan data awal
 * ke database secara otomatis saat menjalankan:
 * php artisan db:seed
 *
 * Seeder ini membuat 5 akun admin dengan role berbeda-beda
 * ============================================================
 */
class AdminSeeder extends Seeder
{
    /**
     * ------------------------------------------------------------
     * FUNGSI: run()
     * ------------------------------------------------------------
     * Fungsi ini dijalankan otomatis oleh Laravel saat seeding
     * ------------------------------------------------------------
     */
    public function run(): void
    {
        // Simpan semua data admin dalam array terlebih dahulu
        // Array ini berisi 5 admin dengan role yang berbeda-beda
        $admins = [
            [
                'username'   => 'kepsek',
                'nama'       => 'Bpk. Nasrullah Nurul Rohmat, S.Pd, M.Pd',
                'password'   => Hash::make('kepsek123'),
                'role'       => 'kepala_sekolah',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'username'   => 'guru1',
                'nama'       => 'Bpk. Sukanda Wiguna, S.Kom',
                'password'   => Hash::make('guru123'),
                'role'       => 'guru',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'username'   => 'sapras',
                'nama'       => 'Bpk. Dudi Haryadi, S.Pd',
                'password'   => Hash::make('sapras123'),
                'role'       => 'sapras',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'username'   => 'kesiswaan',
                'nama'       => 'Ibu Atiek Wardiati, S.Pd',
                'password'   => Hash::make('kesiswaan123'),
                'role'       => 'kesiswaan',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'username'   => 'guru bk',
                'nama'       => 'Ibu Anisa, S.Psi',
                'password'   => Hash::make('gurubk123'),
                'role'       => 'guru_bk',
                'created_at' => now(), 'updated_at' => now()
            ],
        ];

        // Masukkan semua data dari array ke tabel 'admins' sekaligus
        // DB::table()->insert() dengan array = insert banyak baris sekaligus
        DB::table('admins')->insert($admins);
    }
}
