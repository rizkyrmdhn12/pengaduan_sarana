<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================
 * Model: Kategori
 * ============================================================
 * Model ini merepresentasikan tabel 'kategoris' di database
 * yang menyimpan kategori laporan
 *
 * Ada 2 jenis kategori:
 * - sarana_prasarana    : Toilet, Ruang Kelas, Lab, dll
 * - kesejahteraan_siswa : Bullying, Masalah Psikologis, dll
 * ============================================================
 */
class Kategori extends Model
{
    // Nama tabel di database
    protected $table = 'kategoris';

    // Primary key tabel ini adalah 'id_kategori'
    protected $primaryKey = 'id_kategori';

    // Kolom yang boleh diisi secara massal
    protected $fillable = ['ket_kategori', 'jenis'];

    /**
     * ------------------------------------------------------------
     * RELASI: aspirasis()
     * ------------------------------------------------------------
     * Jenis Relasi: ONE TO MANY
     * 1 kategori bisa dipakai oleh banyak laporan aspirasi
     * ------------------------------------------------------------
     */
    public function aspirasis()
    {
        // 1 kategori memiliki banyak aspirasi
        return $this->hasMany(Aspirasi::class, 'id_kategori', 'id_kategori');
    }
}
