<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<?php
// Ambil semua query GET
$queryParams = service('request')->getGet();
?>

<style>
    /* Secara default, sembunyikan tampilan mobile di layar besar */
    .mobile-view {
        display: none;
    }

    /* Terapkan gaya ini hanya pada layar kecil (di bawah 768px) */
    @media (max-width: 767.98px) {
        .desktop-view {
            display: none;
        }

        .export-buttons {
            width: 100%;
            justify-content: flex-end;
        }

        .mobile-view {
            display: block;
        }

        .mobile-card {
            margin-bottom: 1rem;
            border: 1px solid #e3e6f0;
            border-radius: .35rem;
        }

        .mobile-card .card-header {
            background-color: #f8f9fc;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #e3e6f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-card .card-body {
            padding: 1rem;
        }

        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f0f1f3;
            font-size: 0.9rem;
        }

        .mobile-card-row:last-child {
            border-bottom: none;
        }

        .mobile-card-label {
            font-weight: bold;
            color: #5a5c69;
            padding-right: 10px;
        }

        .mobile-card-value {
            text-align: right;
        }
    }

    /* === CSS UNTUK PAGINATION === */
    .pagination-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        padding: 0.5rem 0;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #5a5c69;
    }

    .per-page-label i {
        color: #858796;
    }

    .dropdown-container {
        position: relative;
        display: inline-block;
    }

    .per-page-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        cursor: pointer;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .per-page-select:focus {
        border-color: #4e73df;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .dropdown-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #858796;
    }

    .page-info {
        font-size: 0.875rem;
        color: #5a5c69;
    }

    .info-text .fas {
        color: #858796;
    }

    .pagination-nav .pagination {
        margin-bottom: 0;
    }

    @media (max-width: 991.98px) {
        .pagination-wrapper {
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }

        .pagination-nav {
            order: -1;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title">Laporan Rekap Kopi</h1>
            <p class="mb-0 page-subtitle">Detail Rekap Kopi Masuk/Keluar, dan Stok Kopi.</p>
        </div>
        <a href="<?= base_url('bumdes/laporan') ?>" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <!-- ================== FILTER ================== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-sliders-h mr-2"></i> Filter Laporan Rekap Kopi
            </h6>
        </div>
        <div class="card-body">
            <form id="filter-form" action="<?= base_url('bumdes/laporan/kopi') ?>" method="get">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <label for="start_date" class="form-label">Dari Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            <input type="date" id="start_date" name="start_date" value="<?= esc($filter['start_date']) ?>" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <label for="end_date" class="form-label">Sampai Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            <input type="date" id="end_date" name="end_date" value="<?= esc($filter['end_date']) ?>" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-8 mb-3 mb-lg-0">
                        <label for="petani" class="form-label">Pilih Petani</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <select id="petani" name="petani" class="form-control">
                                <option value="">-- Semua Petani --</option>
                                <?php foreach ($petaniList as $p) : ?>
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
                        <a href="<?= base_url('bumdes/laporan/kopi') ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================== REKAP KOPI MASUK ================== -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-seedling mr-2"></i> Rekap Kopi Masuk Petani
            </h6>
            <div class="export-buttons d-flex flex-wrap gap-2 mt-2 mt-md-0">
                <a href="<?= base_url('bumdes/export/kopi/masuk/excel?' . http_build_query($filter)) ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('bumdes/export/kopi/masuk/pdf?' . http_build_query($filter)) ?>" class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="desktop-view">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Petani</th>
                                <th>Jenis Kopi</th>
                                <th class="text-right">Total Masuk (Kg)</th>
                                <th class="text-right">Total Harga Masuk (Rp)</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Jumlah Transaksi</th>
                                <th class="text-right">Rata-rata Setoran (Kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rekapPetani)) : ?>
                                <?php $page = (int)(service('request')->getGet('page_masuk') ?? 1);
                                $no = 1 + (($page - 1) * $perPageMasuk); ?>
                                <?php foreach ($rekapPetani as $p) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="font-weight-bold text-primary"><?= esc($p['nama_petani']) ?></td>
                                        <td><?= esc($p['jenis_kopi']) ?></td>
                                        <td class="text-right text-success"><?= number_format($p['total_masuk'], 2) ?></td>
                                        <td class="text-right text-primary font-weight-bold">Rp <?= number_format($p['total_nilai_masuk'] ?? 0, 0, ',', '.') ?></td>
                                        <td><?= esc($p['tanggal_transaksi']) ?></td>
                                        <td class="text-center"><?= $p['jumlah_transaksi'] ?></td>
                                        <td class="text-right"><?= number_format($p['rata_rata_setoran'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada data kopi masuk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mobile-view">
                <?php if (!empty($rekapPetani)) : ?>
                    <?php $page = (int)(service('request')->getGet('page_masuk') ?? 1);
                    $no = 1 + (($page - 1) * $perPageMasuk); ?>
                    <?php foreach ($rekapPetani as $p) : ?>
                        <div class="card mobile-card">
                            <div class="card-header">
                                <h6 class="font-weight-bold text-primary mb-0"><?= esc($p['nama_petani']) ?></h6>
                                <span class="badge badge-pill badge-light p-2">#<?= $no++ ?></span>
                            </div>
                            <div class="card-body">
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Total Masuk</span>
                                    <span class="mobile-card-value text-success font-weight-bold"><?= number_format($p['total_masuk'], 2) ?> Kg</span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Jenis Kopi</span>
                                    <span class="mobile-card-value"><?= esc($p['jenis_kopi']) ?></span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Total Harga Masuk</span>
                                    <span class="mobile-card-value text-primary font-weight-bold">Rp <?= number_format($p['total_nilai_masuk'] ?? 0, 0, ',', '.') ?></span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Tanggal</span>
                                    <span class="mobile-card-value"><?= esc($p['tanggal_transaksi']) ?></span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Jml Transaksi</span>
                                    <span class="mobile-card-value"><?= $p['jumlah_transaksi'] ?></span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Rata-rata Setoran</span>
                                    <span class="mobile-card-value"><?= number_format($p['rata_rata_setoran'], 2) ?> Kg</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="text-center text-muted p-3">Tidak ada data kopi masuk.</div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($pagerKopiMasuk) && $pagerKopiMasuk->getPageCount('masuk') > 1) : ?>
            <div class="card-footer">
                <div class="pagination-wrapper">
                    <form method="get" class="per-page-selector">
                        <?php foreach ($queryParams as $key => $val) : if (!in_array($key, ['page_masuk', 'per_page_masuk'])) : ?>
                                <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                        <?php endif;
                        endforeach; ?>
                        <label class="per-page-label"><i class="fas fa-list-ul me-2"></i> Tampilkan</label>
                        <div class="dropdown-container">
                            <select name="per_page_masuk" class="per-page-select" onchange="this.form.submit()">
                                <option value="10" <?= ($perPageMasuk == 10 ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= ($perPageMasuk == 25 ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= ($perPageMasuk == 50 ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= ($perPageMasuk == 100 ? 'selected' : '') ?>>100</option>
                            </select>
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </div>
                        <span class="per-page-suffix">data per halaman</span>
                    </form>

                    <nav class="pagination-nav" aria-label="Navigasi Halaman Kopi Masuk">
                        <?= $pagerKopiMasuk->links('masuk', 'custom_pagination_template') ?>
                    </nav>

                    <div class="page-info">
                        <span class="info-text">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php
                            $currentPageMasuk = $pagerKopiMasuk->getCurrentPage('masuk');
                            $totalItemsMasuk  = $pagerKopiMasuk->getTotal('masuk');
                            $startItemMasuk   = ($currentPageMasuk - 1) * $perPageMasuk + 1;
                            $endItemMasuk     = min($currentPageMasuk * $perPageMasuk, $totalItemsMasuk);
                            ?>
                            Menampilkan <?= $startItemMasuk ?>-<?= $endItemMasuk ?> dari <?= $totalItemsMasuk ?> data
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- ================== REKAP KOPI KELUAR ================== -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-cash-register mr-2"></i> Rekap Kopi Keluar
            </h6>
            <div class="export-buttons d-flex flex-wrap gap-2 mt-2 mt-md-0">
                <a href="<?= base_url('bumdes/export/kopi/keluar/excel?' . http_build_query($filter)) ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('bumdes/export/kopi/keluar/pdf?' . http_build_query($filter)) ?>" class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="desktop-view">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Petani</th>
                                <th>Jenis Kopi</th>
                                <th>Tujuan Pembeli</th>
                                <th class="text-right">Jumlah (Kg)</th>
                                <th class="text-right">Harga Jual (Rp/Kg)</th>
                                <th class="text-right">Keuntungan BUMDes (Rp)</th>
                                <th class="text-right">Total Harga Jual Petani (Rp)</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rekapPenjualan)) : ?>
                                <?php $page = (int)(service('request')->getGet('page_keluar') ?? 1);
                                $no = 1 + (($page - 1) * $perPageKeluar); ?>
                                <?php foreach ($rekapPenjualan as $j) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($j['tanggal']) ?></td>
                                        <td><?= esc($j['nama_petani']) ?></td>
                                        <td><?= esc($j['jenis_kopi']) ?></td>
                                        <td><?= esc($j['tujuan_pembeli']) ?></td>
                                        <td class="text-right text-danger"><?= number_format($j['jumlah_kg'], 2) ?></td>
                                        <td class="text-right"><?= number_format($j['harga_jual_per_kg'] ?? 0, 0, ',', '.') ?></td>
                                        <td class="text-right text-success font-weight-bold">
                                            Rp <?= number_format($j['keuntungan_bumdes'] ?? 0, 0, ',', '.') ?>
                                        </td>
                                        <td class="text-right text-primary font-weight-bold">
                                            Rp <?= number_format($j['total_harga_petani'] ?? 0, 0, ',', '.') ?>
                                        </td>
                                        <td><?= esc($j['keterangan']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Tidak ada data penjualan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mobile-view">
                <?php if (!empty($rekapPenjualan)) : ?>
                    <?php $page = (int)(service('request')->getGet('page_keluar') ?? 1);
                    $no = 1 + (($page - 1) * $perPageKeluar); ?>
                    <?php foreach ($rekapPenjualan as $j) : ?>
                        <div class="card mobile-card">
                            <div class="card-header">
                                <h6 class="font-weight-bold text-dark mb-0"><?= esc($j['jenis_kopi']) ?></h6>
                                <span class="badge badge-pill badge-light p-2"><?= esc($j['tanggal']) ?></span>
                            </div>
                            <div class="card-body">
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Nama Petani</span>
                                    <span class="mobile-card-value"><?= esc($j['nama_petani']) ?></span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Jumlah Keluar</span>
                                    <span class="mobile-card-value text-danger font-weight-bold"><?= number_format($j['jumlah_kg'], 2) ?> Kg</span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Harga Jual</span>
                                    <span class="mobile-card-value">Rp <?= number_format($j['harga_jual_per_kg'] ?? 0, 0, ',', '.') ?>/Kg</span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Keuntungan BUMDes</span>
                                    <span class="mobile-card-value text-success font-weight-bold">
                                        Rp <?= number_format($j['keuntungan_bumdes'] ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Total Harga Petani</span>
                                    <span class="mobile-card-value text-primary font-weight-bold">
                                        Rp <?= number_format($j['total_harga_petani'] ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Tujuan Pembeli</span>
                                    <span class="mobile-card-value"><?= esc($j['tujuan_pembeli']) ?></span>
                                </div>
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">Keterangan</span>
                                    <span class="mobile-card-value"><?= esc($j['keterangan']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="text-center text-muted p-3">Tidak ada data penjualan.</div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($pagerKopiKeluar) && $pagerKopiKeluar->getPageCount('keluar') > 1) : ?>
            <div class="card-footer">
                <div class="pagination-wrapper">
                    <form method="get" class="per-page-selector">
                        <?php foreach ($queryParams as $key => $val) : if (!in_array($key, ['page_keluar', 'per_page_keluar'])) : ?>
                                <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                        <?php endif;
                        endforeach; ?>
                        <label class="per-page-label"><i class="fas fa-list-ul me-2"></i> Tampilkan</label>
                        <div class="dropdown-container">
                            <select name="per_page_keluar" class="per-page-select" onchange="this.form.submit()">
                                <option value="10" <?= ($perPageKeluar == 10 ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= ($perPageKeluar == 25 ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= ($perPageKeluar == 50 ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= ($perPageKeluar == 100 ? 'selected' : '') ?>>100</option>
                            </select>
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </div>
                        <span class="per-page-suffix">data per halaman</span>
                    </form>

                    <nav class="pagination-nav" aria-label="Navigasi Halaman Kopi Keluar">
                        <?= $pagerKopiKeluar->links('keluar', 'custom_pagination_template') ?>
                    </nav>

                    <div class="page-info">
                        <span class="info-text">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php
                            $currentPageKeluar = $pagerKopiKeluar->getCurrentPage('keluar');
                            $totalItemsKeluar  = $pagerKopiKeluar->getTotal('keluar');
                            $startItemKeluar   = ($currentPageKeluar - 1) * $perPageKeluar + 1;
                            $endItemKeluar     = min($currentPageKeluar * $perPageKeluar, $totalItemsKeluar);
                            ?>
                            Menampilkan <?= $startItemKeluar ?>-<?= $endItemKeluar ?> dari <?= $totalItemsKeluar ?> data
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- ================== STOK AKHIR ================== -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-warehouse mr-2"></i> Stok Kopi yang Tercatat
            </h6>
            <div class="export-buttons d-flex flex-wrap gap-2 mt-2 mt-md-0">
                <a href="<?= base_url('bumdes/export/kopi/stok/excel?' . http_build_query($filter)) ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('bumdes/export/kopi/stok/pdf?' . http_build_query($filter)) ?>" class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="desktop-view">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Kopi</th>
                                <th class="text-right">Total Stok Kopi (Kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($stokAkhirPerJenis)) : ?>
                                <?php $page = (int)(service('request')->getGet('page_stok') ?? 1);
                                $no = 1 + (($page - 1) * $perPageStok); ?>
                                <?php foreach ($stokAkhirPerJenis as $s) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="text-left font-weight-bold"><?= esc($s['jenis_kopi']) ?></td>
                                        <td class="text-right"><?= number_format($s['stok_akhir'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data stok.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                        <?php if (!empty($stokAkhirPerJenis)) : ?>
                            <tfoot class="table-info">
                                <tr>
                                    <th colspan="2" class="text-right">Total</th>
                                    <th class="text-right text-primary font-weight-bold"><?= number_format($totalStokGlobal, 2) ?></th>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <div class="mobile-view">
                <?php if (!empty($stokAkhirPerJenis)) : ?>
                    <?php foreach ($stokAkhirPerJenis as $s) : ?>
                        <div class="card mobile-card">
                            <div class="card-body">
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label"><?= esc($s['jenis_kopi']) ?></span>
                                    <span class="mobile-card-value font-weight-bold"><?= number_format($s['stok_akhir'], 2) ?> Kg</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="card mobile-card bg-info text-white">
                        <div class="card-body">
                            <div class="mobile-card-row" style="border-bottom:none;">
                                <span class="mobile-card-label text-white">Total Stok Semua Kopi</span>
                                <span class="mobile-card-value font-weight-bolder"><?= number_format($totalStokGlobal, 2) ?> Kg</span>
                            </div>
                            <div class="mobile-card-row" style="border-bottom:none;">
                                <span class="mobile-card-label text-white">Total Harga Semua Kopi</span>
                                <span class="mobile-card-value font-weight-bolder">Rp <?= number_format($totalNilaiStokGlobal ?? 0, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="text-center text-muted p-3">Tidak ada data stok.</div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($pagerStokAkhir) && $pagerStokAkhir->getPageCount('stok') > 1) : ?>
            <div class="card-footer">
                <div class="pagination-wrapper">
                    <form method="get" class="per-page-selector">
                        <?php foreach ($queryParams as $key => $val) : if (!in_array($key, ['page_stok', 'per_page_stok'])) : ?>
                                <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                        <?php endif;
                        endforeach; ?>
                        <label class="per-page-label"><i class="fas fa-list-ul me-2"></i> Tampilkan</label>
                        <div class="dropdown-container">
                            <select name="per_page_stok" class="per-page-select" onchange="this.form.submit()">
                                <option value="10" <?= ($perPageStok == 10 ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= ($perPageStok == 25 ? 'selected' : '') ?>>25</option>
                                <option value="50" <?= ($perPageStok == 50 ? 'selected' : '') ?>>50</option>
                                <option value="100" <?= ($perPageStok == 100 ? 'selected' : '') ?>>100</option>
                            </select>
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </div>
                        <span class="per-page-suffix">data per halaman</span>
                    </form>

                    <nav class="pagination-nav" aria-label="Navigasi Halaman Stok">
                        <?= $pagerStokAkhir->links('stok', 'custom_pagination_template') ?>
                    </nav>

                    <div class="page-info">
                        <span class="info-text">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php
                            $currentPageStok = $pagerStokAkhir->getCurrentPage('stok');
                            $totalItemsStok  = $pagerStokAkhir->getTotal('stok');
                            $startItemStok   = ($currentPageStok - 1) * $perPageStok + 1;
                            $endItemStok     = min($currentPageStok * $perPageStok, $totalItemsStok);
                            ?>
                            Menampilkan <?= $startItemStok ?>-<?= $endItemStok ?> dari <?= $totalItemsStok ?> data
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>