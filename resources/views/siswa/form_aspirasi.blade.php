@extends('layouts.app')
@section('title','Buat Laporan')
@section('content')
<div class="fade-in">
    <div class="d-flex align-items-center mb-4">
        <a href="/siswa/dashboard" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
        <h4 class="fw-bold mb-0" style="color:var(--smk-biru)"><i class="bi bi-megaphone me-2"></i>Buat Laporan / Aspirasi</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card card-smk">
                <div class="card-header" style="background:linear-gradient(135deg,var(--smk-merah),var(--smk-biru))">
                    <span class="text-white fw-bold"><i class="bi bi-pencil-square me-2"></i>Form Laporan</span>
                </div>
                <div class="card-body p-4">

                    <!-- STEP 1: Pilih Jenis -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">📋 Langkah 1 — Pilih Jenis Laporan</label>
                        <div class="row g-3" id="jenisPicker">
                            <div class="col-md-6">
                                <div class="jenis-card p-3 border-2 border rounded-3 text-center" data-jenis="sarana" style="cursor:pointer;transition:all .25s;">
                                    <div class="fs-2 mb-1">🔧</div>
                                    <div class="fw-bold">Sarana & Prasarana</div>
                                    <div class="text-muted small">Toilet rusak, fasilitas sekolah, dll</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="jenis-card p-3 border-2 border rounded-3 text-center" data-jenis="kesejahteraan" style="cursor:pointer;transition:all .25s;">
                                    <div class="fs-2 mb-1">💛</div>
                                    <div class="fw-bold">Kesejahteraan Siswa</div>
                                    <div class="text-muted small">Bullying, masalah pribadi, saran, dll</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="/siswa/aspirasi" enctype="multipart/form-data" id="formAspirasi">
                        @csrf

                        <!-- Pelapor -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nama Pelapor</label>
                                <input type="text" class="form-control" value="{{ session('siswa_nama') }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Kelas</label>
                                <input type="text" class="form-control" value="{{ session('siswa_kelas') }}" disabled>
                            </div>
                        </div>

                        <!-- STEP 2: Kategori (dinamis berdasarkan jenis) -->
                        <div class="mb-3" id="seksiKategori" style="display:none">
                            <label class="form-label fw-bold">📌 Langkah 2 — Pilih Kategori</label>
                            <div class="row g-2" id="kategoriGrid"></div>
                            <input type="hidden" name="id_kategori" id="selectedKategori" value="{{ old('id_kategori') }}">
                            @error('id_kategori')<div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
                        </div>

                        <!-- Data Kategori JSON -->
                        <script id="dataKategori" type="application/json">
                        {
                            "sarana": {!! json_encode($kategoris['sarana_prasarana']) !!},
                            "kesejahteraan": {!! json_encode($kategoris['kesejahteraan_siswa']) !!}
                        }
                        </script>

                        <!-- STEP 3: Detail -->
                        <div id="seksiDetail" style="display:none">
                            <label class="form-label fw-bold">📝 Langkah 3 — Detail Laporan</label>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Lokasi Kejadian <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                       name="lokasi" value="{{ old('lokasi') }}"
                                       placeholder="Contoh: Toilet lantai 2, Kelas XII RPL 1, Halaman belakang..." maxlength="100">
                                @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text"><span id="lokasiCount">0</span>/100</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Keterangan Lengkap <span class="text-danger">*</span></label>
                                <textarea name="ket" rows="5"
                                    class="form-control @error('ket') is-invalid @enderror"
                                    placeholder="Jelaskan secara detail apa yang terjadi...">{{ old('ket') }}</textarea>
                                @error('ket')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- FOTO UPLOAD -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">
                                    <i class="bi bi-camera me-1"></i>Foto Bukti
                                    <span class="text-muted">(Opsional, maks 5MB)</span>
                                </label>
                                <div class="foto-upload-area border-2 border-dashed rounded-3 p-4 text-center" id="fotoUploadArea"
                                     style="border-color:#dee2e6;cursor:pointer;transition:all .25s;background:#fafafa">
                                    <input type="file" name="foto" id="fotoInput" accept="image/jpg,image/jpeg,image/png,image/webp" class="d-none">
                                    <div id="fotoPlaceholder">
                                        <i class="bi bi-cloud-upload fs-2 text-muted d-block mb-2"></i>
                                        <div class="fw-semibold text-muted">Klik atau drag foto ke sini</div>
                                        <div class="small text-muted">JPG, PNG, WEBP — Maks 5MB</div>
                                    </div>
                                    <div id="fotoPreviewContainer" style="display:none">
                                        <img id="fotoPreviewImg" src="" class="img-fluid rounded-3 mb-2" style="max-height:200px">
                                        <div class="small text-muted" id="fotoNama"></div>
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-1" id="hapusFoto">
                                            <i class="bi bi-x-circle me-1"></i>Hapus Foto
                                        </button>
                                    </div>
                                </div>
                                @error('foto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Anonim -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="anonim" id="anonimCheck" {{ old('anonim') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="anonimCheck">
                                        <i class="bi bi-incognito me-1"></i>
                                        <b>Kirim secara anonim</b> — nama saya tidak ditampilkan ke admin
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-smk-merah flex-fill" id="submitBtn">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Laporan
                                </button>
                                <a href="/siswa/dashboard" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('extra-js')
<script>
const dataKategori = JSON.parse(document.getElementById('dataKategori').textContent);
const icons = {'sarana': ['🚽','🏫','🔬','📚','⚽','🍽️','🅿️','💡'], 'kesejahteraan': ['😢','👊','🧠','📖','🤝','💬']};
let selectedJenis = null;

// Jenis picker
document.querySelectorAll('.jenis-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.jenis-card').forEach(c => { c.style.background=''; c.style.borderColor='#dee2e6'; c.style.transform=''; });
        this.style.background = 'linear-gradient(135deg,rgba(192,57,43,.08),rgba(26,82,118,.08))';
        this.style.borderColor = '#C0392B';
        this.style.transform = 'scale(1.03)';
        selectedJenis = this.dataset.jenis;
        buildKategori(selectedJenis);
        document.getElementById('seksiKategori').style.display = '';
        document.getElementById('seksiDetail').style.display = 'none';
        document.getElementById('selectedKategori').value = '';
    });
});

