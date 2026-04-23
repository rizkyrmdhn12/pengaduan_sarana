<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Models\Aspirasi;

/**
 * ============================================================
 * AdminController - Controller untuk semua fitur halaman admin
 * ============================================================
 * Controller ini menangani semua aksi yang dilakukan oleh
 * admin (kepala sekolah, guru, sapras, kesiswaan, guru BK):
 * 1. Menampilkan dashboard dengan statistik laporan
 * 2. Menampilkan daftar semua laporan (dengan filter)
 * 3. Menampilkan form umpan balik
 * 4. Menyimpan umpan balik dan mengubah status laporan
 *
 * SISTEM ROLE (Hak Akses):
 * - Kepala Sekolah → bisa lihat SEMUA laporan
 * - Guru           → bisa lihat SEMUA laporan
 * - Sapras         → HANYA laporan Sarana Prasarana
 * - Kesiswaan      → HANYA laporan Kesejahteraan Siswa
 * - Guru BK        → bisa lihat SEMUA laporan
 * ============================================================
 */
class AdminController extends Controller
{
    /**
     * ------------------------------------------------------------
     * FUNGSI PRIVATE: getAllowedJenis()
     * ------------------------------------------------------------
     * Tujuan : Menentukan jenis laporan apa saja yang boleh
     *          dilihat oleh admin yang sedang login
     *
     * Cara kerja:
     * - Ambil role admin dari Session
     * - Cocokkan dengan array mapping role → jenis laporan
     * - Return array berisi jenis laporan yang diizinkan
     *
     * Ini adalah fungsi PRIVATE artinya hanya bisa dipanggil
     * dari dalam class AdminController saja
     * ------------------------------------------------------------
     * @return array  Daftar jenis laporan yang boleh diakses
     * ------------------------------------------------------------
     */
    private function getAllowedJenis(): array
    {
        // Ambil role admin yang sedang login dari Session
        $role = Session::get('admin_role');

        // Array mapping: setiap role punya akses ke jenis laporan tertentu
        // sarana_prasarana    = laporan kerusakan fasilitas sekolah
        // kesejahteraan_siswa = laporan bullying, masalah psikologis, dll
        $mapping = [
            'kepala_sekolah' => ['sarana_prasarana', 'kesejahteraan_siswa'], // Akses penuh
            'guru'           => ['sarana_prasarana', 'kesejahteraan_siswa'], // Akses penuh
            'sapras'         => ['sarana_prasarana'],                        // Hanya sarana
            'kesiswaan'      => ['kesejahteraan_siswa'],                     // Hanya kesejahteraan
            'guru_bk'        => ['sarana_prasarana', 'kesejahteraan_siswa'], // Akses penuh
        ];

        // Kembalikan array jenis yang boleh diakses
        // ?? [] = jika role tidak ditemukan, return array kosong
        return $mapping[$role] ?? [];
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: dashboard()
     * ------------------------------------------------------------
     * Tujuan : Menampilkan halaman dashboard admin dengan
     *          statistik dan laporan terbaru
     *
     * Cara kerja:
     * 1. Ambil daftar jenis laporan yang boleh diakses (by role)
     * 2. Hitung statistik: total, menunggu, proses, selesai
     * 3. Ambil 6 laporan terbaru sesuai akses role
     * 4. Kirim semua data ke view untuk ditampilkan
     * ------------------------------------------------------------
     */
    public function dashboard()
    {
        // Ambil jenis laporan yang boleh dilihat oleh role ini
        $allowedJenis = $this->getAllowedJenis();

        // ── HITUNG STATISTIK ─────────────────────────────────────
        // Siapkan array statistik dengan nilai awal 0
        $stats = [
            'total'    => 0,
            'menunggu' => 0,
            'proses'   => 0,
            'selesai'  => 0,
        ];

        // Buat query dasar (base query) yang akan dipakai ulang
        // JOIN 3 tabel: input_aspirasis + aspirasis + kategoris
        // whereIn() = filter supaya hanya tampil jenis yang boleh diakses
        $baseQuery = DB::table('input_aspirasis as ia')
            ->join('aspirasis as a',  'ia.id_aspirasi', '=', 'a.id_aspirasi')
            ->join('kategoris as k',  'ia.id_kategori', '=', 'k.id_kategori')
            ->whereIn('k.jenis', $allowedJenis);

        // clone $baseQuery = salin query agar tidak mengubah query asli
        // Setiap clone ditambah filter status yang berbeda
        $stats['total']    = (clone $baseQuery)->count();
        $stats['menunggu'] = (clone $baseQuery)->where('a.status', 'Menunggu')->count();
        $stats['proses']   = (clone $baseQuery)->where('a.status', 'Proses')->count();
        $stats['selesai']  = (clone $baseQuery)->where('a.status', 'Selesai')->count();

        // ── AMBIL LAPORAN TERBARU ─────────────────────────────────
        // JOIN 4 tabel sekaligus untuk mendapatkan semua info yang dibutuhkan
        // select() = pilih kolom mana saja yang diambil (efisiensi query)
        // orderBy('created_at', 'desc') = urutkan dari yang paling baru
        // limit(6) = ambil maksimal 6 data saja untuk ditampilkan
        $terbaru = DB::table('input_aspirasis as ia')
            ->join('siswas as s',    'ia.nis',          '=', 's.nis')
            ->join('aspirasis as a', 'ia.id_aspirasi',  '=', 'a.id_aspirasi')
            ->join('kategoris as k', 'ia.id_kategori',  '=', 'k.id_kategori')
            ->select(
                'ia.*',           // Semua kolom dari input_aspirasis
                's.nama_siswa',   // Nama siswa dari tabel siswas
                's.kelas',        // Kelas siswa dari tabel siswas
                'a.status',       // Status laporan dari tabel aspirasis
                'k.ket_kategori', // Nama kategori dari tabel kategoris
                'k.jenis'         // Jenis laporan (sarana/kesejahteraan)
            )
            ->whereIn('k.jenis', $allowedJenis) // Filter berdasarkan hak akses role
            ->orderBy('ia.created_at', 'desc')  // Terbaru di atas
            ->limit(6)                           // Batasi 6 data
            ->get();                             // Eksekusi query dan ambil hasilnya

        // Ambil label role untuk ditampilkan di tampilan
        $roleLabel = Session::get('admin_role_label');

        // Kirim semua variabel ke view dashboard
        // compact() = cara singkat buat array ['stats'=>$stats, 'terbaru'=>$terbaru, dst]
        return view('admin.dashboard', compact('stats', 'terbaru', 'roleLabel', 'allowedJenis'));
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: listAspirasi()
     * ------------------------------------------------------------
     * Tujuan : Menampilkan daftar semua laporan dengan fitur filter
     *
     * Cara kerja:
     * 1. Buat query dasar yang sudah difilter sesuai hak akses role
     * 2. Tambahkan filter opsional berdasarkan parameter dari URL
     *    (tanggal, bulan, NIS siswa, kategori, status, jenis)
     * 3. Jalankan query dan kirim hasil ke view
     * ------------------------------------------------------------
     * @param Request $request  Parameter filter dari URL (?tanggal=&bulan=&dst)
     * ------------------------------------------------------------
     */
    public function listAspirasi(Request $request)
    {
        // Ambil jenis laporan yang boleh dilihat oleh role ini
        $allowedJenis = $this->getAllowedJenis();

        // ── BUAT QUERY DASAR ──────────────────────────────────────
        // Query ini menggabungkan 4 tabel dengan JOIN
        // untuk mendapatkan semua informasi laporan lengkap
        $query = DB::table('input_aspirasis as ia')
            ->join('siswas as s',    'ia.nis',         '=', 's.nis')
            ->join('aspirasis as a', 'ia.id_aspirasi', '=', 'a.id_aspirasi')
            ->join('kategoris as k', 'ia.id_kategori', '=', 'k.id_kategori')
            ->select('ia.*', 's.nama_siswa', 's.kelas', 'a.status', 'a.feedback', 'k.ket_kategori', 'k.jenis')
            ->whereIn('k.jenis', $allowedJenis); // Batasi sesuai hak akses

        // ── TAMBAHKAN FILTER OPSIONAL ─────────────────────────────
        // Setiap filter hanya ditambahkan jika user mengisi form filter
        // Jika kosong, tidak mempengaruhi query

        // Filter berdasarkan tanggal spesifik (contoh: 2026-04-23)
        if ($request->tanggal) {
            $query->whereDate('ia.created_at', $request->tanggal);
        }

        // Filter berdasarkan bulan (1=Januari, 2=Februari, dst)
        if ($request->bulan) {
            $query->whereMonth('ia.created_at', $request->bulan);
        }

        // Filter berdasarkan NIS siswa tertentu
        if ($request->nis) {
            $query->where('ia.nis', $request->nis);
        }

        // Filter berdasarkan kategori (Toilet, Bullying, dll)
        if ($request->id_kategori) {
            $query->where('ia.id_kategori', $request->id_kategori);
        }

        // Filter berdasarkan status (Menunggu, Proses, Selesai)
        if ($request->status) {
            $query->where('a.status', $request->status);
        }

        // Filter berdasarkan jenis laporan
        if ($request->jenis) {
            $query->where('k.jenis', $request->jenis);
        }

        // Jalankan query, urutkan dari terbaru
        $aspirasis = $query->orderBy('ia.created_at', 'desc')->get();

        // Ambil data untuk keperluan dropdown filter di tampilan
        $kategoris  = DB::table('kategoris')->whereIn('jenis', $allowedJenis)->get();
        $siswas     = DB::table('siswas')->get();
        $statusList = Aspirasi::getStatusList(); // ['Menunggu', 'Proses', 'Selesai']

        return view('admin.list_aspirasi', compact('aspirasis', 'kategoris', 'siswas', 'statusList', 'allowedJenis'));
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: showFeedback()
     * ------------------------------------------------------------
     * Tujuan : Menampilkan halaman detail laporan beserta form
     *          untuk memberikan umpan balik
     *
     * Cara kerja:
     * 1. Cari data laporan berdasarkan id_pelaporan
     * 2. Sekaligus cek apakah admin punya akses ke laporan ini
     * 3. Jika ada → tampilkan halaman feedback
     * 4. Jika tidak ada / tidak punya akses → redirect dengan error
     * ------------------------------------------------------------
     * @param int $id  ID laporan (id_pelaporan) dari URL
     * ------------------------------------------------------------
     */
    public function showFeedback($id)
    {
        // Ambil jenis yang boleh diakses oleh role ini
        $allowedJenis = $this->getAllowedJenis();

        // Ambil detail laporan lengkap dengan JOIN semua tabel terkait
        // where('ia.id_pelaporan', $id) = cari laporan dengan ID tertentu
        // whereIn('k.jenis', $allowedJenis) = sekaligus cek hak akses
        // Jika role tidak punya akses ke jenis ini, ->first() akan return null
        $aspirasi = DB::table('input_aspirasis as ia')
            ->join('siswas as s',    'ia.nis',         '=', 's.nis')
            ->join('aspirasis as a', 'ia.id_aspirasi', '=', 'a.id_aspirasi')
            ->join('kategoris as k', 'ia.id_kategori', '=', 'k.id_kategori')
            ->select('ia.*', 's.nama_siswa', 's.kelas', 'a.status', 'a.feedback', 'a.id_aspirasi', 'k.ket_kategori', 'k.jenis')
            ->where('ia.id_pelaporan', $id)
            ->whereIn('k.jenis', $allowedJenis) // Keamanan: cek hak akses role
            ->first();

        // Jika data tidak ditemukan (atau akses ditolak karena role)
        if (!$aspirasi) {
            return redirect('/admin/aspirasi')
                ->with('error', 'Data tidak ditemukan atau tidak memiliki akses');
        }

        // Ambil array status yang tersedia untuk dropdown form
        $statusList = Aspirasi::getStatusList();

        return view('admin.feedback', compact('aspirasi', 'statusList'));
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: updateFeedback()
     * ------------------------------------------------------------
     * Tujuan : Menyimpan umpan balik dan mengubah status laporan
     *
     * Cara kerja:
     * 1. Validasi input (status dan feedback harus diisi)
     * 2. Cari data di tabel input_aspirasis untuk mendapatkan id_aspirasi
     * 3. Update tabel aspirasis dengan status dan feedback baru
     * 4. Redirect ke daftar laporan dengan pesan sukses
     * ------------------------------------------------------------
     * @param Request $request  Data dari form umpan balik (status + feedback)
     * @param int     $id       ID laporan (id_pelaporan) dari URL
     * ------------------------------------------------------------
     */
    public function updateFeedback(Request $request, $id)
    {
        // ── VALIDASI INPUT ────────────────────────────────────────
        $request->validate([
            // Status harus diisi dan nilainya hanya boleh 3 pilihan ini
            'status'   => 'required|in:Menunggu,Proses,Selesai',
            // Feedback harus diisi, minimal 5 karakter
            'feedback' => 'required|string|min:5',
        ], [
            'status.required'   => 'Status wajib dipilih',
            'feedback.required' => 'Feedback wajib diisi',
            'feedback.min'      => 'Feedback minimal 5 karakter',
        ]);

        // ── CARI DATA LAPORAN ─────────────────────────────────────
        // Ambil data dari tabel input_aspirasis untuk mendapatkan id_aspirasi
        // id_aspirasi dibutuhkan karena status & feedback tersimpan di tabel aspirasis
        $input = DB::table('input_aspirasis')
            ->where('id_pelaporan', $id)
            ->first();

        // Jika data tidak ditemukan, hentikan proses
        if (!$input) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // ── UPDATE STATUS DAN FEEDBACK ────────────────────────────
        // Update tabel aspirasis berdasarkan id_aspirasi yang ditemukan
        // now() = simpan waktu saat ini sebagai updated_at
        DB::table('aspirasis')
            ->where('id_aspirasi', $input->id_aspirasi)
            ->update([
                'status'     => $request->status,
                'feedback'   => $request->feedback,
                'updated_at' => now(), // Catat kapan terakhir diupdate
            ]);

        // Redirect ke halaman daftar laporan dengan pesan sukses
        return redirect('/admin/aspirasi')
            ->with('success', 'Feedback dan status berhasil diperbarui!');
    }
}
