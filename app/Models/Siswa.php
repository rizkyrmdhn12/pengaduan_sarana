<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================
 * Model: Siswa
 * ============================================================
 * Model ini merepresentasikan tabel 'siswas' di database
 * yang menyimpan data siswa yang bisa login ke aplikasi
 * ============================================================
 */
class Siswa extends Model
{
    // Nama tabel di database
    protected $table = 'siswas';

    // Primary key tabel ini bukan 'id' (default Laravel)
    // melainkan 'nis' (Nomor Induk Siswa)
    protected $primaryKey = 'nis';

    // Kolom yang boleh diisi secara massal
    protected $fillable = ['nis', 'nama_siswa', 'kelas', 'password'];

    // Sembunyikan password agar tidak ikut tampil di response
    protected $hidden = ['password'];

    /**
     * ------------------------------------------------------------
     * RELASI: inputAspirasis()
     * ------------------------------------------------------------
     * Tujuan : Mendefinisikan relasi antara tabel siswas dan
     *          tabel input_aspirasis
     *
     * Jenis Relasi: ONE TO MANY (Satu siswa bisa punya banyak laporan)
     * Artinya: 1 siswa → banyak input_aspirasis
     *
     * hasMany() = relasi 1 ke Banyak
     * Parameter:
     * - InputAspirasi::class = model yang berelasi
     * - 'nis'                = foreign key di tabel input_aspirasis
     * - 'nis'                = primary key di tabel siswas
     * ------------------------------------------------------------
     */
    public function inputAspirasis()
    {
        // 1 siswa memiliki banyak laporan (input aspirasi)
        return $this->hasMany(InputAspirasi::class, 'nis', 'nis');
    }
}
