<?= $this->extend('layouts/main_layout_admin'); ?>

<?= $this->section('content'); ?>

<style>
    /* Style untuk stat card, tidak perlu diubah */
    .stat-card-detail {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .stat-card-detail:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    /* Style untuk tabel responsif, tidak perlu diubah */
    @media (max-width: 767.98px) {
        .table-responsive-stack thead {
            display: none;
        }

        .table-responsive-stack table,
        .table-responsive-stack tbody,
        .table-responsive-stack tr,
        .table-responsive-stack td {
            display: block;
            width: 100%;
        }

        .table-responsive-stack tr {
            margin-bottom: 1rem;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
        }

        .table-responsive-stack td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            text-align: right;
            border-bottom: 1px solid #e3e6f0;
        }

        .table-responsive-stack tr td:last-child {
            border-bottom: none;
        }

        .table-responsive-stack td::before {
            content: attr(data-label);
            font-weight: bold;
            text-align: left;
            margin-right: 1rem;
            color: #858796;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detail Laporan BKU</h1>
            <p class="mb-0 text-gray-600 mt-1">Laporan Periode: <strong><?= date('F Y', mktime(0, 0, 0, $laporan['bulan'], 1)); ?></strong></p>
        </div>
        <!-- Tombol Kembali (Tombol Edit dan Cetak dihapus) -->
        <a href="<?= site_url('LaporanBkuBulanan'); ?>" class="btn btn-secondary btn-icon-split btn-sm">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Stat Cards (Ringkasan) -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 stat-card-detail">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp ' . number_format($laporan['total_pendapatan'], 0, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-arrow-down fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 stat-card-detail">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp ' . number_format($laporan['total_pengeluaran'], 0, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-arrow-up fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 stat-card-detail">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo Akhir</div>
                            <div class="h5 mb-0 font-weight-bold <?= ($laporan['saldo_akhir'] < 0) ? 'text-danger' : 'text-gray-800'; ?>">
                                <?= 'Rp ' . number_format($laporan['saldo_akhir'], 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-wallet fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rincian dalam Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <!-- DIUBAH: Tabs disesuaikan untuk Bootstrap 4 -->
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="alokasi-tab" data-toggle="tab" href="#alokasi-pane" role="tab">Rincian Alokasi Dana</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pendapatan-tab" data-toggle="tab" href="#pendapatan-pane" role="tab">Rincian Pendapatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pengeluaran-tab" data-toggle="tab" href="#pengeluaran-pane" role="tab">Rincian Pengeluaran</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <!-- Tab Alokasi -->
                <div class="tab-pane fade show active" id="alokasi-pane" role="tabpanel">
                    <div class="table-responsive-stack">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kategori Pengeluaran</th>
                                    <th class="text-center">Persentase</th>
                                    <th class="text-right">Alokasi</th>
                                    <th class="text-right">Realisasi</th>
                                    <th class="text-right">Sisa Alokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($rincianAlokasi)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Data alokasi tidak ditemukan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($rincianAlokasi as $a): ?>
                                        <tr>
                                            <td data-label="Kategori"><?= esc($a['nama_kategori']); ?></td>
                                            <td data-label="Persentase" class="text-center"><?= number_format($a['persentase_saat_itu'], 2); ?>%</td>
                                            <td data-label="Alokasi" class="text-right"><?= 'Rp ' . number_format($a['jumlah_alokasi'], 0, ',', '.'); ?></td>
                                            <td data-label="Realisasi" class="text-right"><?= 'Rp ' . number_format($a['jumlah_realisasi'], 0, ',', '.'); ?></td>
                                            <td data-label="Sisa Alokasi" class="text-right font-weight-bold <?= ($a['sisa_alokasi'] < 0) ? 'text-danger' : ''; ?>"><?= 'Rp ' . number_format($a['sisa_alokasi'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Pendapatan -->
                <div class="tab-pane fade" id="pendapatan-pane" role="tabpanel">
                    <div class="table-responsive-stack">
                        <table class="table table-striped" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Jenis Pendapatan</th>
                                    <th class="text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-info">
                                    <td data-label="Jenis"><strong>Sisa Saldo Bulan Lalu</strong></td>
                                    <td data-label="Jumlah" class="text-right"><strong><?= 'Rp ' . number_format($laporan['saldo_bulan_lalu'], 0, ',', '.'); ?></strong></td>
                                </tr>
                                <?php if (empty($rincianPendapatan)): ?>
                                    <tr>
                                        <td colspan="2" class="text-center font-italic">Tidak ada pendapatan bulan ini.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($rincianPendapatan as $p): ?>
                                        <tr>
                                            <td data-label="Jenis"><?= esc($p['nama_pendapatan']); ?></td>
                                            <td data-label="Jumlah" class="text-right"><?= 'Rp ' . number_format($p['jumlah'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th class="text-right">TOTAL PENDAPATAN</th>
                                    <th class="text-right"><?= 'Rp ' . number_format($laporan['total_pendapatan'], 0, ',', '.'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Tab Pengeluaran -->
                <div class="tab-pane fade" id="pengeluaran-pane" role="tabpanel">
                    <div class="table-responsive-stack">
                        <table class="table table-striped" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Deskripsi</th>
                                    <th>Kategori</th>
                                    <th class="text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($rincianPengeluaran)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data pengeluaran.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($rincianPengeluaran as $p): ?>
                                        <tr>
                                            <td data-label="Deskripsi"><?= esc($p['deskripsi_pengeluaran']); ?></td>
                                            <td data-label="Kategori"><span class="badge badge-secondary"><?= esc($p['nama_kategori']); ?></span></td>
                                            <td data-label="Jumlah" class="text-right"><?= 'Rp ' . number_format($p['jumlah'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th colspan="2" class="text-right">TOTAL PENGELUARAN</th>
                                    <th class="text-right"><?= 'Rp ' . number_format($laporan['total_pengeluaran'], 0, ',', '.'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>