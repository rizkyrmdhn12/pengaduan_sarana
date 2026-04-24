<?php $__env->startSection('title','Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--smk-biru)"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
            <p class="text-muted mb-0 small"><?php echo e(session('admin_role_label')); ?> — <?php echo e(session('admin_nama')); ?></p>
        </div>
    </div>

    <!-- Akses Info -->
    <div class="alert mb-4 d-flex gap-2 align-items-center" style="background:rgba(26,82,118,.08);border-left:4px solid var(--smk-biru);border-radius:10px">
        <i class="bi bi-shield-check fs-5" style="color:var(--smk-biru)"></i>
        <div class="small">
            <b>Akses Anda:</b>
            <?php $__currentLoopData = $allowedJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge <?php echo e($j=='sarana_prasarana' ? 'badge-sarana' : 'badge-kesejahteraan'); ?> ms-1">
                    <?php echo e($j=='sarana_prasarana' ? '🔧 Sarana & Prasarana' : '💛 Kesejahteraan Siswa'); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="stat-card biru"><div class="fw-bold fs-1"><?php echo e($stats['total']); ?></div><div>Total Laporan</div><i class="bi bi-inbox stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card kuning"><div class="fw-bold fs-1"><?php echo e($stats['menunggu']); ?></div><div>Menunggu</div><i class="bi bi-hourglass-split stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card merah"><div class="fw-bold fs-1"><?php echo e($stats['proses']); ?></div><div>Dalam Proses</div><i class="bi bi-gear-wide-connected stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card hijau"><div class="fw-bold fs-1"><?php echo e($stats['selesai']); ?></div><div>Selesai</div><i class="bi bi-check-circle stat-icon"></i></div></div>
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
                        <?php $__empty_1 = true; $__currentLoopData = $terbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($i+1); ?></td>
                            <td>
                                <?php if($row->jenis=='sarana_prasarana'): ?>
                                    <span class="badge badge-sarana">🔧 Sarana</span>
                                <?php else: ?>
                                    <span class="badge badge-kesejahteraan">💛 Kesejahteraan</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-semibold">
                                <?php if($row->anonim): ?> <span class="text-muted fst-italic">Anonim</span>
                                <?php else: ?> <?php echo e($row->nama_siswa); ?> <span class="badge bg-secondary small"><?php echo e($row->kelas); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($row->ket_kategori); ?></td>
                            <td><?php echo e($row->lokasi); ?></td>
                            <td class="small text-muted"><?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d/m/Y')); ?></td>
                            <td>
                                <?php if($row->foto): ?>
                                    <img src="<?php echo e(asset('storage/'.$row->foto)); ?>" class="foto-thumb"
                                         onclick="showFoto('<?php echo e(asset('storage/'.$row->foto)); ?>')" title="Klik untuk lihat foto">
                                <?php else: ?> <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row->status=='Menunggu'): ?> <span class="badge badge-menunggu">⏳ <?php echo e($row->status); ?></span>
                                <?php elseif($row->status=='Proses'): ?> <span class="badge badge-proses">⚙️ <?php echo e($row->status); ?></span>
                                <?php else: ?> <span class="badge badge-selesai">✅ <?php echo e($row->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><a href="/admin/aspirasi/feedback/<?php echo e($row->id_pelaporan); ?>" class="btn btn-sm btn-smk-biru"><i class="bi bi-chat-dots"></i></a></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada laporan masuk</td></tr>
                        <?php endif; ?>
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

<?php $__env->startSection('extra-js'); ?>
<script>
function showFoto(url) {
    document.getElementById('fotoModalImg').src = url;
    new bootstrap.Modal(document.getElementById('fotoModal')).show();
}
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pengaduan_sarana\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>