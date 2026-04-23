@extends('layouts.app')
@section('title','Umpan Balik')
@section('content')
<div class="fade-in">
    <div class="d-flex align-items-center mb-4">
        <a href="/admin/aspirasi" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
        <h4 class="fw-bold mb-0" style="color:var(--smk-biru)"><i class="bi bi-chat-dots me-2"></i>Umpan Balik Laporan</h4>
    </div>

    <div class="row g-4">
        <!-- Detail -->
        <div class="col-md-5">
            <div class="card card-smk h-100">
                <div class="card-header" style="background:linear-gradient(135deg,var(--smk-biru),#154360)">
                    <span class="text-white fw-bold"><i class="bi bi-file-text me-2"></i>Detail Laporan</span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless small">
                        <tr>
                            <td class="text-muted fw-semibold" width="38%">Jenis</td>
                            <td>
                                @if($aspirasi->jenis=='sarana_prasarana')
                                    <span class="badge badge-sarana">🔧 Sarana Prasarana</span>
                                @else
                                    <span class="badge badge-kesejahteraan">💛 Kesejahteraan Siswa</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Pelapor</td>
                            <td class="fw-bold">
                                @if($aspirasi->anonim) <em class="text-muted">Anonim</em>
                                @else {{ $aspirasi->nama_siswa }} ({{ $aspirasi->kelas }})
                                @endif
                            </td>
                        </tr>
                        @if(!$aspirasi->anonim)
                        <tr><td class="text-muted fw-semibold">NIS</td><td>{{ $aspirasi->nis }}</td></tr>
                        @endif
                        <tr><td class="text-muted fw-semibold">Kategori</td><td><span class="badge {{ $aspirasi->jenis=='sarana_prasarana' ? 'badge-sarana' : 'badge-kesejahteraan' }}">{{ $aspirasi->ket_kategori }}</span></td></tr>
                        <tr><td class="text-muted fw-semibold">Lokasi</td><td>{{ $aspirasi->lokasi }}</td></tr>
                        <tr><td class="text-muted fw-semibold">Tanggal</td><td>{{ \Carbon\Carbon::parse($aspirasi->created_at)->format('d M Y, H:i') }}</td></tr>
                        <tr>
                            <td class="text-muted fw-semibold">Status</td>
                            <td>
                                @if($aspirasi->status=='Menunggu') <span class="badge badge-menunggu">⏳ {{ $aspirasi->status }}</span>
                                @elseif($aspirasi->status=='Proses') <span class="badge badge-proses">⚙️ {{ $aspirasi->status }}</span>
                                @else <span class="badge badge-selesai">✅ {{ $aspirasi->status }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="mb-3">
                        <p class="text-muted fw-semibold small mb-1">Keterangan:</p>
                        <div class="p-3 rounded-3 small" style="background:#f8f9fa;border-left:4px solid var(--smk-biru)">{{ $aspirasi->ket }}</div>
                    </div>

                    @if($aspirasi->foto)
                    <div>
                        <p class="text-muted fw-semibold small mb-1"><i class="bi bi-image me-1"></i>Foto Bukti:</p>
                        <img src="{{ asset('storage/'.$aspirasi->foto) }}" class="foto-preview w-100"
                             onclick="showFoto('{{ asset('storage/'.$aspirasi->foto) }}')"
                             style="cursor:zoom-in" title="Klik untuk perbesar">
                    </div>
                    @else
                    <div class="text-muted small text-center py-2 bg-light rounded-3">
                        <i class="bi bi-image opacity-25 fs-3 d-block"></i>Tidak ada foto bukti
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Feedback -->
        <div class="col-md-7">
            <div class="card card-smk">
                <div class="card-header" style="background:linear-gradient(135deg,var(--smk-merah),var(--smk-merah-gelap))">
                    <span class="text-white fw-bold"><i class="bi bi-chat-square-dots me-2"></i>Form Umpan Balik</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/aspirasi/feedback/{{ $aspirasi->id_pelaporan }}">
                        @csrf @method('PUT')
                        <div class="mb-4">
                            <label class="form-label fw-bold">Status Penyelesaian</label>
                            <div class="row g-2">
                                @foreach($statusList as $st)
                                <div class="col-4">
                                    <label class="w-100">
                                        <input type="radio" name="status" value="{{ $st }}" class="d-none status-radio" {{ old('status',$aspirasi->status)==$st ? 'checked' : '' }}>
                                        <div class="status-btn p-2 text-center rounded-3 border-2 border fw-bold"
                                            style="cursor:pointer;transition:all .2s;
                                            {{ $st=='Menunggu' ? 'border-color:#D4AC0D' : ($st=='Proses' ? 'border-color:#2E86C1' : 'border-color:#27AE60') }}">
                                            {{ $st=='Menunggu' ? '⏳' : ($st=='Proses' ? '⚙️' : '✅') }} {{ $st }}
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Feedback / Catatan Tindak Lanjut</label>
                            <textarea name="feedback" rows="6"
                                class="form-control @error('feedback') is-invalid @enderror"
                                placeholder="Jelaskan tindakan yang sudah/akan diambil...">{{ old('feedback',$aspirasi->feedback) }}</textarea>
                            @error('feedback')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-smk-merah flex-fill">
                                <i class="bi bi-send me-2"></i>Kirim Umpan Balik
                            </button>
                            <a href="/admin/aspirasi" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fotoModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 bg-transparent shadow-none">
        <div class="modal-body text-center p-0">
            <img id="fotoModalImg" src="" class="img-fluid rounded-3 shadow" style="max-height:80vh">
            <button type="button" class="btn btn-light btn-sm mt-2" data-bs-dismiss="modal">✕ Tutup</button>
        </div>
    </div>
</div></div>

@section('extra-js')
<script>
function showFoto(url) { document.getElementById('fotoModalImg').src=url; new bootstrap.Modal(document.getElementById('fotoModal')).show(); }
document.querySelectorAll('.status-radio').forEach(r => {
    const initActive = () => {
        document.querySelectorAll('.status-btn').forEach(b => { b.style.background=''; b.style.transform=''; });
        document.querySelectorAll('.status-radio:checked').forEach(cr => {
            const b = cr.nextElementSibling;
            b.style.background = cr.value==='Menunggu' ? '#FEF9C3' : (cr.value==='Proses' ? '#EBF5FB' : '#E9F7EF');
            b.style.transform = 'scale(1.04)';
        });
    };
    r.addEventListener('change', initActive);
    if (r.checked) setTimeout(initActive, 50);
});
</script>
@endsection
@endsection
