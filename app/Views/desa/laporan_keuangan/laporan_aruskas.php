<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title"><?= esc($title) ?></h1>
            <p class="mb-0 page-subtitle">Silahkan pilih Tahun untuk melihat Arus Kas Desa Melung.</p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Pilih Periode Laporan</h6>
        </div>
        <div class="card-body">
            <form action="<?= current_url() ?>" method="get" class="row g-3 align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="tahun" class="form-label">Tahun Periode:</label>
                    <select name="tahun" id="tahun" class="form-control" required>
                        <option value="">Pilih Tahun</option>
                        <?php if (!empty($daftar_tahun)) : ?>
                            <?php foreach ($daftar_tahun as $t) : ?>
                                <option value="<?= $t['tahun'] ?>" <?= (isset($tahunDipilih) && $t['tahun'] == $tahunDipilih) ? 'selected' : '' ?>>
                                    <?= $t['tahun'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-eye fa-sm mr-2"></i>Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($tahunDipilih)) : ?>
        <!-- Desktop View -->
        <div class="card shadow mb-4 d-none d-lg-block">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-line mr-2"></i>Laporan Arus Kas Tahun <?= $tahunDipilih ?></h6>
            </div>
            <div class="card-body">
                <?php if (isset($tahunDipilih)) : ?>
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class=" font-weight-bold text-success text-uppercase mb-1">
                                                <i class="fas fa-arrow-down mr-1"></i>Arus Kas Masuk
                                            </div>
                                            <div class="list-group list-group-flush mt-3">
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    Pendapatan Operasional Utama
                                                    <span class="font-weight-bold text-dark">Rp <?= number_format($pendapatanUtama, 0, ',', '.') ?></span>
                                                </div>
                                                <?php foreach ($komponenMasuk as $km) : ?>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                        <?= esc($km['nama_komponen']) ?>
                                                        <span class="font-weight-bold text-dark">Rp <?= number_format((int) $km['jumlah'], 0, ',', '.') ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                    <span class="text-success font-weight-bold"><i class="fas fa-calculator mr-2"></i>TOTAL KAS MASUK</span>
                                    <span class="h5 mb-0 font-weight-bold text-success">Rp <?= number_format($totalKasMasuk, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="font-weight-bold text-danger text-uppercase mb-1">
                                                <i class="fas fa-arrow-up mr-1"></i>Arus Kas Keluar
                                            </div>
                                            <div class="list-group list-group-flush mt-3">
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    Pembelian Barang dan Jasa
                                                    <span class="font-weight-bold text-dark">(Rp <?= number_format($pembelianBarang, 0, ',', '.') ?>)</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    Pembayaran Beban Gaji
                                                    <span class="font-weight-bold text-dark">(Rp <?= number_format($bebanGaji, 0, ',', '.') ?>)</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    Pendapatan Asli Desa
                                                    <span class="font-weight-bold text-dark">(Rp <?= number_format($pad, 0, ',', '.') ?>)</span>
                                                </div>
                                                <?php foreach ($komponenKeluar as $kk) : ?>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                        <?= esc($kk['nama_komponen']) ?>
                                                        <span class="font-weight-bold text-dark">(Rp <?= number_format((int) $kk['jumlah'], 0, ',', '.') ?>)</span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                    <span class="text-danger font-weight-bold"><i class="fas fa-calculator mr-2"></i>TOTAL KAS KELUAR</span>
                                    <span class="h5 mb-0 font-weight-bold text-danger">(Rp <?= number_format($totalKasKeluar, 0, ',', '.') ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Saldo Akhir Tahun <?= $tahunDipilih ?>
                                            </div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                                Rp <?= number_format($saldoAkhir, 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-balance-scale fa-3x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
            </div>
            <div class="card-footer text-center bg-light border-top">
                <div class="text-muted small">SALDO AKHIR</div>
                <div class="h4 m-0 font-weight-bold text-gray-800">
                    Rp <?= number_format($saldoAkhir, 0, ',', '.') ?>
                </div>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="d-lg-none">
            <!-- Header Info Mobile -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line mr-2"></i>Laporan Arus Kas Tahun <?= $tahunDipilih ?>
                </h6>
            </div>

            <!-- Tabs untuk Mobile -->
            <ul class="nav nav-tabs nav-fill mb-0" id="arusKasTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="masuk-tab" data-toggle="tab" href="#masuk-pane" role="tab">
                        <i class="fas fa-arrow-down text-success mr-1"></i>
                        <span class="d-none d-sm-inline">Kas </span>Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="keluar-tab" data-toggle="tab" href="#keluar-pane" role="tab">
                        <i class="fas fa-arrow-up text-danger mr-1"></i>
                        <span class="d-none d-sm-inline">Kas </span>Keluar
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="arusKasTabContent">
                <!-- Tab Kas Masuk -->
                <div class="tab-pane fade show active" id="masuk-pane" role="tabpanel">
                    <div class="card shadow border-top-0" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <div class="card-body px-2 px-sm-3">
                            <div class="mb-3">
                                <small class="text-muted text-uppercase font-weight-bold">Komponen Kas Masuk:</small>
                            </div>

                            <!-- Item Kas Masuk -->
                            <div class="row mb-2 py-2 border-bottom">
                                <div class="col-7 col-sm-8">
                                    <small class="text-gray-700 d-block">Pendapatan Operasional Utama</small>
                                </div>
                                <div class="col-5 col-sm-4 text-right">
                                    <small class="font-weight-bold text-success d-block">
                                        Rp <?= number_format($pendapatanUtama, 0, ',', '.') ?>
                                    </small>
                                </div>
                            </div>

                            <?php foreach ($komponenMasuk as $km) : ?>
                                <div class="row mb-2 py-2 border-bottom">
                                    <div class="col-7 col-sm-8">
                                        <small class="text-gray-700 d-block"><?= esc($km['nama_komponen']) ?></small>
                                    </div>
                                    <div class="col-5 col-sm-4 text-right">
                                        <small class="font-weight-bold text-success d-block">
                                            Rp <?= number_format((int) $km['jumlah'], 0, ',', '.') ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="card-footer bg-success text-white">
                            <div class="row align-items-center">
                                <div class="col-6 col-sm-7">
                                    <span class="font-weight-bold">
                                        <i class="fas fa-calculator mr-2"></i>
                                        <span class="d-none d-sm-inline">TOTAL </span>KAS MASUK
                                    </span>
                                </div>
                                <div class="col-6 col-sm-5 text-right">
                                    <span class="font-weight-bold">Rp <?= number_format($totalKasMasuk, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Kas Keluar -->
                <div class="tab-pane fade" id="keluar-pane" role="tabpanel">
                    <div class="card shadow border-top-0" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <div class="card-body px-2 px-sm-3">
                            <div class="mb-3">
                                <small class="text-muted text-uppercase font-weight-bold">Komponen Kas Keluar:</small>
                            </div>

                            <!-- Item Kas Keluar -->
                            <div class="row mb-2 py-2 border-bottom">
                                <div class="col-7 col-sm-8">
                                    <small class="text-gray-700 d-block">Pembelian Barang dan Jasa</small>
                                </div>
                                <div class="col-5 col-sm-4 text-right">
                                    <small class="font-weight-bold text-danger d-block">
                                        (Rp <?= number_format($pembelianBarang, 0, ',', '.') ?>)
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-2 py-2 border-bottom">
                                <div class="col-7 col-sm-8">
                                    <small class="text-gray-700 d-block">Pembayaran Beban Gaji</small>
                                </div>
                                <div class="col-5 col-sm-4 text-right">
                                    <small class="font-weight-bold text-danger d-block">
                                        (Rp <?= number_format($bebanGaji, 0, ',', '.') ?>)
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-2 py-2 border-bottom">
                                <div class="col-7 col-sm-8">
                                    <small class="text-gray-700 d-block">Pendapatan Asli Desa</small>
                                </div>
                                <div class="col-5 col-sm-4 text-right">
                                    <small class="font-weight-bold text-danger d-block">
                                        (Rp <?= number_format($pad, 0, ',', '.') ?>)
                                    </small>
                                </div>
                            </div>

                            <?php foreach ($komponenKeluar as $kk) : ?>
                                <div class="row mb-2 py-2 border-bottom">
                                    <div class="col-7 col-sm-8">
                                        <small class="text-gray-700 d-block"><?= esc($kk['nama_komponen']) ?></small>
                                    </div>
                                    <div class="col-5 col-sm-4 text-right">
                                        <small class="font-weight-bold text-danger d-block">
                                            (Rp <?= number_format((int) $kk['jumlah'], 0, ',', '.') ?>)
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="card-footer bg-danger text-white">
                            <div class="row align-items-center">
                                <div class="col-6 col-sm-7">
                                    <span class="font-weight-bold">
                                        <i class="fas fa-calculator mr-2"></i>
                                        <span class="d-none d-sm-inline">TOTAL </span>KAS KELUAR
                                    </span>
                                </div>
                                <div class="col-6 col-sm-5 text-right">
                                    <span class="font-weight-bold">(Rp <?= number_format($totalKasKeluar, 0, ',', '.') ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saldo Akhir Mobile -->
            <div class="card shadow mt-3">
                <div class="card-footer text-center bg-light py-4">
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-2">
                            <i class="fas fa-balance-scale fa-2x text-primary"></i>
                        </div>
                        <h5 class="mb-1 font-weight-bold text-gray-700">SALDO AKHIR</h5>
                        <h4 class="m-0 font-weight-bold text-primary">
                            Rp <?= number_format($saldoAkhir, 0, ',', '.') ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards untuk Mobile -->
        <div class="d-lg-none mt-4">
            <div class="row">
                <div class="col-6 mb-3">
                    <div class="card border-left-success">
                        <div class="card-body py-3 text-center">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Masuk</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Rp <?= number_format($totalKasMasuk, 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="card border-left-danger">
                        <div class="card-body py-3 text-center">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Keluar</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                (Rp <?= number_format($totalKasKeluar, 0, ',', '.') ?>)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>