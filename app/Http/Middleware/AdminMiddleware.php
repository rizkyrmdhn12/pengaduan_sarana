<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * ============================================================
 * Middleware: AdminMiddleware
 * ============================================================
 * Middleware adalah "penjaga pintu" yang memeriksa setiap
 * request sebelum masuk ke halaman admin.
 *
 * Cara kerja middleware:
 * 1. User mencoba akses halaman admin (misal: /admin/dashboard)
 * 2. Sebelum halaman tampil, middleware ini dijalankan dulu
 * 3. Jika lolos → request diteruskan ke controller
 * 4. Jika tidak lolos → redirect ke halaman login
 *
 * Middleware ini terdaftar di bootstrap/app.php dengan alias
 * 'admin.auth' dan dipakai di routes/web.php
 * ============================================================
 */
class AdminMiddleware
{
    /**
     * ------------------------------------------------------------
     * FUNGSI: handle()
     * ------------------------------------------------------------
     * Tujuan : Memverifikasi bahwa yang mengakses halaman admin
     *          adalah admin yang sudah login dengan benar
     *
     * Cara kerja:
     * 1. Cek apakah ada session 'admin_id' (artinya sudah login)
     * 2. Cek apakah role di session adalah 'admin'
     * 3. Jika salah satu tidak terpenuhi → tolak, redirect ke login
     * 4. Jika lolos → lanjutkan request ke controller tujuan
     * ------------------------------------------------------------
     * @param  Request  $request  Data request HTTP yang masuk
     * @param  Closure  $next     Fungsi untuk meneruskan request
     * @return mixed
     * ------------------------------------------------------------
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek 2 kondisi keamanan sekaligus:
        // 1. !Session::has('admin_id') = belum login sebagai admin
        // 2. Session::get('role') !== 'admin' = role bukan admin
        if (!Session::has('admin_id') || Session::get('role') !== 'admin') {

            // Tolak akses, arahkan ke halaman login dengan pesan
            return redirect('/login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu');
        }

        // Semua cek lolos → teruskan request ke controller yang dituju
        return $next($request);
    }
}
