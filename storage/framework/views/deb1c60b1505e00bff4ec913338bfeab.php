<?php $__env->startSection('title','Histori Laporan'); ?>
<?php $__env->startSection('content'); ?>
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
                        <?php ($no=1); ?>
                        <?php $__empty_1 = true; $__currentLoopData = $histori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($no++); ?></td>
                            <td class="small text-muted"><?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d M Y')); ?></td>
                            <td>
                                <?php if($row->jenis=='sarana_prasarana'): ?> <span class="badge badge-sarana">🔧 Sarana</span>
                                <?php else: ?> <span class="badge badge-kesejahteraan">💛 Kesejahteraan</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge <?php echo e($row->jenis=='sarana_prasarana' ? 'badge-sarana' : 'badge-kesejahteraan'); ?>"><?php echo e($row->ket_kategori); ?></span></td>
                            <td class="small"><?php echo e($row->lokasi); ?></td>
                            <td>
                                <?php if($row->foto): ?> <i class="bi bi-image-fill text-success" title="Ada foto"></i>
                                <?php else: ?> <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row->status=='Menunggu'): ?> <span class="badge badge-menunggu">⏳ <?php echo e($row->status); ?></span>
                                <?php elseif($row->status=='Proses'): ?> <span class="badge badge-proses">⚙️ <?php echo e($row->status); ?></span>
                                <?php else: ?> <span class="badge badge-selesai">✅ <?php echo e($row->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <?php if($row->feedback): ?> <span class="text-success"><i class="bi bi-check-circle"></i> Ada</span>
                                <?php else: ?> <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><a href="/siswa/histori/<?php echo e($row->id_pelaporan); ?>" class="btn btn-sm btn-smk-biru"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Belum ada laporan. <a href="/siswa/aspirasi">Buat sekarang!</a></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pengaduan_sarana\resources\views/siswa/histori.blade.php ENDPATH**/ ?>