<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * ============================================================
 * RegisterController - Controller untuk pendaftaran siswa baru
 * ============================================================
 * Controller ini menangani proses registrasi akun siswa baru
 * agar bisa login ke aplikasi pengaduan
 * ============================================================
 */
class RegisterController extends Controller
{
    /**
     * ------------------------------------------------------------
     * FUNGSI: showForm()
     * ------------------------------------------------------------
     * Tujuan : Menampilkan halaman form registrasi
     *
     * Cara kerja:
     * - Cukup memanggil view 'auth.register'
     * - Tidak perlu kirim data apapun ke view
     * ------------------------------------------------------------
     */
    public function showForm()
    {
        // Tampilkan halaman form registrasi siswa
        return view('auth.register');
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: register()
     * ------------------------------------------------------------
     * Tujuan : Memproses data registrasi dan menyimpan akun baru
     *
     * Cara kerja:
     * 1. Validasi semua input dari form registrasi
     * 2. Pastikan NIS belum terdaftar sebelumnya (unique)
     * 3. Hash password agar tidak tersimpan sebagai teks biasa
     * 4. Simpan data siswa baru ke tabel 'siswas'
     * 5. Redirect ke halaman login dengan pesan sukses
     * ------------------------------------------------------------
     * @param Request $request  Data yang dikirim dari form registrasi
     * ------------------------------------------------------------
     */
    public function register(Request $request)
    {
        // ── LANGKAH 1: VALIDASI INPUT ────────────────────────────
        // Aturan validasi untuk setiap field form registrasi
        $request->validate([
            // NIS harus diisi, berupa angka, 5-10 digit,
            // dan belum ada di tabel siswas (unique)
            'nis'        => 'required|numeric|digits_between:5,10|unique:siswas,nis',

            // Nama harus diisi, berupa string, minimal 3 karakter
            'nama_siswa' => 'required|string|min:3|max:100',

            // Kelas harus diisi, maksimal 10 karakter
            'kelas'      => 'required|string|max:10',

            // Password harus diisi, minimal 6 karakter,
            // 'confirmed' = harus ada field password_confirmation yang nilainya sama
            'password'   => 'required|min:6|confirmed',

        // Pesan error custom dalam Bahasa Indonesia
        ], [
            'nis.required'       => 'NIS wajib diisi',
            'nis.numeric'        => 'NIS harus berupa angka',
            'nis.digits_between' => 'NIS harus 5-10 digit',
            'nis.unique'         => 'NIS sudah terdaftar, hubungi admin jika ada masalah',
            'nama_siswa.required'=> 'Nama lengkap wajib diisi',
            'nama_siswa.min'     => 'Nama minimal 3 karakter',
            'kelas.required'     => 'Kelas wajib diisi',
            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // ── LANGKAH 2: SIMPAN DATA SISWA BARU ───────────────────
        // DB::table()->insert() = memasukkan 1 baris data baru ke tabel

        DB::table('siswas')->insert([
            'nis'        => $request->nis,

            'nama_siswa' => $request->nama_siswa,

            // strtoupper() = ubah kelas jadi huruf kapital semua
            // Contoh: "xii rpl 1" → "XII RPL 1"
            'kelas'      => strtoupper($request->kelas),

            // Hash::make() = mengenkripsi password menggunakan algoritma bcrypt
            // Password TIDAK BOLEH disimpan sebagai teks biasa di database
            // karena alasan keamanan. Hash bcrypt tidak bisa di-decode balik.
            'password'   => Hash::make($request->password),

            // now() = fungsi helper Laravel untuk mengambil waktu saat ini
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── LANGKAH 3: REDIRECT KE LOGIN ────────────────────────
        // Setelah berhasil daftar, arahkan ke halaman login
        // dengan pesan sukses
        return redirect('/login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan NIS dan password kamu 🎉');
    }
}
