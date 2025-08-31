<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4">
    <div class="header-section mb-4">
        <h1 class="page-title">Laporan Bumdes Melung</h1>
        <p class="page-subtitle">Pilih jenis laporan yang ingin Anda lihat.</p>
    </div>

    <div class="row g-4 mb-4 justify-content-center">
        <div class="col-lg-4 col-md-6">
            <a href="<?= base_url('bumdes/laporan/kopi') ?>" class="modern-card nav-card">
                <div class="nav-card-icon bg-primary-subtle text-primary">
                    <i class="fas fa-coffee"></i>
                </div>
                <h5 class="nav-card-title">Laporan Kopi</h5>
                <p class="nav-card-subtitle">Rekapitulasi kopi masuk, keluar, dan stok.</p>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="<?= base_url('bumdes/laporan/petani') ?>" class="modern-card nav-card">
                <div class="nav-card-icon bg-success-subtle text-success">
                    <i class="fas fa-users"></i>
                </div>
                <h5 class="nav-card-title">Laporan Petani</h5>
                <p class="nav-card-subtitle">Data lengkap petani terdaftar.</p>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="<?= base_url('bumdes/laporan/aset') ?>" class="modern-card nav-card">
                <div class="nav-card-icon bg-warning-subtle text-warning">
                    <i class="fas fa-tools"></i>
                </div>
                <h5 class="nav-card-title">Laporan Aset</h5>
                <p class="nav-card-subtitle">Data inventaris aset produksi.</p>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="<?= base_url('bumdes/laporan/pariwisata') ?>" class="modern-card nav-card">
                <div class="nav-card-icon bg-info-subtle text-info">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h5 class="nav-card-title">Laporan Pariwisata</h5>
                <p class="nav-card-subtitle">Rekapitulasi data unit pariwisata.</p>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="<?= base_url('bumdes/laporan/umkm') ?>" class="modern-card nav-card">
                <div class="nav-card-icon bg-danger-subtle text-danger">
                    <i class="fas fa-store"></i>
                </div>
                <h5 class="nav-card-title">Laporan UMKM</h5>
                <p class="nav-card-subtitle">Rekapitulasi data unit UMKM.</p>
            </a>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
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