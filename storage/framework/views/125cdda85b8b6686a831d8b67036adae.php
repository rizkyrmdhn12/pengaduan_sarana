<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - SMK Sangkuriang 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:linear-gradient(135deg,#C0392B 0%,#922B21 30%,#1A5276 70%,#154360 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; padding:1rem; }
        .reg-wrapper { width:100%; max-width:480px; }
        .school-logo { width:80px; height:80px; background:linear-gradient(135deg,#F4D03F,#D4AC0D); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; font-size:2.4rem; box-shadow:0 8px 25px rgba(0,0,0,.35); }
        .reg-card { background:white; border-radius:22px; padding:2rem; box-shadow:0 20px 60px rgba(0,0,0,.3); }
        .color-strip { height:6px; background:linear-gradient(90deg,#C0392B,#F4D03F,#1A5276); border-radius:22px 22px 0 0; margin:-2rem -2rem 1.5rem; }
        .form-control { border-radius:10px; border:2px solid #eee; padding:.65rem 1rem; transition:all .2s; }
        .form-control:focus { border-color:#1A5276; box-shadow:0 0 0 3px rgba(26,82,118,.15); }
        .input-group-text { border-radius:10px 0 0 10px; background:#f8f9fa; border:2px solid #eee; border-right:none; color:#1A5276; }
        .input-group .form-control { border-radius:0 10px 10px 0; border-left:none; }
        .btn-register { background:linear-gradient(135deg,#C0392B,#1A5276); border:none; border-radius:10px; padding:.75rem; font-weight:700; transition:all .3s; }
        .btn-register:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,0,0,.3); color:white; }
        .kelas-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:.4rem; }
        .kelas-btn { padding:.4rem; text-align:center; border:2px solid #eee; border-radius:8px; cursor:pointer; font-size:.82rem; font-weight:600; transition:all .2s; background:white; }
        .kelas-btn:hover { border-color:#1A5276; background:#EBF5FB; }
        .kelas-btn.selected { border-color:#C0392B; background:rgba(192,57,43,.08); color:#C0392B; }
        .strength-bar { height:6px; border-radius:3px; transition:all .3s; }
        .invalid-feedback { font-size:.8rem; }
    </style>
</head>
<body>
<div class="reg-wrapper">
    <div class="text-center text-white mb-3">
        <div class="school-logo">🏫</div>
        <h5 class="fw-bold mb-0">SMK Sangkuriang 1</h5>
        <p class="opacity-75 small">Daftarkan akun siswa baru</p>
    </div>

    <div class="reg-card">
        <div class="color-strip"></div>

        <form method="POST" action="/register">
            <?php echo csrf_field(); ?>

            <!-- NIS -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">NIS (Nomor Induk Siswa) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-123"></i></span>
                    <input type="number" name="nis"
                           class="form-control <?php $__errorArgs = ['nis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('nis')); ?>"
                           placeholder="Masukkan NIS kamu">
                </div>
                <?php $__errorArgs = ['nis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Nama -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="nama_siswa"
                           class="form-control <?php $__errorArgs = ['nama_siswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('nama_siswa')); ?>"
                           placeholder="Nama lengkap sesuai data sekolah">
                </div>
                <?php $__errorArgs = ['nama_siswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Kelas -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Kelas <span class="text-danger">*</span></label>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="bi bi-mortarboard-fill"></i></span>
                    <input type="text" name="kelas" id="kelasInput"
                           class="form-control <?php $__errorArgs = ['kelas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('kelas')); ?>"
                           placeholder="Contoh: XII RPL 1">
                </div>
                <!-- Quick select kelas -->
                <div class="kelas-grid">
                    <?php $__currentLoopData = ['X RPL 1','X RPL 2','XI RPL 1','XI RPL 2','XII RPL 1','XII RPL 2']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="kelas-btn <?php echo e(old('kelas')==$k ? 'selected' : ''); ?>" onclick="pilihKelas('<?php echo e($k); ?>')"><?php echo e($k); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['kelas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="passInput"
                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Minimal 6 karakter"
                           oninput="checkStrength(this.value)">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passInput','eyePass')" style="border-radius:0 10px 10px 0;border:2px solid #eee;border-left:none">
                        <i class="bi bi-eye" id="eyePass"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <!-- Password strength -->
                <div class="mt-2">
                    <div class="strength-bar bg-secondary w-0" id="strengthBar" style="width:0%"></div>
                    <div class="small text-muted mt-1" id="strengthText"></div>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="form-label fw-semibold small">Konfirmasi Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" id="passConfirm"
                           class="form-control"
                           placeholder="Ulangi password"
                           oninput="checkMatch()">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passConfirm','eyeConfirm')" style="border-radius:0 10px 10px 0;border:2px solid #eee;border-left:none">
                        <i class="bi bi-eye" id="eyeConfirm"></i>
                    </button>
                </div>
                <div class="small mt-1" id="matchText"></div>
            </div>

            <button type="submit" class="btn btn-register text-white w-100 mb-3">
                <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
            </button>
        </form>

        <div class="text-center small text-muted">
            Sudah punya akun?
            <a href="/login" class="fw-bold" style="color:var(--smk-merah,#C0392B)">Login di sini</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function pilihKelas(kelas) {
    document.getElementById('kelasInput').value = kelas;
    document.querySelectorAll('.kelas-btn').forEach(b => b.classList.remove('selected'));
    event.target.classList.add('selected');
}
function togglePass(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'bi bi-eye-slash'; }
    else { inp.type = 'password'; icon.className = 'bi bi-eye'; }
}
function checkStrength(val) {
    const bar = document.getElementById('strengthBar');
    const txt = document.getElementById('strengthText');
    let strength = 0;
    if (val.length >= 6)  strength++;
    if (val.length >= 10) strength++;
    if (/[A-Z]/.test(val)) strength++;
    if (/[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;
    const levels = [
        {w:'0%',   c:'bg-secondary', t:''},
        {w:'25%',  c:'bg-danger',    t:'Terlalu pendek'},
        {w:'50%',  c:'bg-warning',   t:'Lemah'},
        {w:'75%',  c:'bg-info',      t:'Cukup kuat'},
        {w:'90%',  c:'bg-primary',   t:'Kuat'},
        {w:'100%', c:'bg-success',   t:'Sangat kuat ✅'},
    ];
    const l = levels[Math.min(strength, 5)];
    bar.style.width = l.w;
    bar.className = 'strength-bar ' + l.c;
    txt.textContent = l.t;
    txt.className = 'small mt-1 ' + (strength < 2 ? 'text-danger' : strength < 4 ? 'text-warning' : 'text-success');
}
function checkMatch() {
    const p1 = document.getElementById('passInput').value;
    const p2 = document.getElementById('passConfirm').value;
    const el = document.getElementById('matchText');
    if (!p2) { el.textContent=''; return; }
    if (p1===p2) { el.textContent='✅ Password cocok'; el.className='small mt-1 text-success'; }
    else { el.textContent='❌ Password tidak cocok'; el.className='small mt-1 text-danger'; }
}
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\pengaduan_sarana\resources\views/auth/register.blade.php ENDPATH**/ ?>