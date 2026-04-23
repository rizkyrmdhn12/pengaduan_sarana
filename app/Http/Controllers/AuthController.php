<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;

/**
 * ============================================================
 * AuthController - Controller untuk proses autentikasi
 * ============================================================
 * Menangani: login (admin & siswa), logout
 * Siswa bisa login menggunakan NIS ATAU nama lengkap
 * ============================================================
 */
class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     * Jika user sudah login (ada session) → langsung redirect ke dashboard.
     */
    public function showLogin()
    {
        // Cek apakah user sudah login sebelumnya lewat session
        if (Session::has('admin_id') || Session::has('siswa_nis')) {
            return redirect(
                Session::get('role') === 'siswa' ? '/siswa/dashboard' : '/admin/dashboard'
            );
        }
        return view('auth.login');
    }

    /**
     * Proses login dari form.
     *
     * Alur kerja:
     * 1. Validasi input form
     * 2. Jika role admin  → cari di tabel admins by username, cek password hash
     * 3. Jika role siswa  → cari di tabel siswas by NIS ATAU nama_siswa
     * 4. Berhasil         → simpan ke Session, redirect dashboard
     * 5. Gagal            → kembali ke form dengan pesan error
     */
    public function login(Request $request)
    {
        // Validasi: semua field wajib diisi, role hanya boleh 2 nilai
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role'     => 'required|in:admin,siswa',
        ]);

        // ── LOGIN ADMIN ──────────────────────────────────────────
        if ($request->role === 'admin') {

            // Cari admin berdasarkan username yang diinput
            $admin = DB::table('admins')
                ->where('username', $request->username)
                ->first();

            // Hash::check() = bandingkan password input dengan hash bcrypt di DB
            if ($admin && Hash::check($request->password, $admin->password)) {

                // Simpan semua info admin ke Session
                Session::put('admin_id',         $admin->id);
                Session::put('admin_username',   $admin->username);
                Session::put('admin_nama',       $admin->nama);
                Session::put('admin_role',       $admin->role);
                Session::put('admin_role_label', Admin::getRoleLabel($admin->role));
                Session::put('role', 'admin');

                return redirect('/admin/dashboard')
                    ->with('success', 'Selamat datang, ' . $admin->nama . '!');
            }

        // ── LOGIN SISWA ──────────────────────────────────────────
        } else {

            // Siswa bisa login dengan NIS (angka) ATAU nama lengkap (huruf)
            // is_numeric() = cek apakah input berupa angka
            if (is_numeric($request->username)) {
                // Input berupa angka → cari berdasarkan NIS
                $siswa = DB::table('siswas')
                    ->where('nis', $request->username)
                    ->first();
            } else {
                // Input berupa teks → cari berdasarkan nama_siswa
                // LIKE untuk pencarian tidak case-sensitive
                $siswa = DB::table('siswas')
                    ->where('nama_siswa', 'LIKE', $request->username)
                    ->first();
            }

            // Cek apakah siswa ditemukan dan password cocok
            if ($siswa && Hash::check($request->password, $siswa->password)) {

                // Simpan data siswa ke Session
                Session::put('siswa_nis',   $siswa->nis);
                Session::put('siswa_nama',  $siswa->nama_siswa);
                Session::put('siswa_kelas', $siswa->kelas);
                Session::put('role', 'siswa');

                return redirect('/siswa/dashboard')
                    ->with('success', 'Selamat datang, ' . $siswa->nama_siswa . '!');
            }
        }

        // Login gagal → kembali ke form, tampilkan error, input tetap ada
        return back()
            ->withErrors(['login' => 'Username/NIS/Nama atau password salah!'])
            ->withInput();
    }

    /**
     * Proses logout.
     * Session::flush() = hapus SEMUA data session → user dianggap keluar.
     */
    public function logout()
    {
        Session::flush();
        return redirect('/login')->with('success', 'Berhasil logout. Sampai jumpa!');
    }
}
