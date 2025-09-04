<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<?php
// Mengambil parameter GET yang ada untuk ditambahkan ke link pagination dan form 'per page'
$queryParams = $_GET;
unset($queryParams['page_masuk'], $queryParams['page_keluar'], $queryParams['page_stok']);
?>

<!-- ================== FILTER ================== -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title">Laporan Rekap Kopi</h1>
            <p class="mb-0 page-subtitle">Detail Rekap Kopi Masuk/Keluar, dan Stok Kopi.</p>
        </div>
        <!-- Tombol Kembali mengarah ke laporan desa -->
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-sliders-h mr-2"></i> Filter Laporan Rekap Kopi
            </h6>
        </div>
        <div class="card-body">
            <!-- Form action diubah ke current_url() agar selalu memfilter di halaman ini -->
            <form id="filter-form" action="<?= current_url() ?>" method="get">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label for="start_date" class="form-label">Dari Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            <input type="date" id="start_date" name="start_date" value="<?= esc($filter['start_date']) ?>" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="end_date" class="form-label">Sampai Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            <input type="date" id="end_date" name="end_date" value="<?= esc($filter['end_date']) ?>" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-8">
                        <label for="petani" class="form-label">Pilih Petani</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <select id="petani" name="petani" class="form-control">
                                <option value="">-- Semua Petani --</option>
                                <?php foreach ($petaniList as $p): ?>
                                    <option value="<?= $p['user_id'] ?>" <?= ($filter['petani'] == $p['user_id']) ? 'selected' : '' ?>>
                                        <?= esc($p['nama_petani']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                        <!-- Tombol Reset mengarah ke laporan kopi desa -->
                        <a href="<?= base_url('DesaRekapKopi') ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================== REKAP KOPI MASUK ================== -->
    <div class="card shadow mb-4">
        <!-- Header kartu disederhanakan, tanpa tombol ekspor -->
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-seedling mr-2"></i> Rekap Kopi Masuk per Petani
            </h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form action="<?= current_url() ?>" method="get" id="perPageFormMasuk" class="form-inline">
                    <?php foreach ($queryParams as $key => $val): ?>
                        <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                    <?php endforeach; ?>
                    <label class="mr-2 text-muted">Tampilkan</label>
                    <select name="per_page_masuk" id="per_page_masuk" onchange="this.form.submit()" class="form-control form-control-sm">
                        <option value="10" <?= ($perPageMasuk == 10) ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($perPageMasuk == 25) ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($perPageMasuk == 50) ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= ($perPageMasuk == 100) ? 'selected' : '' ?>>100</option>
                    </select>
                    <span class="ml-2 text-muted">entri</span>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
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
                            <?php $page = (int)(service('request')->getGet('page_masuk') ?? 1);
                            $no = 1 + (($page - 1) * $perPageMasuk); ?>
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
                                <td colspan="6" class="text-center text-muted">Tidak ada data kopi masuk.</td>
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

    <!-- ================== REKAP KOPI KELUAR ================== -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-cash-register mr-2"></i> Rekap Kopi Keluar
            </h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form action="<?= current_url() ?>" method="get" id="perPageFormKeluar" class="form-inline">
                    <?php foreach ($queryParams as $key => $val): ?>
                        <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                    <?php endforeach; ?>
                    <label class="mr-2 text-muted">Tampilkan</label>
                    <select name="per_page_keluar" id="per_page_keluar" onchange="this.form.submit()" class="form-control form-control-sm">
                        <option value="10" <?= ($perPageKeluar == 10) ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($perPageKeluar == 25) ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($perPageKeluar == 50) ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= ($perPageKeluar == 100) ? 'selected' : '' ?>>100</option>
                    </select>
                    <span class="ml-2 text-muted">entri</span>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
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
                            <?php $page = (int)(service('request')->getGet('page_keluar') ?? 1);
                            $no = 1 + (($page - 1) * $perPageKeluar); ?>
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
                                <td colspan="6" class="text-center text-muted">Tidak ada data penjualan.</td>
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

    <!-- ================== STOK KOPI ================== -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-warehouse mr-2"></i> Stok Akhir Per Jenis Kopi
            </h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form action="<?= current_url() ?>" method="get" id="perPageFormStok" class="form-inline">
                    <?php foreach ($queryParams as $key => $val): ?>
                        <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                    <?php endforeach; ?>
                    <label class="mr-2 text-muted">Tampilkan</label>
                    <select name="per_page_stok" id="per_page_stok" onchange="this.form.submit()" class="form-control form-control-sm">
                        <option value="10" <?= ($perPageStok == 10) ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($perPageStok == 25) ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($perPageStok == 50) ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= ($perPageStok == 100) ? 'selected' : '' ?>>100</option>
                    </select>
                    <span class="ml-2 text-muted">entri</span>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Jenis Kopi</th>
                            <th class="text-right">Total Stok (Kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stokAkhirPerJenis)): ?>
                            <?php $page = (int)(service('request')->getGet('page_stok') ?? 1);
                            $no = 1 + (($page - 1) * $perPageStok); ?>
                            <?php foreach ($stokAkhirPerJenis as $s): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td class="text-left"><?= esc($s['jenis_kopi']) ?></td>
                                    <td class="text-right"><?= number_format($s['stok_akhir'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data stok.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <?php if (!empty($stokAkhirPerJenis)): ?>
                        <tfoot>
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
</div>

<?= $this->endSection() ?>