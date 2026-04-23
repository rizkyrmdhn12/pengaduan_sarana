<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================
 * Model: InputAspirasi
 * ============================================================
 * Model ini merepresentasikan tabel 'input_aspirasis' di database
 * yang menyimpan ISI/DETAIL laporan yang dikirim siswa
 *
 * Kolom penting:
 * - nis          : NIS siswa yang mengirim
 * - id_aspirasi  : Referensi ke tabel aspirasis (untuk status & feedback)
 * - id_kategori  : Referensi ke tabel kategoris
 * - lokasi       : Lokasi kejadian
 * - ket          : Keterangan detail masalah
 * - foto         : Path file foto bukti (nullable)
 * - anonim       : 1 jika siswa ingin identitasnya disembunyikan
 * ============================================================
 */
class InputAspirasi extends Model
{
    // Nama tabel di database
    protected $table = 'input_aspirasis';

    // Primary key tabel ini adalah 'id_pelaporan'
    protected $primaryKey = 'id_pelaporan';

    // Kolom yang boleh diisi secara massal
    protected $fillable = ['nis', 'id_aspirasi', 'id_kategori', 'lokasi', 'ket', 'foto', 'anonim'];

    /**
     * ------------------------------------------------------------
     * RELASI: siswa()
     * ------------------------------------------------------------
     * Jenis Relasi: MANY TO ONE (belongsTo)
     * Banyak laporan dimiliki oleh 1 siswa
     * ------------------------------------------------------------
     */
    public function siswa()
    {
        // Laporan ini dikirim oleh satu siswa tertentu
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    /**
     * ------------------------------------------------------------
     * RELASI: aspirasi()
     * ------------------------------------------------------------
     * Jenis Relasi: MANY TO ONE (belongsTo)
     * Input laporan ini terhubung ke 1 data aspirasi (status & feedback)
     * ------------------------------------------------------------
     */
    public function aspirasi()
    {
        // Terhubung ke data aspirasi (status & feedback)
        return $this->belongsTo(Aspirasi::class, 'id_aspirasi', 'id_aspirasi');
    }

    /**
     * ------------------------------------------------------------
     * RELASI: kategori()
     * ------------------------------------------------------------
     * Jenis Relasi: MANY TO ONE (belongsTo)
     * Banyak laporan bisa masuk ke 1 kategori yang sama
     * ------------------------------------------------------------
     */
    public function kategori()
    {
        // Laporan ini masuk ke satu kategori tertentu
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
