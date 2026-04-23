<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================
 * Model: Admin
 * ============================================================
 * Model ini merepresentasikan tabel 'admins' di database
 * yang menyimpan data staff/guru yang bisa login sebagai admin
 *
 * Role yang tersedia:
 * - kepala_sekolah : Akses ke semua jenis laporan
 * - guru           : Akses ke semua jenis laporan
 * - sapras         : Hanya laporan sarana prasarana
 * - kesiswaan      : Hanya laporan kesejahteraan siswa
 * - guru_bk        : Akses ke semua jenis laporan
 * ============================================================
 */
class Admin extends Model
{
    // Nama tabel di database yang digunakan model ini
    protected $table = 'admins';

    // Kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = ['username', 'nama', 'password', 'role'];

    // Kolom yang disembunyikan ketika data dikonversi ke JSON/array
    // Password tidak boleh ikut tampil saat data dikirim ke view
    protected $hidden = ['password'];

    /**
     * ------------------------------------------------------------
     * FUNGSI STATIC: getRoleLabel()
     * ------------------------------------------------------------
     * Tujuan : Mengubah kode role menjadi label yang mudah dibaca
     *
     * Cara kerja:
     * - Menerima kode role (contoh: 'kepala_sekolah')
     * - Mengembalikan label yang lebih ramah pengguna (contoh: 'Kepala Sekolah')
     * - Menggunakan array associative sebagai lookup table
     *
     * Fungsi STATIC = bisa dipanggil tanpa membuat object dulu
     * Contoh: Admin::getRoleLabel('sapras') → 'Sarana Prasarana'
     * ------------------------------------------------------------
     * @param  string $role  Kode role dari database
     * @return string        Label role yang mudah dibaca
     * ------------------------------------------------------------
     */
    public static function getRoleLabel(string $role): string
    {
        // Array asosiatif: kode role → label tampilan
        $labels = [
            'kepala_sekolah' => 'Kepala Sekolah',
            'guru'           => 'Guru',
            'sapras'         => 'Sarana Prasarana',
            'kesiswaan'      => 'Kesiswaan',
            'guru_bk'        => 'Guru BK',
        ];

        // Kembalikan label sesuai role, atau role itu sendiri jika tidak ditemukan
        return $labels[$role] ?? $role;
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI STATIC: getRolesForJenis()
     * ------------------------------------------------------------
     * Tujuan : Mendapatkan daftar role yang boleh mengakses
     *          jenis laporan tertentu
     *
     * Cara kerja:
     * - Jika jenis = 'sarana_prasarana' → return role yang mengurus sarana
     * - Jika jenis = 'kesejahteraan_siswa' → return role yang mengurus siswa
     *
     * Fungsi ini berguna untuk mengetahui "laporan ini akan masuk ke
     * halaman siapa saja?"
     * ------------------------------------------------------------
     * @param  string $jenis  Jenis laporan
     * @return array          Daftar role yang boleh mengakses
     * ------------------------------------------------------------
     */
    public static function getRolesForJenis(string $jenis): array
    {
        // Laporan sarana prasarana masuk ke: sapras, guru, guru_bk, kepala_sekolah
        if ($jenis === 'sarana_prasarana') {
            return ['sapras', 'guru', 'guru_bk', 'kepala_sekolah'];
        }

        // Laporan kesejahteraan siswa masuk ke: kesiswaan, guru, guru_bk, kepala_sekolah
        return ['kesiswaan', 'guru', 'guru_bk', 'kepala_sekolah'];
    }
}
