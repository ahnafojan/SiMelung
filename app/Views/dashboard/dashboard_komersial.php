<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 text-dark font-weight-bolder">Dashboard Komersial</h1>
            <p class="text-secondary medium">Monitor dan analisis operasional komersial Bumdes Melung secara real-time.</p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>Filter Data
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#"><i class="fas fa-redo fa-sm fa-fw mr-2 text-gray-400"></i> Reset Filter</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-save fa-sm fa-fw mr-2 text-gray-400"></i> Save Filter</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="get" action="<?= base_url('dashboard/dashboard_komersial') ?>">
                <div class="row align-items-end">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="bulan" class="form-label text-gray-700 font-weight-bold small">Pilih Bulan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <select name="bulan" id="bulan" class="form-control">
                                <?php
                                $namaBulan = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember'
                                ];
                                foreach ($namaBulan as $num => $nama) : ?>
                                    <option value="<?= $num ?>" <?= ($bulan == $num) ? 'selected' : '' ?>><?= $nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="tahun" class="form-label text-gray-700 font-weight-bold small">Pilih Tahun</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white"><i class="fas fa-clock"></i></span>
                            </div>
                            <select name="tahun" id="tahun" class="form-control">
                                <?php foreach ($years as $y) : ?>
                                    <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-2"></i> Terapkan Filter
                        </button>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?= base_url('dashboard/dashboard_komersial') ?>" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-sync-alt mr-2"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kopi Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($totalMasuk ?? 0, 2, ',', '.') ?> <span class="text-gray-600 small">Kg</span>
                            </div>
                            <div class="mt-2 text-success small font-weight-bold">
                                <i class="fas fa-arrow-up mr-1"></i> Periode: <?= $namaBulan[$bulan] ?? '' ?> <?= $tahun ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Kopi Keluar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($totalKeluar ?? 0, 2, ',', '.') ?> <span class="text-gray-600 small">Kg</span>
                            </div>
                            <div class="mt-2 text-danger small font-weight-bold">
                                <i class="fas fa-arrow-down mr-1"></i> Distribusi & Penjualan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck-loading fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sisa Kopi Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stokBersihGlobal ?? 0, 2, ',', '.') ?> <span class="text-gray-600 small">Kg</span>
                            </div>
                            <?php
                            $persentaseStok = ($totalMasukGlobal > 0) ? (($stokBersihGlobal / $totalMasukGlobal) * 100) : 0;
                            $statusColor = $persentaseStok > 50 ? 'success' : ($persentaseStok > 20 ? 'warning' : 'danger');
                            ?>
                            <div class="mt-2 text-<?= $statusColor ?> small font-weight-bold">
                                <i class="fas fa-chart-pie mr-1"></i> <?= number_format($persentaseStok, 1) ?>% dari total masuk
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Petani Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($totalPetaniGlobal ?? 0, 0, ',', '.') ?> <span class="text-gray-600 small">Orang</span>
                            </div>
                            <div class="mt-2 text-primary small font-weight-bold">
                                <i class="fas fa-users mr-1"></i> Mitra Terdaftar
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Aset Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($totalAsetGlobal ?? 0, 0, ',', '.') ?> <span class="text-gray-600 small">Unit</span>
                            </div>
                            <div class="mt-2 text-warning small font-weight-bold">
                                <i class="fas fa-tools mr-1"></i> Peralatan & Fasilitas
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cubes fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Tingkat Distribusi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($tingkatDistribusiGlobal ?? 0, 1, ',', '.') ?>%
                            </div>
                            <div class="mt-2 text-dark small font-weight-bold">
                                <i class="fas fa-shipping-fast mr-1"></i> Dari Seluruh Periode
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // ===== STATUS OPERASIONAL (GLOBAL) =====
        $stokPersen = ($totalMasukGlobal > 0)
            ? ($stokBersihGlobal / $totalMasukGlobal) * 100
            : 0;

        // Default
        $statusText  = 'Sistem Normal';
        $statusColor = 'success';
        $statusIcon  = 'fa-check-circle';
        $borderColor = 'secondary';

        // Rules
        if ($totalMasukGlobal == 0 && $totalKeluarGlobal == 0) {
            $statusText  = 'Tidak Ada Aktivitas';
            $statusColor = 'secondary';
            $statusIcon  = 'fa-ban';
            $borderColor = 'secondary';
        } elseif ($totalKeluarGlobal > $totalMasukGlobal) {
            $statusText  = 'Stok Defisit';
            $statusColor = 'danger';
            $statusIcon  = 'fa-exclamation-triangle';
            $borderColor = 'danger';
        } elseif ($stokPersen < 10) {
            $statusText  = 'Stok Menipis';
            $statusColor = 'danger';
            $statusIcon  = 'fa-arrow-down';
            $borderColor = 'danger';
        } elseif ($stokPersen > 80) {
            $statusText  = 'Stok Berlebih';
            $statusColor = 'warning';
            $statusIcon  = 'fa-box';
            $borderColor = 'warning';
        } elseif (($tingkatDistribusiGlobal ?? 0) > 95) {
            $statusText  = 'Distribusi Tinggi';
            $statusColor = 'info';
            $statusIcon  = 'fa-shipping-fast';
            $borderColor = 'info';
        }
        ?>

        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-left-<?= $borderColor ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-8">

                            <!-- TITLE -->
                            <div class="text-xs font-weight-bold text-<?= $borderColor ?> text-uppercase mb-1">
                                Status Operasional (Real-time)
                            </div>

                            <div class="row">
                                <!-- STATUS -->
                                <div class="col-6">
                                    <div class="text-gray-800 font-weight-bold">
                                        <i class="fas <?= $statusIcon ?> text-<?= $statusColor ?> mr-2"></i>
                                        <?= $statusText ?>
                                    </div>
                                    <small class="text-gray-600">
                                        Update: <?= date('d M Y, H:i') ?>
                                    </small>
                                </div>

                                <!-- INFO -->
                                <div class="col-6">
                                    <div class="text-gray-800">
                                        <strong>Periode:</strong> <?= $namaBulan[$bulan] ?? '' ?> <?= $tahun ?>
                                    </div>
                                    <div class="text-gray-800">
                                        <strong>Stok (Total):</strong>
                                        <?= number_format($stokBersihGlobal ?? 0, 2, ',', '.') ?> Kg
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ICON -->
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-<?= $borderColor ?> fa-spin-slow"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area mr-2"></i>Grafik Tren Kopi Masuk & Keluar
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="chartDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="chartDropdown">
                            <div class="dropdown-header">Chart Options:</div>
                            <a class="dropdown-item" href="#"><i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i> Download Chart</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-expand fa-sm fa-fw mr-2 text-gray-400"></i> Full Screen</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="kopiChart" style="height: 320px;"></canvas>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6 text-center">
                            <div class="text-success"><i class="fas fa-arrow-up"></i> <span class="font-weight-bold">Kopi Masuk</span></div>
                            <div class="text-xs text-gray-500">Total periode ini</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="text-danger"><i class="fas fa-arrow-down"></i> <span class="font-weight-bold">Kopi Keluar</span></div>
                            <div class="text-xs text-gray-500">Distribusi & penjualan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie mr-2"></i>Jenis Kopi yang tercatat
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="pieChartDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="pieChartDropdown">
                            <div class="dropdown-header">View Options:</div>
                            <a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> Show Data Table</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-chart-bar fa-sm fa-fw mr-2 text-gray-400"></i> Bar Chart View</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php
                    $labelsArr = json_decode($jenisLabels ?? '[]', true);
                    $totalsArr = json_decode($jenisTotals ?? '[]', true);

                    $labelsArr = is_array($labelsArr) ? $labelsArr : [];
                    $totalsArr = is_array($totalsArr) ? $totalsArr : [];

                    $totalKg = 0;
                    foreach ($totalsArr as $v) {
                        $totalKg += (int) $v;
                    }

                    $isEmpty = empty($totalsArr) || $totalKg <= 0;
                    ?>

                    <?php if ($isEmpty): ?>
                        <!-- EMPTY STATE (tanpa CSS tambahan, pakai bootstrap) -->
                        <div class="text-center py-4">
                            <div class="mb-2">
                                <i class="fas fa-seedling fa-2x text-gray-400"></i>
                            </div>

                            <div class="h4 font-weight-bold text-gray-800 mb-0">
                                0 <span class="small text-gray-600">Kg</span>
                            </div>
                            <div class="small text-gray-600 mb-3">
                                Total distribusi jenis kopi
                            </div>

                            <div class="alert alert-light border small text-left mb-3">
                                <div class="font-weight-bold text-gray-800 mb-1">
                                    Tidak ada data pada periode <?= $namaBulan[$bulan] ?? '' ?> <?= $tahun ?>
                                </div>
                                <div class="text-gray-600">
                                    Alasan: belum ada data <strong>kopi masuk</strong> pada periode ini, sehingga distribusi per jenis belum bisa dihitung.
                                </div>
                            </div>

                            <a href="<?= base_url('dashboard/dashboard_komersial') ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-sync-alt mr-1"></i> Reset Filter
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- CHART -->
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="jenisKopiChart" style="height: 245px;"></canvas>
                        </div>

                        <!-- LEGEND OTOMATIS DARI DATA -->
                        <div class="mt-3 small">
                            <?php foreach ($labelsArr as $i => $lbl): ?>
                                <div class="d-flex justify-content-between border-bottom py-1">
                                    <div class="text-gray-700">
                                        <?= esc($lbl) ?>
                                    </div>
                                    <div class="font-weight-bold text-gray-800">
                                        <?= number_format((int)($totalsArr[$i] ?? 0), 0, ',', '.') ?> Kg
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="d-flex justify-content-between pt-2">
                                <div class="text-gray-600 font-weight-bold">Total</div>
                                <div class="text-gray-800 font-weight-bold"><?= number_format($totalKg, 0, ',', '.') ?> Kg</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle mr-2"></i>Informasi Dashboard
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-success text-white mr-3"><i class="fas fa-leaf"></i></div>
                        <div>
                            <div class="font-weight-bold text-gray-800">Kualitas Premium</div>
                            <div class="text-gray-600 small">Fokus pada kualitas biji kopi terbaik</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary text-white mr-3"><i class="fas fa-handshake"></i></div>
                        <div>
                            <div class="font-weight-bold text-gray-800">Kemitraan Petani</div>
                            <div class="text-gray-600 small">Mendukung kesejahteraan petani lokal</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-info text-white mr-3"><i class="fas fa-chart-line"></i></div>
                        <div>
                            <div class="font-weight-bold text-gray-800">Analisis Real-time</div>
                            <div class="text-gray-600 small">Monitoring operasional berbasis data</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="statusDetailModal" tabindex="-1" role="dialog" aria-labelledby="statusDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusDetailModalLabel">
                    <i class="fas fa-info-circle mr-2"></i>Detail Status Operasional
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Status</th>
                                <th>Kondisi</th>
                                <th>Deskripsi</th>
                                <th>Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge badge-success">Normal</span></td>
                                <td>Stok 10-80%, Distribusi seimbang</td>
                                <td>Sistem berjalan optimal</td>
                                <td>Pertahankan operasional</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-info">Distribusi Tinggi</span></td>
                                <td>Distribusi > 95%</td>
                                <td>Penjualan sangat aktif</td>
                                <td>Siapkan stok tambahan</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-warning">Stok Berlebih</span></td>
                                <td>Stok > 80%</td>
                                <td>Stok menumpuk terlalu lama</td>
                                <td>Tingkatkan pemasaran & distribusi</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-warning">Stok Menipis</span></td>
                                <td>Stok < 10%</td>
                                <td>Stok hampir habis</td>
                                <td>Segera lakukan restocking</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-danger">Tidak Ada Aktivitas</span></td>
                                <td>Masuk = 0, Keluar = 0</td>
                                <td>Tidak ada transaksi</td>
                                <td>Periksa sistem & aktivasi operasional</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-danger">Stok Defisit</span></td>
                                <td>Keluar > Masuk, Stok < 0</td>
                                <td>Overselling atau data error</td>
                                <td>Audit stok & perbaiki data</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-danger">Distribusi Tanpa Stok</span></td>
                                <td>Keluar > 0, Masuk = 0</td>
                                <td>Distribusi tanpa input baru</td>
                                <td>Periksa data & sumber kopi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-lightbulb mr-2"></i>Tips Monitoring:</h6>
                    <ul class="mb-0">
                        <li><strong>Normal:</strong> Stok ideal 20-60% untuk fleksibilitas operasional.</li>
                        <li><strong>Peringatan:</strong> Monitor harian jika status 'warning' muncul.</li>
                        <li><strong>Darurat:</strong> Tindakan segera diperlukan jika status 'danger'.</li>
                        <li><strong>Trend:</strong> Pantau pola bulanan untuk planning yang lebih baik.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-download mr-1"></i> Export Report
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .card-hover {
        transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fa-spin-slow {
        animation: fa-spin 3s infinite linear;
    }

    /* Remove hover effect on smaller screens for better UX */
    @media (max-width: 768px) {
        .card-hover:hover {
            transform: none;
        }
    }
</style>

<?= $this->endSection() ?>