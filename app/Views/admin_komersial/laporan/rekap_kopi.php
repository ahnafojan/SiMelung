<?php
// Hapus parameter paginasi lama agar tidak menumpuk di URL
$queryParams = $_GET;
unset($queryParams['page_masuk'], $queryParams['page_keluar'], $queryParams['page_stok'], $queryParams['page_petani']);
?>

<div class="card shadow border-0 mb-4 animated--grow-in">
    <div class="card-header border-0 py-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center bg-white">
        <h6 class="m-0 font-weight-bold text-warning mb-2 mb-sm-0">
            <i class="fas fa-seedling mr-1"></i> Rekap Kopi Masuk per Petani
        </h6>
        <div class="d-flex flex-column flex-sm-row mt-2 mt-sm-0">
            <a href="<?= base_url('admin-komersial/export/masuk/excel?' . http_build_query($filter)) ?>" class="btn btn-success rounded-pill mb-2 mb-sm-0 mr-sm-2">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
            <a href="<?= base_url('admin-komersial/export/masuk/pdf?' . http_build_query($filter)) ?>" class="btn btn-danger rounded-pill">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-start mb-3">
            <form action="<?= current_url() ?>" method="get" class="form-inline">
                <?php foreach ($queryParams as $key => $val): ?>
                    <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                <?php endforeach; ?>
                <label for="per_page_masuk" class="text-muted fw-bold mr-2">Tampilkan</label>
                <select name="per_page_masuk" id="per_page_masuk" class="form-control d-inline-block w-auto form-control-sm rounded-pill" onchange="this.form.submit()">
                    <option value="10" <?= ($perPageMasuk == 10) ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= ($perPageMasuk == 25) ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= ($perPageMasuk == 50) ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($perPageMasuk == 100) ? 'selected' : '' ?>>100</option>
                </select>
                <span class="ml-2">entri</span>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTablePetani" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Petani</th>
                        <th class="text-right">Total Masuk (Kg)</th>
                        <th>Tanggal Setor Terakhir</th>
                        <th class="text-center">Jumlah Transaksi</th>
                        <th class="text-right">Rata-rata Setoran (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rekapPetani)): ?>
                        <?php
                        $page = (int) (service('request')->getGet('page_masuk') ?? 1);
                        $no = 1 + (($page - 1) * $perPageMasuk);
                        ?>
                        <?php foreach ($rekapPetani as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="font-weight-bold text-primary"><?= esc($p['nama_petani']) ?></td>
                                <td class="text-right text-success"><?= number_format($p['total_masuk'], 2) ?></td>
                                <td><?= esc($p['tanggal_terakhir']) ?></td>
                                <td class="text-center"><?= $p['jumlah_transaksi'] ?></td>
                                <td class="text-right"><?= number_format($p['rata_rata_setoran'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info mt-3" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i> Tidak ada data kopi masuk untuk filter ini.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($rekapPetani) && isset($pagerKopiMasuk)): ?>
            <div class="d-flex justify-content-end mt-3">
                <?= $pagerKopiMasuk->links('masuk', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

---

<div class="card shadow border-0 mb-4 animated--grow-in">
    <div class="card-header border-0 py-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center bg-white">
        <h6 class="m-0 font-weight-bold text-warning mb-2 mb-sm-0">
            <i class="fas fa-cash-register mr-1"></i> Rekap Kopi Keluar (Penjualan)
        </h6>
        <div class="d-flex flex-column flex-sm-row mt-2 mt-sm-0">
            <a href="<?= base_url('admin-komersial/export/keluar/excel?' . http_build_query($filter)) ?>" class="btn btn-success rounded-pill mb-2 mb-sm-0 mr-sm-2">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
            <a href="<?= base_url('admin-komersial/export/keluar/pdf?' . http_build_query($filter)) ?>" class="btn btn-danger rounded-pill">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-start mb-3">
            <form action="<?= current_url() ?>" method="get" class="form-inline">
                <?php foreach ($queryParams as $key => $val): ?>
                    <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                <?php endforeach; ?>
                <label for="per_page_keluar" class="mr-2">Tampilkan</label>
                <select name="per_page_keluar" id="per_page_keluar" class="form-control d-inline-block w-auto form-control-sm rounded-pill" onchange="this.form.submit()">
                    <option value="10" <?= ($perPageKeluar == 10) ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= ($perPageKeluar == 25) ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= ($perPageKeluar == 50) ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($perPageKeluar == 100) ? 'selected' : '' ?>>100</option>
                </select>
                <span class="ml-2">entri</span>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTablePenjualan" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis Kopi</th>
                        <th>Tujuan Pembeli</th>
                        <th class="text-right">Jumlah (Kg)</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rekapPenjualan)): ?>
                        <?php
                        $page = (int) (service('request')->getGet('page_keluar') ?? 1);
                        $no = 1 + (($page - 1) * $perPageKeluar);
                        ?>
                        <?php foreach ($rekapPenjualan as $j): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($j['tanggal']) ?></td>
                                <td><?= esc($j['jenis_kopi']) ?></td>
                                <td><?= esc($j['tujuan_pembeli']) ?></td>
                                <td class="text-right text-danger"><?= number_format($j['jumlah_kg'], 2) ?></td>
                                <td><?= esc($j['keterangan']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info mt-3" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i> Tidak ada data penjualan untuk filter ini.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($rekapPenjualan) && isset($pagerKopiKeluar)): ?>
            <div class="d-flex justify-content-end mt-3">
                <?= $pagerKopiKeluar->links('keluar', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

---

<div class="card shadow border-0 mb-4 animated--grow-in">
    <div class="card-header border-0 py-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center bg-white">
        <h6 class="m-0 font-weight-bold text-warning mb-2 mb-sm-0">
            <i class="fas fa-warehouse mr-1"></i> Stok Akhir Kopi
        </h6>
        <div class="d-flex flex-column flex-sm-row mt-2 mt-sm-0">
            <a href="<?= base_url('admin-komersial/export/stok/excel?' . http_build_query($filter)) ?>" class="btn btn-success rounded-pill mb-2 mb-sm-0 mr-sm-2">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
            <a href="<?= base_url('admin-komersial/export/stok/pdf?' . http_build_query($filter)) ?>" class="btn btn-danger rounded-pill">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-start mb-3">
            <form action="<?= current_url() ?>" method="get" class="form-inline">
                <?php foreach ($queryParams as $key => $val): ?>
                    <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                <?php endforeach; ?>
                <label for="per_page_stok" class="mr-2">Tampilkan</label>
                <select name="per_page_stok" id="per_page_stok" class="form-control d-inline-block w-auto form-control-sm rounded-pill" onchange="this.form.submit()">
                    <option value="10" <?= ($perPageStok == 10) ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= ($perPageStok == 25) ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= ($perPageStok == 50) ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($perPageStok == 100) ? 'selected' : '' ?>>100</option>
                </select>
                <span class="ml-2">entri</span>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTableStok" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Jenis Kopi</th>
                        <th class="text-right">Total Stok (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stokAkhirPerJenis)): ?>
                        <?php
                        $page = (int) (service('request')->getGet('page_stok') ?? 1);
                        $no = 1 + (($page - 1) * $perPageStok);
                        ?>
                        <?php foreach ($stokAkhirPerJenis as $s): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="font-weight-bold"><?= esc($s['jenis_kopi']) ?></td>
                                <td class="text-right font-weight-bold"><?= number_format($s['stok_akhir'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">
                                <div class="alert alert-info mt-3" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i> Tidak ada data stok yang tersedia.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($stokAkhirPerJenis)): ?>
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <th colspan="2" class="text-right">Total Stok Akhir Global</th>
                            <th class="text-right text-primary"><?= number_format($totalStokGlobal, 2) ?></th>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
        <?php if (!empty($stokAkhirPerJenis) && isset($pagerStokAkhir)): ?>
            <div class="d-flex justify-content-end mt-3">
                <?= $pagerStokAkhir->links('stok', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>
</div>