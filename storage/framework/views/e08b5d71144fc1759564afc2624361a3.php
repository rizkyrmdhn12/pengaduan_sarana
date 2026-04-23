<?php $__env->startSection('title','Daftar Laporan'); ?>
<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <h4 class="fw-bold mb-4" style="color:var(--smk-biru)"><i class="bi bi-inbox me-2"></i>Semua Laporan</h4>

    <div class="card card-smk mb-4">
        <div class="card-header" style="background:linear-gradient(90deg,var(--smk-biru),var(--smk-merah))">
            <span class="text-white fw-bold"><i class="bi bi-funnel me-2"></i>Filter</span>
        </div>
        <div class="card-body">
            <form method="GET" action="/admin/aspirasi">
                <div class="row g-2">
                    <?php if(count($allowedJenis) > 1): ?>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Jenis</label>
                        <select name="jenis" class="form-select form-select-sm">
                            <option value="">Semua Jenis</option>
                            <option value="sarana_prasarana" <?php echo e(request('jenis')=='sarana_prasarana' ? 'selected' : ''); ?>>🔧 Sarana Prasarana</option>
                            <option value="kesejahteraan_siswa" <?php echo e(request('jenis')=='kesejahteraan_siswa' ? 'selected' : ''); ?>>💛 Kesejahteraan Siswa</option>
                        </select>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="jenis" value="<?php echo e($allowedJenis[0] ?? ''); ?>">
                    <?php endif; ?>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Tanggal</label>
                        <input type="date" name="tanggal" value="<?php echo e(request('tanggal')); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            <?php $__currentLoopData = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($i+1); ?>" <?php echo e(request('bulan')==$i+1 ? 'selected' : ''); ?>><?php echo e($bln); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Siswa</label>
                        <select name="nis" class="form-select form-select-sm">
                            <option value="">Semua Siswa</option>
                            <?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->nis); ?>" <?php echo e(request('nis')==$s->nis ? 'selected' : ''); ?>><?php echo e($s->nama_siswa); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Kategori</label>
                        <select name="id_kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id_kategori); ?>" <?php echo e(request('id_kategori')==$k->id_kategori ? 'selected' : ''); ?>><?php echo e($k->ket_kategori); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <?php $__currentLoopData = $statusList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($st); ?>" <?php echo e(request('status')==$st ? 'selected' : ''); ?>><?php echo e($st); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-sm btn-smk-biru w-100"><i class="bi bi-search"></i></button>
                        <a href="/admin/aspirasi" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-smk">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-smk table-hover mb-0">
                    <thead><tr><th>No</th><th>Tgl</th><th>Jenis</th><th>Siswa</th><th>Kategori</th><th>Lokasi</th><th>Keterangan</th><th>Foto</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php ($no=1); ?>
                        <?php $__empty_1 = true; $__currentLoopData = $aspirasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($no++); ?></td>
                            <td class="small text-muted"><?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d/m/Y')); ?></td>
                            <td>
                                <?php if($row->jenis=='sarana_prasarana'): ?>
                                    <span class="badge badge-sarana">🔧 Sarana</span>
                                <?php else: ?>
                                    <span class="badge badge-kesejahteraan">💛 Kesejahteraan</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-semibold small">
                                <?php if($row->anonim): ?> <em class="text-muted">Anonim</em>
                                <?php else: ?> <?php echo e($row->nama_siswa); ?><br><span class="badge bg-secondary" style="font-size:.65rem"><?php echo e($row->kelas); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge <?php echo e($row->jenis=='sarana_prasarana' ? 'badge-sarana' : 'badge-kesejahteraan'); ?>"><?php echo e($row->ket_kategori); ?></span></td>
                            <td class="small"><?php echo e($row->lokasi); ?></td>
                            <td class="small" style="max-width:130px" title="<?php echo e($row->ket); ?>"><?php echo e(Str::limit($row->ket,45)); ?></td>
                            <td>
                                <?php if($row->foto): ?>
                                    <img src="<?php echo e(asset('storage/'.$row->foto)); ?>" class="foto-thumb" onclick="showFoto('<?php echo e(asset('storage/'.$row->foto)); ?>')" title="Klik lihat foto">
                                <?php else: ?> <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row->status=='Menunggu'): ?> <span class="badge badge-menunggu">⏳ <?php echo e($row->status); ?></span>
                                <?php elseif($row->status=='Proses'): ?> <span class="badge badge-proses">⚙️ <?php echo e($row->status); ?></span>
                                <?php else: ?> <span class="badge badge-selesai">✅ <?php echo e($row->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><a href="/admin/aspirasi/feedback/<?php echo e($row->id_pelaporan); ?>" class="btn btn-sm btn-smk-merah"><i class="bi bi-chat-square-text"></i></a></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="10" class="text-center text-muted py-5"><i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Tidak ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if(count($aspirasis)>0): ?>
        <div class="card-footer text-muted small">Menampilkan <b><?php echo e(count($aspirasis)); ?></b> laporan</div>
        <?php endif; ?>
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

<?php $__env->startSection('extra-js'); ?>
<script>
function showFoto(url) {
    document.getElementById('fotoModalImg').src = url;
    new bootstrap.Modal(document.getElementById('fotoModal')).show();
}
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\pengaduan_sarana\resources\views/admin/list_aspirasi.blade.php ENDPATH**/ ?>