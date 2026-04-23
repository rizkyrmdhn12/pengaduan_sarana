<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMK Sangkuriang 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:linear-gradient(135deg,#C0392B 0%,#922B21 30%,#1A5276 70%,#154360 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; }
        .login-wrapper { width:100%; max-width:460px; padding:1rem; }
        .school-logo { width:86px; height:86px; background:linear-gradient(135deg,#F4D03F,#D4AC0D); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; font-size:2.6rem; box-shadow:0 8px 25px rgba(0,0,0,.35); animation:pulse 2s infinite; }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.06)} }
        .login-card { background:white; border-radius:22px; padding:2rem; box-shadow:0 20px 60px rgba(0,0,0,.3); }
        .color-strip { height:6px; background:linear-gradient(90deg,#C0392B,#F4D03F,#1A5276); border-radius:22px 22px 0 0; margin:-2rem -2rem 1.5rem; }
        .role-tabs label { cursor:pointer; flex:1; }
        .role-tabs .nav-link { border-radius:10px; font-weight:600; color:#555; border:2px solid #eee; transition:all .3s; text-align:center; width:100%; }
        .role-tabs .nav-link.active { background:linear-gradient(135deg,#C0392B,#1A5276); color:white; border-color:transparent; }
        .form-control { border-radius:10px; border:2px solid #eee; padding:.65rem 1rem; }
        .form-control:focus { border-color:#1A5276; box-shadow:0 0 0 3px rgba(26,82,118,.15); }
        .input-group-text { border-radius:10px 0 0 10px; background:#f8f9fa; border:2px solid #eee; border-right:none; color:#1A5276; }
        .input-group .form-control { border-radius:0 10px 10px 0; border-left:none; }
        .btn-login { background:linear-gradient(135deg,#C0392B,#1A5276); border:none; border-radius:10px; padding:.75rem; font-weight:700; transition:all .3s; }
        .btn-login:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,0,0,.3); color:white; }
        .akun-grid { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; font-size:.8rem; }
        .akun-item { background:#f8f9fa; border-radius:8px; padding:.5rem .7rem; }
        .akun-item .role-tag { font-size:.7rem; font-weight:700; padding:.1rem .4rem; border-radius:20px; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="text-center text-white mb-3">
        <div class="school-logo">🏫</div>
        <h5 class="fw-bold mb-0">SMK Sangkuriang 1</h5>
        <p class="opacity-75 small">Aplikasi Pengaduan Sarana &amp; Kesejahteraan Siswa</p>
    </div>

    <div class="login-card">
        <div class="color-strip"></div>

        @if($errors->has('login'))
        <div class="alert alert-danger rounded-3 py-2 mb-3">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first('login') }}
        </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Login Sebagai:</label>
                <div class="d-flex gap-2 role-tabs">
                    <label>
                        <input type="radio" name="role" value="admin" class="d-none role-radio" {{ old('role')=='admin' ? 'checked' : '' }}>
                        <div class="nav-link {{ old('role')=='admin' ? 'active' : '' }}">
                            <i class="bi bi-person-gear me-1"></i>Guru / Staff
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="role" value="siswa" class="d-none role-radio" {{ old('role','siswa')=='siswa' ? 'checked' : '' }}>
                        <div class="nav-link {{ old('role','siswa')=='siswa' ? 'active' : '' }}">
                            <i class="bi bi-mortarboard me-1"></i>Siswa
                        </div>
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Username / NIS</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username / NIS / Nama Siswa" autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Masukkan password">
                    <button type="button" class="btn btn-outline-secondary border-2 border-start-0" id="togglePassword" style="border-radius:0 10px 10px 0">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-login text-white w-100 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <div style="background:rgba(244,208,63,.12);border:1px solid rgba(244,208,63,.4);border-radius:10px;padding:.8rem 1rem">
            <div class="fw-bold text-muted small mb-2"><i class="bi bi-info-circle me-1"></i>Akun Demo:</div>
            <div class="akun-grid">
                <div class="akun-item">
                    <span class="role-tag" style="background:#1A5276;color:white">Kepsek</span>
                    <div class="mt-1"><code>kepsek</code> / <code>kepsek123</code></div>
                </div>
                <div class="akun-item">
                    <span class="role-tag" style="background:#117A65;color:white">Guru</span>
                    <div class="mt-1"><code>guru1</code> / <code>guru123</code></div>
                </div>
                <div class="akun-item">
                    <span class="role-tag" style="background:#C0392B;color:white">Sapras</span>
                    <div class="mt-1"><code>sapras</code> / <code>sapras123</code></div>
                </div>
                <div class="akun-item">
                    <span class="role-tag" style="background:#6C3483;color:white">Kesiswaan</span>
                    <div class="mt-1"><code>kesiswaan</code> / <code>kesiswaan123</code></div>
                </div>
                <div class="akun-item">
                    <span class="role-tag" style="background:#B7950B;color:white">Guru BK</span>
                    <div class="mt-1"><code>gurubk</code> / <code>gurubk123</code></div>
                </div>
                <div class="akun-item">
                    <span class="role-tag" style="background:#333;color:white">Siswa</span>
                    <div class="mt-1">NIS: <code>2024001</code> / <code>siswa123</code></div>
                </div>
            </div>
        <hr class="my-3">
        <div class="text-center small text-muted">
            Siswa belum punya akun?
            <a href="/register" class="fw-bold" style="color:#C0392B">Daftar di sini &rarr;</a>
        </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.role-radio').forEach(r => {
        r.parentElement.addEventListener('click', function() {
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.querySelector('.nav-link').classList.add('active');
            r.checked = true;
        });
    });
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const inp = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (inp.type === 'password') { inp.type = 'text'; icon.className = 'bi bi-eye-slash'; }
        else { inp.type = 'password'; icon.className = 'bi bi-eye'; }
    });
</script>
</body>
</html>
