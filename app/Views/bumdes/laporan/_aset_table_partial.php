<!-- Desktop Table View -->
<div class="d-none d-lg-block">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Nama Aset</th>
                    <th class="text-center" style="width: 100px;">Kode</th>
                    <th class="text-center" style="width: 80px;">NUP</th>
                    <th class="text-center" style="width: 80px;">Tahun</th>
                    <th>Merk/Tipe</th>
                    <th class="text-right" style="width: 120px;">Nilai (Rp)</th>
                    <th class="text-center" style="width: 100px;">Metode</th>
                    <th class="text-center" style="width: 100px;">Sumber</th>
                    <th class="text-center" style="width: 80px;">Foto</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($aset)): ?>
                    <?php $no = 1 + ($pagerAset->getCurrentPage('aset') - 1) * $pagerAset->getPerPage('aset'); ?>
                    <?php foreach ($aset as $item): ?>
                        <tr class="hover-row">
                            <td class="text-center font-weight-bold text-gray-700">
                                <?= $no++ ?>
                            </td>
                            <td>
                                <div class="font-weight-bold text-primary">
                                    <?= esc($item['nama_aset']) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-outline-primary">
                                    <?= esc($item['kode_aset']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-outline-info">
                                    <?= esc($item['nup']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-secondary">
                                    <?= esc($item['tahun_perolehan']) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-gray-600">
                                    <?= esc($item['merk_type']) ?>
                                </small>
                            </td>
                            <td class="text-right font-weight-bold text-success">
                                <?= number_format($item['nilai_perolehan'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <small class="text-gray-600">
                                    <?= esc($item['metode_pengadaan']) ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <small class="text-gray-600">
                                    <?= esc($item['sumber_pengadaan']) ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($item['foto'])): ?>
                                    <img src="<?= base_url('uploads/foto_aset/' . $item['foto']) ?>"
                                        alt="Foto Aset" class="img-thumbnail shadow-sm cursor-pointer"
                                        style="max-width: 50px; max-height: 50px;"
                                        data-toggle="modal" data-target="#imageModal"
                                        onclick="showImage('<?= base_url('uploads/foto_aset/' . $item['foto']) ?>', '<?= esc($item['nama_aset']) ?>')">
                                <?php else: ?>
                                    <i class="fas fa-image text-gray-400"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-gray-600">
                                    <?= esc($item['keterangan']) ?>
                                </small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center py-4">
                            <div class="text-gray-500">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <div>
                                    <h6>Tidak ada data aset</h6>
                                    <p class="mb-0">Tidak ditemukan data aset untuk filter yang dipilih.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tablet View -->
<div class="d-none d-md-block d-lg-none">
    <?php if (!empty($aset)): ?>
        <?php $no = 1 + ($pagerAset->getCurrentPage('aset') - 1) * $pagerAset->getPerPage('aset'); ?>
        <?php foreach ($aset as $item): ?>
            <div class="card border-left-primary shadow-sm mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h6 class="font-weight-bold text-primary mb-1">
                                <?= $no++ ?>. <?= esc($item['nama_aset']) ?>
                            </h6>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <small class="text-gray-600">
                                        <strong>Kode:</strong>
                                        <span class="badge badge-outline-primary ml-1">
                                            <?= esc($item['kode_aset']) ?>
                                        </span>
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-gray-600">
                                        <strong>NUP:</strong>
                                        <span class="badge badge-outline-info ml-1">
                                            <?= esc($item['nup']) ?>
                                        </span>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <small class="text-gray-600">
                                        <strong>Tahun:</strong> <?= esc($item['tahun_perolehan']) ?>
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-gray-600">
                                        <strong>Merk:</strong> <?= esc($item['merk_type']) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="mb-2">
                                <small class="text-gray-600">
                                    <strong>Nilai:</strong>
                                    <span class="font-weight-bold text-success">
                                        Rp <?= number_format($item['nilai_perolehan'], 0, ',', '.') ?>
                                    </span>
                                </small>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-gray-600">
                                        <strong>Metode:</strong> <?= esc($item['metode_pengadaan']) ?>
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-gray-600">
                                        <strong>Sumber:</strong> <?= esc($item['sumber_pengadaan']) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <?php if (!empty($item['foto'])): ?>
                                <img src="<?= base_url('uploads/foto_aset/' . $item['foto']) ?>"
                                    alt="Foto Aset" class="img-fluid rounded shadow-sm cursor-pointer"
                                    style="max-height: 100px;"
                                    data-toggle="modal" data-target="#imageModal"
                                    onclick="showImage('<?= base_url('uploads/foto_aset/' . $item['foto']) ?>', '<?= esc($item['nama_aset']) ?>')">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 80px;">
                                    <i class="fas fa-image text-gray-400 fa-2x"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($item['keterangan'])): ?>
                        <div class="mt-2 pt-2 border-top">
                            <small class="text-gray-600">
                                <strong>Keterangan:</strong> <?= esc($item['keterangan']) ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
            <h6 class="text-gray-500">Tidak ada data aset</h6>
            <p class="text-gray-400 mb-0">Tidak ditemukan data aset untuk filter yang dipilih.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Mobile View -->
<div class="d-block d-md-none">
    <?php if (!empty($aset)): ?>
        <?php $no = 1 + ($pagerAset->getCurrentPage('aset') - 1) * $pagerAset->getPerPage('aset'); ?>
        <?php foreach ($aset as $item): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0 font-weight-bold">
                        <?= $no++ ?>. <?= esc($item['nama_aset']) ?>
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row mb-2">
                        <div class="col-6">
                            <small>
                                <strong>Kode:</strong><br>
                                <span class="badge badge-primary">
                                    <?= esc($item['kode_aset']) ?>
                                </span>
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <strong>NUP:</strong><br>
                                <span class="badge badge-info">
                                    <?= esc($item['nup']) ?>
                                </span>
                            </small>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6">
                            <small>
                                <strong>Tahun:</strong><br>
                                <span class="text-gray-700"><?= esc($item['tahun_perolehan']) ?></span>
                            </small>
                        </div>
                        <div class="col-6">
                            <small>
                                <strong>Merk/Tipe:</strong><br>
                                <span class="text-gray-700"><?= esc($item['merk_type']) ?></span>
                            </small>
                        </div>
                    </div>

                    <div class="mb-2">
                        <small>
                            <strong>Nilai Perolehan:</strong><br>
                            <span class="font-weight-bold text-success h6">
                                Rp <?= number_format($item['nilai_perolehan'], 0, ',', '.') ?>
                            </span>
                        </small>
                    </div>

                    <div class="mb-2">
                        <small>
                            <strong>Metode Pengadaan:</strong><br>
                            <span class="text-gray-700"><?= esc($item['metode_pengadaan']) ?></span>
                        </small>
                    </div>

                    <div class="mb-2">
                        <small>
                            <strong>Sumber Pengadaan:</strong><br>
                            <span class="text-gray-700"><?= esc($item['sumber_pengadaan']) ?></span>
                        </small>
                    </div>

                    <?php if (!empty($item['keterangan'])): ?>
                        <div class="mb-3">
                            <small>
                                <strong>Keterangan:</strong><br>
                                <span class="text-gray-700"><?= esc($item['keterangan']) ?></span>
                            </small>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($item['foto'])): ?>
                        <div class="text-center">
                            <img src="<?= base_url('uploads/foto_aset/' . $item['foto']) ?>"
                                alt="Foto Aset" class="img-fluid rounded shadow cursor-pointer"
                                style="max-height: 150px;"
                                data-toggle="modal" data-target="#imageModal"
                                onclick="showImage('<?= base_url('uploads/foto_aset/' . $item['foto']) ?>', '<?= esc($item['nama_aset']) ?>')">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
            <h6 class="text-gray-500">Tidak ada data aset</h6>
            <p class="text-gray-400 mb-0">Tidak ditemukan data aset untuk filter yang dipilih.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (!empty($aset) && $pagerAset): ?>
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            <small class="text-gray-600">
                Menampilkan <?= count($aset) ?> dari total <?= $pagerAset->getTotal('aset') ?> data
            </small>
        </div>
        <div>
            <?= $pagerAset->links('aset', 'default_full') ?>
        </div>
    </div>
<?php endif; ?>