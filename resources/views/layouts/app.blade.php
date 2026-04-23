<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Pengaduan Sarana') - SMK Sangkuriang 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --smk-merah:#C0392B; --smk-merah-gelap:#922B21;
            --smk-biru:#1A5276;  --smk-biru-muda:#2E86C1;
            --smk-kuning:#F4D03F; --smk-kuning-gelap:#D4AC0D;
        }
        body { background:#f0f4f8; font-family:'Segoe UI',sans-serif; min-height:100vh; }
        /* NAVBAR */
        .navbar-smk {
            background:linear-gradient(90deg,var(--smk-merah) 0%,var(--smk-merah-gelap) 35%,var(--smk-biru) 100%);
            box-shadow:0 4px 15px rgba(0,0,0,.3); padding:.5rem 1rem; min-height:60px;
        }
        .navbar-smk .navbar-brand { color:var(--smk-kuning)!important; font-weight:800; font-size:1rem; }
        /* SIDEBAR */
        .sidebar {
            background:linear-gradient(180deg,var(--smk-biru) 0%,#0d2b40 100%);
            min-height:calc(100vh - 60px); width:230px; position:fixed; top:60px; left:0;
            z-index:100; box-shadow:4px 0 15px rgba(0,0,0,.2); padding:.75rem 0; transition:transform .3s;
        }
        .sidebar .nav-link {
            color:rgba(255,255,255,.82)!important; padding:.65rem 1.25rem; font-weight:500;
            transition:all .2s; display:flex; align-items:center; gap:.6rem; font-size:.92rem;
            border-left:4px solid transparent; text-decoration:none;
        }
        .sidebar .nav-link:hover { color:var(--smk-kuning)!important; background:rgba(255,255,255,.12); padding-left:1.75rem; border-left:4px solid var(--smk-kuning); }
        .sidebar .nav-link.active { color:var(--smk-kuning)!important; background:rgba(255,255,255,.15); padding-left:1.75rem; border-left:4px solid var(--smk-kuning); }
        .sidebar .nav-section { color:rgba(255,255,255,.4); font-size:.7rem; font-weight:700; text-transform:uppercase; padding:.6rem 1.25rem .2rem; letter-spacing:.8px; }
        .sidebar .nav-divider { border-color:rgba(255,255,255,.12); margin:.4rem 1rem; }
        /* MAIN */
        .main-content { margin-left:230px; padding:1.5rem; min-height:calc(100vh - 60px); }
        /* CARDS */
        .card-smk { border:none; border-radius:14px; box-shadow:0 3px 18px rgba(0,0,0,.08); transition:transform .2s,box-shadow .2s; overflow:hidden; }
        .card-smk:hover { transform:translateY(-2px); box-shadow:0 7px 25px rgba(0,0,0,.12); }
        .card-smk .card-header { font-weight:700; padding:.9rem 1.3rem; border-bottom:none; }
        /* STAT CARDS */
        .stat-card { border-radius:14px; color:white; padding:1.3rem; position:relative; overflow:hidden; }
        .stat-card .stat-icon { font-size:2.8rem; opacity:.18; position:absolute; right:1rem; top:50%; transform:translateY(-50%); }
        .stat-card.merah   { background:linear-gradient(135deg,var(--smk-merah),var(--smk-merah-gelap)); }
        .stat-card.biru    { background:linear-gradient(135deg,var(--smk-biru-muda),var(--smk-biru)); }
        .stat-card.kuning  { background:linear-gradient(135deg,var(--smk-kuning-gelap),#B7950B); }
        .stat-card.hijau   { background:linear-gradient(135deg,#27AE60,#1E8449); }
        .stat-card.ungu    { background:linear-gradient(135deg,#8E44AD,#6C3483); }
        .stat-card.tosca   { background:linear-gradient(135deg,#16A085,#0E6655); }
        /* BADGE STATUS */
        .badge-menunggu { background:var(--smk-kuning); color:#333; }
        .badge-proses   { background:var(--smk-biru-muda); color:white; }
        .badge-selesai  { background:#27AE60; color:white; }
        .badge-sarana   { background:#E8F4FD; color:#1A5276; }
        .badge-kesejahteraan { background:#F9F0FF; color:#6C3483; }
        /* PROGRESS TRACKER */
        .progress-tracker { display:flex; justify-content:space-between; position:relative; margin:1.5rem 0; }
        .progress-tracker::before { content:''; position:absolute; top:20px; left:10%; right:10%; height:4px; background:#ddd; z-index:0; }
        .tracker-step { text-align:center; position:relative; z-index:1; flex:1; }
        .tracker-icon { width:42px; height:42px; border-radius:50%; background:#ddd; display:flex; align-items:center; justify-content:center; margin:0 auto .5rem; font-size:1.1rem; transition:all .3s; }
        .tracker-step.done .tracker-icon    { background:#27AE60; color:white; }
        .tracker-step.current .tracker-icon { background:var(--smk-biru-muda); color:white; box-shadow:0 0 0 6px rgba(46,134,193,.2); }
        /* BUTTONS */
        .btn-smk-merah  { background:var(--smk-merah); border:none; color:white; }
        .btn-smk-merah:hover  { background:var(--smk-merah-gelap); color:white; }
        .btn-smk-biru   { background:var(--smk-biru); border:none; color:white; }
        .btn-smk-biru:hover   { background:var(--smk-biru-muda); color:white; }
        .btn-smk-kuning { background:var(--smk-kuning); border:none; color:#333; font-weight:600; }
        .btn-smk-kuning:hover { background:var(--smk-kuning-gelap); color:white; }
        /* TABLE */
        .table-smk thead th { background:var(--smk-biru); color:white; font-weight:600; border:none; white-space:nowrap; }
        .table-smk tbody tr:hover { background:rgba(26,82,118,.04); }
        /* MISC */
        .form-control:focus,.form-select:focus { border-color:var(--smk-biru-muda); box-shadow:0 0 0 .2rem rgba(46,134,193,.25); }
        .alert { border-radius:12px; border:none; }
        .header-strip { background:linear-gradient(90deg,var(--smk-kuning),var(--smk-merah),var(--smk-biru)); height:5px; }
        .foto-thumb { width:60px; height:60px; object-fit:cover; border-radius:8px; cursor:pointer; border:2px solid #dee2e6; transition:transform .2s; }
        .foto-thumb:hover { transform:scale(1.1); border-color:var(--smk-biru-muda); }
        .foto-preview { max-width:100%; max-height:250px; border-radius:12px; object-fit:cover; box-shadow:0 4px 15px rgba(0,0,0,.15); }
        .role-badge { font-size:.75rem; padding:.3rem .6rem; border-radius:20px; font-weight:700; }
        .role-kepala_sekolah { background:#1A5276; color:white; }
        .role-guru           { background:#117A65; color:white; }
        .role-sapras         { background:#C0392B; color:white; }
        .role-kesiswaan      { background:#6C3483; color:white; }
        .role-guru_bk        { background:#B7950B; color:white; }
        .fade-in { animation:fadeIn .4s ease-in; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
        @media(max-width:768px){ .sidebar{transform:translateX(-100%)} .sidebar.show{transform:translateX(0)} .main-content{margin-left:0;padding:1rem} }
    </style>
    @yield('extra-css')
</head>
<body>
<div class="header-strip"></div>

{{-- NAVBAR --}}
<nav class="navbar navbar-smk">
    <div class="container-fluid">
        <button class="btn btn-link text-white d-lg-none me-2 p-0" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        <a class="navbar-brand" href="#">
            <i class="bi bi-shield-check me-1"></i>SMK Sangkuriang 1 &mdash; Pengaduan Sarana
        </a>
        <div class="ms-auto d-flex align-items-center gap-2">
            @if(session('role') === 'admin')
                <span class="role-badge role-{{ session('admin_role') }}">
                    {{ session('admin_role_label') }}
                </span>
                <span class="text-white small d-none d-md-inline">{{ session('admin_nama') }}</span>
            @elseif(session('role') === 'siswa')
                <span class="badge" style="background:var(--smk-kuning);color:#333">
                    <i class="bi bi-mortarboard me-1"></i>{{ session('siswa_nama') }}
                </span>
            @endif
            <a href="/logout" class="btn btn-sm btn-outline-light ms-1">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>

<div class="d-flex">
    {{-- SIDEBAR --}}
    <div class="sidebar" id="sidebar">
        @if(session('role') === 'admin')
            <div class="nav-section">Menu Utama</div>
            <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            @if(in_array(session('admin_role'), ['kepala_sekolah','guru','sapras','guru_bk']))
            <a href="/admin/aspirasi?jenis=sarana_prasarana" class="nav-link {{ request()->is('admin/aspirasi*') && request('jenis') == 'sarana_prasarana' ? 'active' : '' }}">
                <i class="bi bi-tools"></i> Sarana &amp; Prasarana
            </a>
            @endif
            @if(in_array(session('admin_role'), ['kepala_sekolah','guru','kesiswaan','guru_bk']))
            <a href="/admin/aspirasi?jenis=kesejahteraan_siswa" class="nav-link {{ request()->is('admin/aspirasi*') && request('jenis') == 'kesejahteraan_siswa' ? 'active' : '' }}">
                <i class="bi bi-heart-pulse"></i> Kesejahteraan Siswa
            </a>
            @endif
            <a href="/admin/aspirasi" class="nav-link {{ request()->is('admin/aspirasi') && !request('jenis') ? 'active' : '' }}">
                <i class="bi bi-inbox"></i> Semua Laporan
            </a>
        @else
            <div class="nav-section">Menu Siswa</div>
            <a href="/siswa/dashboard" class="nav-link {{ request()->is('siswa/dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <hr class="nav-divider">
            <div class="nav-section">Buat Laporan</div>
            <a href="/siswa/aspirasi" class="nav-link {{ request()->is('siswa/aspirasi') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Buat Aspirasi
            </a>
            <hr class="nav-divider">
            <div class="nav-section">Riwayat</div>
            <a href="/siswa/histori" class="nav-link {{ request()->is('siswa/histori*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Histori Laporan
            </a>
        @endif
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="main-content w-100">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show fade-in">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show fade-in">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar untuk tampilan mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });
    // Auto-close alert setelah 4 detik
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(el) {
            try { new bootstrap.Alert(el).close(); } catch(e) {}
        });
    }, 4500);
</script>
@yield('extra-js')
</body>
</html>
