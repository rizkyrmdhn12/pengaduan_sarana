@extends('layouts.app')
@section('title','Dashboard Siswa')
@section('content')
<div class="fade-in">
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--smk-biru)"><i class="bi bi-house me-2"></i>Dashboard</h4>
            <p class="text-muted mb-0 small">{{ session('siswa_nama') }} — {{ session('siswa_kelas') }}</p>
        </div>
        <a href="/siswa/aspirasi" class="btn btn-smk-merah ms-auto"><i class="bi bi-plus-circle me-1"></i>Buat Laporan</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="stat-card biru"><div class="fw-bold fs-1">{{ $stats['total'] }}</div><div>Total Laporan</div><i class="bi bi-megaphone stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card kuning"><div class="fw-bold fs-1">{{ $stats['menunggu'] }}</div><div>Menunggu</div><i class="bi bi-hourglass stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card merah"><div class="fw-bold fs-1">{{ $stats['proses'] }}</div><div>Dalam Proses</div><i class="bi bi-gear stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card hijau"><div class="fw-bold fs-1">{{ $stats['selesai'] }}</div><div>Selesai</div><i class="bi bi-check-circle stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card tosca"><div class="fw-bold fs-1">{{ $stats['sarana'] }}</div><div>Lap. Sarana</div><i class="bi bi-tools stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card ungu"><div class="fw-bold fs-1">{{ $stats['kesejahteraan'] }}</div><div>Lap. Kesejahteraan</div><i class="bi bi-heart-pulse stat-icon"></i></div></div>
    </div>

    <div class="card card-smk">
        <div class="card-header" style="background:linear-gradient(90deg,var(--smk-biru),var(--smk-merah))">
            <span class="text-white fw-bold"><i class="bi bi-clock-history me-2"></i>Laporan Terbaru Saya</span>
            <a href="/siswa/histori" class="btn btn-sm btn-smk-kuning float-end">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-smk table-hover mb-0">
                    <thead><tr><th>Tanggal</th><th>Jenis</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($histori as $row)
                        <tr>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                            <td>
                                @if($row->jenis=='sarana_prasarana') <span class="badge badge-sarana">🔧 Sarana</span>
                                @else <span class="badge badge-kesejahteraan">💛 Kesejahteraan</span>
                                @endif
                            </td>
                            <td>{{ $row->ket_kategori }}</td>
                            <td>{{ $row->lokasi }}</td>
                            <td>
                                @if($row->status=='Menunggu') <span class="badge badge-menunggu">⏳ {{ $row->status }}</span>
                                @elseif($row->status=='Proses') <span class="badge badge-proses">⚙️ {{ $row->status }}</span>
                                @else <span class="badge badge-selesai">✅ {{ $row->status }}</span>
                                @endif
                            </td>
                            <td><a href="/siswa/histori/{{ $row->id_pelaporan }}" class="btn btn-sm btn-smk-biru"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada laporan. <a href="/siswa/aspirasi">Buat sekarang!</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
