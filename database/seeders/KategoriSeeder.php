<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ============================================================
 * KategoriSeeder - Seeder untuk mengisi data kategori laporan
 * ============================================================
 * Mengisi tabel 'kategoris' dengan 14 kategori:
 * - 8 kategori untuk sarana prasarana
 * - 6 kategori untuk kesejahteraan siswa
 * ============================================================
 */
class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Array berisi semua kategori yang akan dimasukkan ke database
        // Setiap elemen array = 1 baris data di tabel kategoris
        $kategoris = [

            // ── KATEGORI SARANA PRASARANA ─────────────────────────
            // Laporan terkait kerusakan/masalah fasilitas fisik sekolah
            // Jenis ini akan masuk ke halaman: sapras, guru, guru_bk, kepala_sekolah

            ['ket_kategori' => 'Toilet & Kamar Mandi',  'jenis' => 'sarana_prasarana',    'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Ruang Kelas',            'jenis' => 'sarana_prasarana',    'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Laboratorium',           'jenis' => 'sarana_prasarana',    'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Perpustakaan',           'jenis' => 'sarana_prasarana',    'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Lapangan & Olahraga',    'jenis' => 'sarana_prasarana',    'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Kantin Sekolah',         'jenis' => 'sarana_prasarana',    'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Area Parkir',            'jenis' => 'sarana_prasarana',    'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Listrik & Elektronik',   'jenis' => 'sarana_prasarana',    'created_at' => now(), 'updated_at' => now()],

            // ── KATEGORI KESEJAHTERAAN SISWA ──────────────────────
            // Laporan terkait masalah yang dialami siswa secara personal
            // Jenis ini akan masuk ke halaman: kesiswaan, guru, guru_bk, kepala_sekolah

            ['ket_kategori' => 'Bullying / Perundungan', 'jenis' => 'kesejahteraan_siswa', 'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Kekerasan Fisik',        'jenis' => 'kesejahteraan_siswa', 'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Masalah Psikologis',     'jenis' => 'kesejahteraan_siswa', 'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Masalah Akademik',       'jenis' => 'kesejahteraan_siswa', 'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Permasalahan Sosial',    'jenis' => 'kesejahteraan_siswa', 'created_at' => now(), 'updated_at' => now()],
            ['ket_kategori' => 'Saran & Masukan Lainnya','jenis' => 'kesejahteraan_siswa', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Masukkan semua kategori ke database sekaligus
        DB::table('kategoris')->insert($kategoris);
    }
}
