<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================
 * Model: Aspirasi
 * ============================================================
 * Model ini merepresentasikan tabel 'aspirasis' di database
 * yang menyimpan STATUS dan FEEDBACK setiap laporan
 *
 * Tabel ini dipisah dari input_aspirasis karena:
 * - Status dan feedback bisa berubah-ubah (diupdate admin)
 * - Isi laporan (di input_aspirasis) tidak berubah setelah dikirim
 * ============================================================
 */
class Aspirasi extends Model
{
    // Nama tabel di database
    protected $table = 'aspirasis';

    // Primary key tabel ini adalah 'id_aspirasi'
    protected $primaryKey = 'id_aspirasi';

    // Kolom yang boleh diisi secara massal
    protected $fillable = ['status', 'id_kategori', 'feedback'];

    /**
     * ------------------------------------------------------------
     * FUNGSI STATIC: getStatusList()
     * ------------------------------------------------------------
     * Tujuan : Mengembalikan daftar status yang tersedia
     *          dalam bentuk array
     *
     * Fungsi ini dipanggil di Controller untuk mengisi
     * dropdown pilihan status di form umpan balik admin
     * ------------------------------------------------------------
     * @return array  Daftar status yang tersedia
     * ------------------------------------------------------------
     */
    public static function getStatusList(): array
    {
        // Array berisi 3 status yang bisa dipilih admin
        return ['Menunggu', 'Proses', 'Selesai'];
    }

    /**
     * ------------------------------------------------------------
     * RELASI: kategori()
     * ------------------------------------------------------------
     * Jenis Relasi: MANY TO ONE (belongsTo)
     * Banyak aspirasi dimiliki oleh 1 kategori
     * ------------------------------------------------------------
     */
    public function kategori()
    {
        // Aspirasi ini milik satu kategori tertentu
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * ------------------------------------------------------------
     * RELASI: inputAspirasi()
     * ------------------------------------------------------------
     * Jenis Relasi: ONE TO ONE
     * 1 aspirasi memiliki tepat 1 data input (isi laporan)
     * ------------------------------------------------------------
     */
    public function inputAspirasi()
    {
        // 1 aspirasi memiliki 1 data input laporan
        return $this->hasOne(InputAspirasi::class, 'id_aspirasi', 'id_aspirasi');
    }
}
