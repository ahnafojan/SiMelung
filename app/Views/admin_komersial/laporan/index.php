<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <h1 class="page-title">Laporan Komersial Bumdes Melung</h1>
        <p class="page-subtitle">Pilih jenis laporan yang ingin Anda lihat.</p>
    </div>

    <div class="row g-3 g-lg-4 mb-4">

        <div class="col-12 col-md-6 col-xl-4">
            <div class="modern-stats-card">
                <div class="card-glow"></div>
                <div class="stats-content">
                    <div class="stats-header">
                        <div class="stats-icon-container coffee">
                            <div class="icon-bg"></div>
                            <i class="fas fa-coffee"></i>
                        </div>
                        <div class="stats-badge">
                            <span class="badge-dot active"></span>
                            <span class="badge-text">Aktif</span>
                        </div>
                    </div>
                    <div class="stats-main">
                        <div class="stats-number">
                            <span class="counter" data-target="<?= $totalStokKopi ?? 0 ?>">0</span>
                            <span class="stats-unit">Kg</span>
                        </div>
                        <h3 class="stats-title">Total Stok Kopi Bumdes</h3>
                        <p class="stats-subtitle">Stok kopi tersedia saat ini</p>
                    </div>
                    <div class="stats-footer">
                        <div class="stats-indicator">
                            <div class="indicator-bar" data-percentage="30"></div>
                        </div>
                        <div class="stats-meta">
                            <span class="meta-text">Kapasitas 30%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="modern-stats-card">
                <div class="card-glow"></div>
                <div class="stats-content">
                    <div class="stats-header">
                        <div class="stats-icon-container users">
                            <div class="icon-bg"></div>
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-badge">
                            <span class="badge-dot active"></span>
                            <span class="badge-text">Aktif</span>
                        </div>
                    </div>
                    <div class="stats-main">
                        <div class="stats-number">
                            <span class="counter" data-target="<?= count($petaniList) ?>">0</span>
                            <span class="stats-unit">Petani</span>
                        </div>
                        <h3 class="stats-title">Total Petani Terdaftar</h3>
                        <p class="stats-subtitle">Petani aktif dalam sistem</p>
                    </div>
                    <div class="stats-footer">
                        <div class="stats-indicator">
                            <div class="indicator-bar" data-percentage="50"></div>
                        </div>
                        <div class="stats-meta">
                            <span class="meta-text">Partisipasi 50%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="modern-stats-card">
                <div class="card-glow"></div>
                <div class="stats-content">
                    <div class="stats-header">
                        <div class="stats-icon-container tools">
                            <div class="icon-bg"></div>
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="stats-badge">
                            <span class="badge-dot warning"></span>
                            <span class="badge-text">Normal</span>
                        </div>
                    </div>
                    <div class="stats-main">
                        <div class="stats-number">
                            <span class="counter" data-target="<?= $totalAset ?? 0 ?>">0</span>
                            <span class="stats-unit">Unit</span>
                        </div>
                        <h3 class="stats-title">Total Aset Terdaftar</h3>
                        <p class="stats-subtitle">Aset produksi tersedia</p>
                    </div>
                    <div class="stats-footer">
                        <div class="stats-indicator">
                            <div class="indicator-bar" data-percentage="65"></div>
                        </div>
                        <div class="stats-meta">
                            <span class="meta-text">Utilisasi 65%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3 g-lg-4 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <a href="<?= base_url('admin-komersial/laporan/kopi') ?>" class="modern-card nav-card h-100">
                <div class="nav-card-icon bg-primary-subtle text-primary">
                    <i class="fas fa-coffee"></i>
                </div>
                <h5 class="nav-card-title">Laporan Kopi</h5>
                <p class="nav-card-subtitle">Rekapitulasi kopi masuk, keluar, dan stok.</p>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="<?= base_url('admin-komersial/laporan/petani') ?>" class="modern-card nav-card h-100">
                <div class="nav-card-icon bg-success-subtle text-success">
                    <i class="fas fa-users"></i>
                </div>
                <h5 class="nav-card-title">Laporan Petani</h5>
                <p class="nav-card-subtitle">Data lengkap petani terdaftar.</p>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="<?= base_url('admin-komersial/laporan/aset') ?>" class="modern-card nav-card h-100">
                <div class="nav-card-icon bg-warning-subtle text-warning">
                    <i class="fas fa-tools"></i>
                </div>
                <h5 class="nav-card-title">Laporan Aset</h5>
                <p class="nav-card-subtitle">Data inventaris aset produksi.</p>
            </a>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
    <div id="successToast" class="toast" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Berhasil</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>
<?= $this->endSection() ?>