function buildKategori(jenis) {
    const grid = document.getElementById('kategoriGrid');
    const cats = dataKategori[jenis] || [];
    const ic = icons[jenis] || [];
    grid.innerHTML = '';
    cats.forEach((k, i) => {
        const preselected = '{{ old("id_kategori") }}' == k.id_kategori;
        grid.innerHTML += `
        <div class="col-6 col-md-4">
            <div class="kat-card p-2 text-center border-2 border rounded-3 ${preselected ? 'kat-selected' : ''}"
                 data-id="${k.id_kategori}" style="cursor:pointer;transition:all .2s;${preselected ? 'background:rgba(192,57,43,.08);border-color:#C0392B' : ''}">
                <div class="fs-5">${ic[i % ic.length] || '📋'}</div>
                <div class="small fw-semibold">${k.ket_kategori}</div>
            </div>
        </div>`;
    });
    // Attach events
    document.querySelectorAll('.kat-card').forEach(c => {
        c.addEventListener('click', function() {
            document.querySelectorAll('.kat-card').forEach(x => { x.style.background=''; x.style.borderColor='#dee2e6'; });
            this.style.background = 'rgba(192,57,43,.08)';
            this.style.borderColor = '#C0392B';
            document.getElementById('selectedKategori').value = this.dataset.id;
            document.getElementById('seksiDetail').style.display = '';
            document.getElementById('seksiDetail').scrollIntoView({behavior:'smooth', block:'start'});
        });
    });
}

// Init if validation error (old values)
@if(old('id_kategori'))
document.addEventListener('DOMContentLoaded', () => {
    // Try to find which jenis the old category belongs to
    ['sarana','kesejahteraan'].forEach(j => {
        const found = dataKategori[j]?.find(k => k.id_kategori == '{{ old("id_kategori") }}');
        if (found) {
            const card = document.querySelector(`.jenis-card[data-jenis="${j}"]`);
            if (card) card.click();
            setTimeout(() => {
                const katCard = document.querySelector(`.kat-card[data-id="{{ old('id_kategori') }}"]`);
                if (katCard) { katCard.click(); }
            }, 100);
        }
    });
});
@endif

// Lokasi counter
document.querySelector('[name="lokasi"]')?.addEventListener('input', function() {
    document.getElementById('lokasiCount').textContent = this.value.length;
});

// Foto upload
const fotoInput = document.getElementById('fotoInput');
const fotoArea  = document.getElementById('fotoUploadArea');

fotoArea.addEventListener('click', () => fotoInput.click());
fotoArea.addEventListener('dragover', e => { e.preventDefault(); fotoArea.style.background='rgba(46,134,193,.08)'; });
fotoArea.addEventListener('dragleave', () => fotoArea.style.background = '#fafafa');
fotoArea.addEventListener('drop', e => { e.preventDefault(); fotoArea.style.background='#fafafa'; if(e.dataTransfer.files[0]) handleFoto(e.dataTransfer.files[0]); });
fotoInput.addEventListener('change', function() { if(this.files[0]) handleFoto(this.files[0]); });

function handleFoto(file) {
    if (file.size > 5 * 1024 * 1024) { alert('Ukuran foto maks 5MB!'); return; }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('fotoPreviewImg').src = e.target.result;
        document.getElementById('fotoNama').textContent = file.name + ' (' + (file.size/1024).toFixed(0) + ' KB)';
        document.getElementById('fotoPlaceholder').style.display = 'none';
        document.getElementById('fotoPreviewContainer').style.display = '';
        fotoArea.style.background = 'rgba(39,174,96,.06)';
        fotoArea.style.borderColor = '#27AE60';
    };
    reader.readAsDataURL(file);
    const dt = new DataTransfer();
    dt.items.add(file);
    fotoInput.files = dt.files;
}

document.getElementById('hapusFoto')?.addEventListener('click', function(e) {
    e.stopPropagation();
    fotoInput.value = '';
    document.getElementById('fotoPlaceholder').style.display = '';
    document.getElementById('fotoPreviewContainer').style.display = 'none';
    fotoArea.style.background = '#fafafa';
    fotoArea.style.borderColor = '#dee2e6';
});

document.getElementById('formAspirasi')?.addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
    btn.disabled = true;
});
</script>
@endsection
@endsection
