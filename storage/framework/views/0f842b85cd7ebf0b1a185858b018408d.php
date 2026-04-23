<?php $__env->startSection('title','Detail Laporan'); ?>
<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <div class="d-flex align-items-center mb-4">
        <a href="/siswa/histori" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
        <h4 class="fw-bold mb-0" style="color:var(--smk-biru)"><i class="bi bi-file-text me-2"></i>Detail & Progres Laporan</h4>
    </div>

    <!-- Progress Tracker -->
    <div class="card card-smk mb-4">
        <div class="card-body py-4">
            <h6 class="fw-bold text-muted text-center mb-4 text-uppercase small">Progres Penanganan</h6>
            <div class="progress-tracker">
                <div class="tracker-step done">
                    <div class="tracker-icon">📩</div>
                    <div class="small fw-semibold">Diterima</div>
                    <div class="text-muted" style="font-size:.72rem">Laporan masuk</div>
                </div>
                <div class="tracker-step <?php echo e($aspirasi->status=='Menunggu' ? 'current' : 'done'); ?>">
                    <div class="tracker-icon">⏳</div>
                    <div class="small fw-semibold">Menunggu</div>
                    <div class="text-muted" style="font-size:.72rem">Antri ditinjau</div>
                </div>
                <div class="tracker-step <?php echo e($aspirasi->status=='Proses' ? 'current' : ($aspirasi->status=='Selesai' ? 'done' : '')); ?>">
                    <div class="tracker-icon">⚙️</div>
                    <div class="small fw-semibold">Proses</div>
                    <div class="text-muted" style="font-size:.72rem">Sedang ditangani</div>
                </div>
                <div class="tracker-step <?php echo e($aspirasi->status=='Selesai' ? 'done' : ''); ?>">
                    <div class="tracker-icon">✅</div>
                    <div class="small fw-semibold">Selesai</div>
                    <div class="text-muted" style="font-size:.72rem">Sudah ditangani</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card card-smk h-100">
                <div class="card-header" style="background:linear-gradient(135deg,var(--smk-biru),#154360)">
                    <span class="text-white fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Detail Laporan</span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless small">
                        <tr>
                            <td class="text-muted fw-semibold" width="35%">Jenis</td>
                            <td>
                                <?php if($aspirasi->jenis=='sarana_prasarana'): ?>
                                    <span class="badge badge-sarana">🔧 Sarana</span>
                                <?php else: ?>
                                    <span class="badge badge-kesejahteraan">💛 Kesejahteraan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Kategori</td>
                            <td><span class="badge <?php echo e($aspirasi->jenis=='sarana_prasarana' ? 'badge-sarana' : 'badge-kesejahteraan'); ?>"><?php echo e($aspirasi->ket_kategori); ?></span></td>
                        </tr>
                        <tr><td class="text-muted fw-semibold">Lokasi</td><td class="fw-semibold"><?php echo e($aspirasi->lokasi); ?></td></tr>
                        <tr><td class="text-muted fw-semibold">Tanggal</td><td><?php echo e(\Carbon\Carbon::parse($aspirasi->created_at)->format('d M Y, H:i')); ?></td></tr>
                        <tr>
                            <td class="text-muted fw-semibold">Status</td>
                            <td>
                                <?php if($aspirasi->status=='Menunggu'): ?> <span class="badge badge-menunggu fs-6">⏳ <?php echo e($aspirasi->status); ?></span>
                                <?php elseif($aspirasi->status=='Proses'): ?> <span class="badge badge-proses fs-6">⚙️ <?php echo e($aspirasi->status); ?></span>
                                <?php else: ?> <span class="badge badge-selesai fs-6">✅ <?php echo e($aspirasi->status); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if($aspirasi->anonim): ?>
                        <tr><td class="text-muted fw-semibold">Mode</td><td><span class="badge bg-secondary"><i class="bi bi-incognito me-1"></i>Anonim</span></td></tr>
                        <?php endif; ?>
                    </table>

                    <p class="text-muted fw-semibold small mb-1">Keterangan:</p>
                    <div class="p-3 rounded-3 small mb-3" style="background:#f8f9fa;border-left:4px solid var(--smk-biru)"><?php echo e($aspirasi->ket); ?></div>

                    <?php if($aspirasi->foto): ?>
                    <p class="text-muted fw-semibold small mb-1"><i class="bi bi-image me-1"></i>Foto Bukti:</p>
                    <?php $fotoUrl = \Illuminate\Support\Facades\Storage::url($aspirasi->foto); ?>
                    <img src="<?php echo e($fotoUrl); ?>" class="foto-preview w-100"
                         onclick="showFoto('<?php echo e($fotoUrl); ?>')"
                         style="cursor:zoom-in; max-height:250px; object-fit:cover;">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-smk h-100">
                <div class="card-header" style="background:linear-gradient(135deg,var(--smk-merah),var(--smk-merah-gelap))">
                    <span class="text-white fw-bold"><i class="bi bi-chat-dots me-2"></i>Umpan Balik</span>
                </div>
                <div class="card-body d-flex flex-column">
                    <?php if($aspirasi->feedback): ?>
                    <div class="p-3 rounded-3 flex-fill" style="background:rgba(39,174,96,.08);border:2px solid rgba(39,174,96,.3)">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-success me-2">✅ Feedback Diterima</span>
                            <span class="text-muted small"><?php echo e(\Carbon\Carbon::parse($aspirasi->status_updated_at)->format('d M Y')); ?></span>
                        </div>
                        <p class="mb-0"><?php echo e($aspirasi->feedback); ?></p>
                    </div>
                    <?php else: ?>
                    <div class="text-center text-muted flex-fill d-flex flex-column align-items-center justify-content-center py-4">
                        <i class="bi bi-hourglass-split fs-1 mb-3 opacity-25"></i>
                        <p class="mb-1 fw-semibold">Belum ada umpan balik.</p>
                        <p class="small">Sabar ya, laporan kamu sedang ditinjau! 🙏</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="modal-body text-center p-2">
                <img id="fotoModalImg" src="" class="img-fluid rounded-3 shadow" style="max-height:85vh">
                <div class="mt-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('extra-js'); ?>
<script>
function showFoto(url) {
    document.getElementById('fotoModalImg').src = url;
    new bootstrap.Modal(document.getElementById('fotoModal')).show();
}
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pengaduan_sarana\resources\views/siswa/detail_aspirasi.blade.php ENDPATH**/ ?>