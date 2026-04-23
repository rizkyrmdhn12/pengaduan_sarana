<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * ============================================================
 * Middleware: SiswaMiddleware
 * ============================================================
 * Sama seperti AdminMiddleware, tapi khusus untuk melindungi
 * halaman-halaman yang hanya boleh diakses oleh siswa.
 *
 * Middleware ini memastikan hanya siswa yang sudah login
 * yang bisa mengakses /siswa/dashboard, /siswa/aspirasi, dll
 * ============================================================
 */
class SiswaMiddleware
{
    /**
     * ------------------------------------------------------------
     * FUNGSI: handle()
     * ------------------------------------------------------------
     * Tujuan : Memverifikasi bahwa yang mengakses halaman siswa
     *          adalah siswa yang sudah login dengan benar
     *
     * Cara kerja sama seperti AdminMiddleware, tapi mengecek
     * session 'siswa_nis' dan role 'siswa'
     * ------------------------------------------------------------
     * @param  Request  $request  Data request HTTP yang masuk
     * @param  Closure  $next     Fungsi untuk meneruskan request
     * @return mixed
     * ------------------------------------------------------------
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek 2 kondisi:
        // 1. Apakah session siswa_nis ada (sudah login sebagai siswa)
        // 2. Apakah role yang tersimpan di session adalah 'siswa'
        if (!Session::has('siswa_nis') || Session::get('role') !== 'siswa') {

            // Akses ditolak, redirect ke login
            return redirect('/login')
                ->with('error', 'Silakan login sebagai siswa terlebih dahulu');
        }

        // Lolos verifikasi → teruskan ke controller
        return $next($request);
    }
}
