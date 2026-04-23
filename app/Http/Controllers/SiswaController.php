<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Aspirasi;

/**
 * ============================================================
 * SiswaController - Controller untuk semua fitur halaman siswa
 * ============================================================
 * Controller ini menangani semua aksi yang dilakukan siswa:
 * 1. Menampilkan dashboard siswa dengan statistik laporan miliknya
 * 2. Menampilkan form pengiriman aspirasi/laporan
 * 3. Memproses dan menyimpan laporan baru (termasuk upload foto)
 * 4. Menampilkan histori semua laporan yang pernah dikirim
 * 5. Menampilkan detail dan progres satu laporan
 * ============================================================
 */
class SiswaController extends Controller
{
    /**
     * ------------------------------------------------------------
     * FUNGSI: dashboard()
     * ------------------------------------------------------------
     * Tujuan : Menampilkan halaman dashboard khusus milik siswa
     *          yang sedang login
     *
     * Cara kerja:
     * 1. Ambil NIS siswa dari Session (disimpan saat login)
     * 2. Hitung statistik laporan milik siswa ini saja
     * 3. Ambil 5 laporan terbaru milik siswa ini
     * 4. Kirim data ke view untuk ditampilkan
     * ------------------------------------------------------------
     */
    public function dashboard()
    {
        // Ambil NIS siswa yang sedang login dari Session
        $nis = Session::get('siswa_nis');

        // ── HITUNG STATISTIK LAPORAN SISWA INI ───────────────────
        // Menggunakan array untuk menyimpan semua statistik sekaligus
        // Setiap query di-filter dengan where('nis', $nis)
        // supaya hanya menghitung laporan milik siswa ini saja
        $stats = [
            // Total semua laporan yang pernah dikirim siswa ini
            'total' => DB::table('input_aspirasis')
                ->where('nis', $nis)
                ->count(),

            // Jumlah laporan yang statusnya masih 'Menunggu'
            // Perlu JOIN ke tabel aspirasis untuk mengakses kolom 'status'
            'menunggu' => DB::table('input_aspirasis as ia')
                ->join('aspirasis as a', 'ia.id_aspirasi', '=', 'a.id_aspirasi')
                ->where('ia.nis', $nis)
                ->where('a.status', 'Menunggu')
                ->count(),

            // Jumlah laporan yang statusnya 'Proses' (sedang ditangani)
            'proses' => DB::table('input_aspirasis as ia')
                ->join('aspirasis as a', 'ia.id_aspirasi', '=', 'a.id_aspirasi')
                ->where('ia.nis', $nis)
                ->where('a.status', 'Proses')
                ->count(),

            // Jumlah laporan yang statusnya 'Selesai'
            'selesai' => DB::table('input_aspirasis as ia')
                ->join('aspirasis as a', 'ia.id_aspirasi', '=', 'a.id_aspirasi')
                ->where('ia.nis', $nis)
                ->where('a.status', 'Selesai')
                ->count(),

            // Jumlah laporan kategori sarana prasarana milik siswa ini
            // Perlu JOIN ke tabel kategoris untuk filter berdasarkan 'jenis'
            'sarana' => DB::table('input_aspirasis as ia')
                ->join('kategoris as k', 'ia.id_kategori', '=', 'k.id_kategori')
                ->where('ia.nis', $nis)
                ->where('k.jenis', 'sarana_prasarana')
                ->count(),

            // Jumlah laporan kategori kesejahteraan siswa
            'kesejahteraan' => DB::table('input_aspirasis as ia')
                ->join('kategoris as k', 'ia.id_kategori', '=', 'k.id_kategori')
                ->where('ia.nis', $nis)
                ->where('k.jenis', 'kesejahteraan_siswa')
                ->count(),
        ];

        // ── AMBIL 5 LAPORAN TERBARU MILIK SISWA INI ──────────────
        $histori = DB::table('input_aspirasis as ia')
            ->join('aspirasis as a', 'ia.id_aspirasi', '=', 'a.id_aspirasi')
            ->join('kategoris as k', 'ia.id_kategori', '=', 'k.id_kategori')
            ->select('ia.*', 'a.status', 'a.feedback', 'k.ket_kategori', 'k.jenis')
            ->where('ia.nis', $nis)           // Hanya milik siswa yang login
            ->orderBy('ia.created_at', 'desc') // Terbaru di atas
            ->limit(5)                          // Batasi 5 data saja
            ->get();

        return view('siswa.dashboard', compact('stats', 'histori'));
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: formAspirasi()
     * ------------------------------------------------------------
     * Tujuan : Menampilkan halaman form pengiriman laporan/aspirasi
     *
     * Cara kerja:
     * - Ambil semua kategori dari database
     * - Pisahkan kategori menjadi 2 kelompok berdasarkan jenis:
     *   1. sarana_prasarana    (Toilet, Ruang Kelas, Lab, dll)
     *   2. kesejahteraan_siswa (Bullying, Kekerasan, Masalah Psikologis, dll)
     * - Kirim ke view dalam format array 2 dimensi
     *   supaya bisa ditampilkan secara terpisah di form
     * ------------------------------------------------------------
     */
    public function formAspirasi()
    {
        // Ambil semua kategori dari database sekaligus
        $semuaKategori = DB::table('kategoris')->get();

        // Pisahkan ke dalam array berdasarkan jenis menggunakan where() collection
        // ->values() = reset nomor index array agar mulai dari 0
        $kategoris = [
            'sarana_prasarana'    => $semuaKategori->where('jenis', 'sarana_prasarana')->values(),
            'kesejahteraan_siswa' => $semuaKategori->where('jenis', 'kesejahteraan_siswa')->values(),
        ];

        // Kirim array kategoris ke view
        return view('siswa.form_aspirasi', compact('kategoris'));
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: simpanAspirasi()
     * ------------------------------------------------------------
     * Tujuan : Memproses dan menyimpan laporan baru yang dikirim siswa
     *
     * Cara kerja:
     * 1. Validasi semua input dari form
     * 2. Jika ada foto yang diupload → simpan ke folder storage
     * 3. Buat record baru di tabel 'aspirasis' (status awal: Menunggu)
     * 4. Buat record baru di tabel 'input_aspirasis' dengan semua detail
     * 5. Redirect ke histori dengan pesan sukses
     *
     * Kenapa 2 tabel? Karena:
     * - Tabel 'aspirasis' menyimpan status & feedback (bisa berubah)
     * - Tabel 'input_aspirasis' menyimpan isi laporan (tidak berubah)
     * ------------------------------------------------------------
     * @param Request $request  Data dari form laporan
     * ------------------------------------------------------------
     */
    public function simpanAspirasi(Request $request)
    {
        // ── LANGKAH 1: VALIDASI INPUT ────────────────────────────
        $request->validate([
            // id_kategori harus ada dan harus valid (ada di tabel kategoris)
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'lokasi'      => 'required|string|max:100',
            'ket'         => 'required|string|min:10',  // Minimal 10 karakter agar jelas

            // Foto bersifat opsional (nullable)
            // Jika diisi, harus berupa file gambar dengan format tertentu, maks 5MB
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'id_kategori.required' => 'Kategori wajib dipilih',
            'id_kategori.exists'   => 'Kategori tidak valid',
            'lokasi.required'      => 'Lokasi wajib diisi',
            'ket.required'         => 'Keterangan wajib diisi',
            'ket.min'              => 'Keterangan minimal 10 karakter',
            'foto.image'           => 'File harus berupa gambar',
            'foto.mimes'           => 'Format foto: JPG, PNG, atau WEBP',
            'foto.max'             => 'Ukuran foto maksimal 5MB',
        ]);

        // ── LANGKAH 2: PROSES UPLOAD FOTO (JIKA ADA) ─────────────
        $fotoPath = null; // Default null jika tidak ada foto

        // hasFile('foto') = cek apakah ada file yang diupload dengan nama 'foto'
        // isValid()       = cek apakah file tidak rusak/corrupt
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {

            // ->store('foto_laporan', 'public') = simpan file ke folder
            // 'foto_laporan' di dalam disk 'public' (storage/app/public/foto_laporan/)
            // Nama file dibuat otomatis secara UNIK oleh Laravel
            // agar tidak terjadi konflik nama file
            $fotoPath = $request->file('foto')->store('foto_laporan', 'public');
        }

        // ── LANGKAH 3: SIMPAN KE TABEL ASPIRASIS ─────────────────
        // insertGetId() = insert data DAN return ID yang baru dibuat
        // ID ini dibutuhkan untuk dimasukkan ke tabel input_aspirasis
        $id_aspirasi = DB::table('aspirasis')->insertGetId([
            'status'      => 'Menunggu',         // Status awal selalu 'Menunggu'
            'id_kategori' => $request->id_kategori,
            'feedback'    => null,               // Belum ada feedback dari admin
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // ── LANGKAH 4: SIMPAN KE TABEL INPUT_ASPIRASIS ───────────
        // Tabel ini menyimpan detail isi laporan dari siswa
        DB::table('input_aspirasis')->insert([
            'nis'         => Session::get('siswa_nis'), // NIS dari session (siswa yang login)
            'id_aspirasi' => $id_aspirasi,              // ID aspirasi yang baru dibuat di atas
            'id_kategori' => $request->id_kategori,
            'lokasi'      => $request->lokasi,
            'ket'         => $request->ket,
            'foto'        => $fotoPath,                 // Path foto (null jika tidak ada)

            // has('anonim') = cek apakah checkbox 'anonim' dicentang
            // Jika dicentang = 1 (true), tidak dicentang = 0 (false)
            'anonim'      => $request->has('anonim') ? 1 : 0,

            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Redirect ke halaman histori dengan pesan sukses
        return redirect('/siswa/histori')
            ->with('success', 'Aspirasi berhasil dikirim! Status: Menunggu 📬');
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: histori()
     * ------------------------------------------------------------
     * Tujuan : Menampilkan semua laporan yang pernah dikirim
     *          oleh siswa yang sedang login
     *
     * Cara kerja:
     * - Ambil NIS dari Session
     * - Query JOIN 3 tabel untuk mendapat info lengkap tiap laporan
     * - Filter hanya milik NIS ini
     * - Urutkan dari yang paling baru
     * ------------------------------------------------------------
     */
    public function histori()
    {
        // Ambil NIS siswa yang sedang login
        $nis = Session::get('siswa_nis');

        // Ambil semua laporan milik siswa ini dengan JOIN
        // untuk mendapatkan status, feedback, nama kategori, dan jenis
        $histori = DB::table('input_aspirasis as ia')
            ->join('aspirasis as a', 'ia.id_aspirasi', '=', 'a.id_aspirasi')
            ->join('kategoris as k', 'ia.id_kategori', '=', 'k.id_kategori')
            ->select('ia.*', 'a.status', 'a.feedback', 'k.ket_kategori', 'k.jenis')
            ->where('ia.nis', $nis)            // Filter hanya milik siswa ini
            ->orderBy('ia.created_at', 'desc') // Laporan terbaru tampil di atas
            ->get();                            // Ambil semua data (tanpa limit)

        return view('siswa.histori', compact('histori'));
    }

    /**
     * ------------------------------------------------------------
     * FUNGSI: detailAspirasi()
     * ------------------------------------------------------------
     * Tujuan : Menampilkan detail lengkap satu laporan beserta
     *          progres penanganan dan umpan balik dari admin
     *
     * Cara kerja:
     * 1. Ambil NIS dari Session untuk keamanan
     * 2. Cari laporan berdasarkan ID
     * 3. Sekaligus verifikasi bahwa laporan ini milik siswa yang login
     *    (where nis = NIS siswa yang login) → mencegah siswa A
     *    mengakses laporan milik siswa B dengan menebak URL
     * 4. Jika tidak ditemukan → redirect dengan pesan error
     * 5. Jika ditemukan → tampilkan halaman detail
     * ------------------------------------------------------------
     * @param int $id  ID laporan (id_pelaporan) dari URL
     * ------------------------------------------------------------
     */
    public function detailAspirasi($id)
    {
        // Ambil NIS siswa yang sedang login dari Session
        $nis = Session::get('siswa_nis');

        // Cari detail laporan lengkap dengan JOIN semua tabel terkait
        $aspirasi = DB::table('input_aspirasis as ia')
            ->join('aspirasis as a', 'ia.id_aspirasi', '=', 'a.id_aspirasi')
            ->join('kategoris as k', 'ia.id_kategori', '=', 'k.id_kategori')
            ->select(
                'ia.*',
                'a.status',
                'a.feedback',
                'a.updated_at as status_updated_at', // Kapan status terakhir diubah
                'k.ket_kategori',
                'k.jenis'
            )
            ->where('ia.id_pelaporan', $id)  // Cari berdasarkan ID laporan
            ->where('ia.nis', $nis)           // KEAMANAN: pastikan milik siswa ini
            ->first();

        // Jika laporan tidak ditemukan atau bukan milik siswa ini
        if (!$aspirasi) {
            return redirect('/siswa/histori')
                ->with('error', 'Data tidak ditemukan');
        }

        // Tampilkan halaman detail laporan
        return view('siswa.detail_aspirasi', compact('aspirasi'));
    }
}
