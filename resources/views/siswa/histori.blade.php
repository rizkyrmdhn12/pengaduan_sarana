@extends('layouts.app')
@section('title','Histori Laporan')
@section('content')
<div class="fade-in">
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color:var(--smk-biru)"><i class="bi bi-clock-history me-2"></i>Histori Laporan Saya</h4>
        <a href="/siswa/aspirasi" class="btn btn-smk-merah ms-auto"><i class="bi bi-plus me-1"></i>Buat Baru</a>
    </div>
    <div class="card card-smk">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-smk table-hover mb-0">
                    <thead><tr><th>No</th><th>Tanggal</th><th>Jenis</th><th>Kategori</th><th>Lokasi</th><th>Foto</th><th>Status</th><th>Feedback</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @php($no=1)
                        @forelse($histori as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                            <td>
                                @if($row->jenis=='sarana_prasarana') <span class="badge badge-sarana">🔧 Sarana</span>
                                @else <span class="badge badge-kesejahteraan">💛 Kesejahteraan</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $row->jenis=='sarana_prasarana' ? 'badge-sarana' : 'badge-kesejahteraan' }}">{{ $row->ket_kategori }}</span></td>
                            <td class="small">{{ $row->lokasi }}</td>
                            <td>
                                @if($row->foto) <i class="bi bi-image-fill text-success" title="Ada foto"></i>
                                @else <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($row->status=='Menunggu') <span class="badge badge-menunggu">⏳ {{ $row->status }}</span>
                                @elseif($row->status=='Proses') <span class="badge badge-proses">⚙️ {{ $row->status }}</span>
                                @else <span class="badge badge-selesai">✅ {{ $row->status }}</span>
                                @endif
                            </td>
                            <td class="small">
                                @if($row->feedback) <span class="text-success"><i class="bi bi-check-circle"></i> Ada</span>
                                @else <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><a href="/siswa/histori/{{ $row->id_pelaporan }}" class="btn btn-sm btn-smk-biru"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Belum ada laporan. <a href="/siswa/aspirasi">Buat sekarang!</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
