@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="fade-in">
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--smk-biru)"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
            <p class="text-muted mb-0 small">{{ session('admin_role_label') }} — {{ session('admin_nama') }}</p>
        </div>
    </div>

    <!-- Akses Info -->
    <div class="alert mb-4 d-flex gap-2 align-items-center" style="background:rgba(26,82,118,.08);border-left:4px solid var(--smk-biru);border-radius:10px">
        <i class="bi bi-shield-check fs-5" style="color:var(--smk-biru)"></i>
        <div class="small">
            <b>Akses Anda:</b>
            @foreach($allowedJenis as $j)
                <span class="badge {{ $j=='sarana_prasarana' ? 'badge-sarana' : 'badge-kesejahteraan' }} ms-1">
                    {{ $j=='sarana_prasarana' ? '🔧 Sarana & Prasarana' : '💛 Kesejahteraan Siswa' }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="stat-card biru"><div class="fw-bold fs-1">{{ $stats['total'] }}</div><div>Total Laporan</div><i class="bi bi-inbox stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card kuning"><div class="fw-bold fs-1">{{ $stats['menunggu'] }}</div><div>Menunggu</div><i class="bi bi-hourglass-split stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card merah"><div class="fw-bold fs-1">{{ $stats['proses'] }}</div><div>Dalam Proses</div><i class="bi bi-gear-wide-connected stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card hijau"><div class="fw-bold fs-1">{{ $stats['selesai'] }}</div><div>Selesai</div><i class="bi bi-check-circle stat-icon"></i></div></div>
    </div>

    <!-- Latest -->
    <div class="card card-smk">
        <div class="card-header" style="background:linear-gradient(90deg,var(--smk-biru),var(--smk-merah))">
            <span class="text-white fw-bold"><i class="bi bi-clock-history me-2"></i>Laporan Terbaru</span>
            <a href="/admin/aspirasi" class="btn btn-sm btn-smk-kuning float-end">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-smk table-hover mb-0">
                    <thead><tr><th>No</th><th>Jenis</th><th>Siswa</th><th>Kategori</th><th>Lokasi</th><th>Tgl</th><th>Foto</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($terbaru as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                @if($row->jenis=='sarana_prasarana')
                                    <span class="badge badge-sarana">🔧 Sarana</span>
                                @else
                                    <span class="badge badge-kesejahteraan">💛 Kesejahteraan</span>
                                @endif
                            </td>
                            <td class="fw-semibold">
                                @if($row->anonim) <span class="text-muted fst-italic">Anonim</span>
                                @else {{ $row->nama_siswa }} <span class="badge bg-secondary small">{{ $row->kelas }}</span>
                                @endif
                            </td>
                            <td>{{ $row->ket_kategori }}</td>
                            <td>{{ $row->lokasi }}</td>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                            <td>
                                @if($row->foto)
                                    <img src="{{ asset('storage/'.$row->foto) }}" class="foto-thumb"
                                         onclick="showFoto('{{ asset('storage/'.$row->foto) }}')" title="Klik untuk lihat foto">
                                @else <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($row->status=='Menunggu') <span class="badge badge-menunggu">⏳ {{ $row->status }}</span>
                                @elseif($row->status=='Proses') <span class="badge badge-proses">⚙️ {{ $row->status }}</span>
                                @else <span class="badge badge-selesai">✅ {{ $row->status }}</span>
                                @endif
                            </td>
                            <td><a href="/admin/aspirasi/feedback/{{ $row->id_pelaporan }}" class="btn btn-sm btn-smk-biru"><i class="bi bi-chat-dots"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada laporan masuk</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal foto -->
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
function showFoto(url) {
    document.getElementById('fotoModalImg').src = url;
    new bootstrap.Modal(document.getElementById('fotoModal')).show();
}
</script>
@endsection
@endsection
