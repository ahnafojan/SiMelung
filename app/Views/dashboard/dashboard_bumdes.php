<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4 bg-light min-vh-100">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 text-dark font-weight-bolder">Dashboard Bumdes</h1>
            <p class="text-secondary medium">Ringkasan operasional dan visualisasi Admin Komersial, Pariwisata, UMKM.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-lg">
        <div class="card-body py-3">
            <form method="get" action="<?= base_url('dashboard/dashboard_bumdes') ?>">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="bulan" class="form-label text-muted small mb-1">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control form-control-sm rounded-pill">
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
                            // Baris 38 yang error kemungkinan ada di sini
                            foreach ($namaBulan as $num => $nama): ?>
                                <option value="<?= $num ?>" <?= ($bulan == $num) ? 'selected' : '' ?>>
                                    <?= $nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tahun" class="form-label text-muted small mb-1">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control form-control-sm rounded-pill">
                            <?php foreach ($years as $y): ?>
                                <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill shadow-sm mt-3 mt-md-0">
                            <i class="fas fa-filter me-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kopi Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($totalMasuk ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-box fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Kopi Keluar</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($totalKeluar ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-truck-loading fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Stok Bersih</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($stokBersih ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-balance-scale fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Petani Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalPetani ?? 0 ?> <span class="text-muted small">Orang</span></div>
                        </div>
                        <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Aset Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalAset ?? 0 ?> <span class="text-muted small">Unit</span></div>
                        </div>
                        <i class="fas fa-cubes fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">Total Admin</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalUser ?? 0 ?> <span class="text-muted small">Orang</span></div>
                        </div>
                        <i class="fas fa-user-shield fa-2x text-purple opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100 rounded-lg animated--grow-in">
                <div class="card-header bg-white py-3 border-0 rounded-top-lg d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Tren Kopi Masuk & Keluar</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height:300px;">
                        <canvas id="kopiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100 rounded-lg animated--grow-in">
                <div class="card-header bg-white py-3 border-0 rounded-top-lg d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Stok per Jenis Kopi</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="chart-pie pt-4 pb-2" style="position: relative; height:250px;">
                        <canvas id="jenisKopiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<?= $this->endSection() ?>