<?php $__env->startSection('title','Dashboard Siswa'); ?>
<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--smk-biru)"><i class="bi bi-house me-2"></i>Dashboard</h4>
            <p class="text-muted mb-0 small"><?php echo e(session('siswa_nama')); ?> — <?php echo e(session('siswa_kelas')); ?></p>
        </div>
        <a href="/siswa/aspirasi" class="btn btn-smk-merah ms-auto"><i class="bi bi-plus-circle me-1"></i>Buat Laporan</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="stat-card biru"><div class="fw-bold fs-1"><?php echo e($stats['total']); ?></div><div>Total Laporan</div><i class="bi bi-megaphone stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card kuning"><div class="fw-bold fs-1"><?php echo e($stats['menunggu']); ?></div><div>Menunggu</div><i class="bi bi-hourglass stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card merah"><div class="fw-bold fs-1"><?php echo e($stats['proses']); ?></div><div>Dalam Proses</div><i class="bi bi-gear stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card hijau"><div class="fw-bold fs-1"><?php echo e($stats['selesai']); ?></div><div>Selesai</div><i class="bi bi-check-circle stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card tosca"><div class="fw-bold fs-1"><?php echo e($stats['sarana']); ?></div><div>Lap. Sarana</div><i class="bi bi-tools stat-icon"></i></div></div>
        <div class="col-6 col-md-3"><div class="stat-card ungu"><div class="fw-bold fs-1"><?php echo e($stats['kesejahteraan']); ?></div><div>Lap. Kesejahteraan</div><i class="bi bi-heart-pulse stat-icon"></i></div></div>
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
                        <?php $__empty_1 = true; $__currentLoopData = $histori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="small text-muted"><?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d M Y')); ?></td>
                            <td>
                                <?php if($row->jenis=='sarana_prasarana'): ?> <span class="badge badge-sarana">🔧 Sarana</span>
                                <?php else: ?> <span class="badge badge-kesejahteraan">💛 Kesejahteraan</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($row->ket_kategori); ?></td>
                            <td><?php echo e($row->lokasi); ?></td>
                            <td>
                                <?php if($row->status=='Menunggu'): ?> <span class="badge badge-menunggu">⏳ <?php echo e($row->status); ?></span>
                                <?php elseif($row->status=='Proses'): ?> <span class="badge badge-proses">⚙️ <?php echo e($row->status); ?></span>
                                <?php else: ?> <span class="badge badge-selesai">✅ <?php echo e($row->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><a href="/siswa/histori/<?php echo e($row->id_pelaporan); ?>" class="btn btn-sm btn-smk-biru"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada laporan. <a href="/siswa/aspirasi">Buat sekarang!</a></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pengaduan_sarana\resources\views/siswa/dashboard.blade.php ENDPATH**/ ?>