<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <h1 class="page-title">Laporan Komersial Bumdes Melung</h1>
        <p class="page-subtitle">Pilih jenis laporan yang ingin Anda lihat.</p>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-4 col-md-6">
            <div class="modern-stats-card">
                <div class="card-glow"></div>
                <div class="stats-content">
                    <div class="stats-header">
                        <div class="stats-icon-container coffee">
                            <div class="icon-bg"></div>
                            <i class="fas fa-coffee"></i>
                        </div>
                        <div class="stats-badge">
                            <span class="badge-dot"></span>
                            <span class="badge-text">Aktif</span>
                        </div>
                    </div>

                    <div class="stats-main">
                        <div class="stats-number">
                            <?= $totalStokKopi ?? 0 ?>
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

        <div class="col-xl-4 col-md-6">
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
                            <?= count($petaniList) ?>
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

        <div class="col-xl-4 col-md-6">
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
                            <?= $totalAset ?? 0 ?>
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

    <!-- Navigation Buttons Section -->
    <div class="row g-4 mb-4 justify-content-center">
        <div class="col-lg-4 col-md-6">
            <a href="<?= base_url('admin-komersial/laporan/kopi') ?>" class="modern-card nav-card">
                <div class="nav-card-icon bg-primary-subtle text-primary">
                    <i class="fas fa-coffee"></i>
                </div>
                <h5 class="nav-card-title">Laporan Kopi</h5>
                <p class="nav-card-subtitle">Rekapitulasi kopi masuk, keluar, dan stok.</p>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="<?= base_url('admin-komersial/laporan/petani') ?>" class="modern-card nav-card">
                <div class="nav-card-icon bg-success-subtle text-success">
                    <i class="fas fa-users"></i>
                </div>
                <h5 class="nav-card-title">Laporan Petani</h5>
                <p class="nav-card-subtitle">Data lengkap petani terdaftar.</p>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="<?= base_url('admin-komersial/laporan/aset') ?>" class="modern-card nav-card">
                <div class="nav-card-icon bg-warning-subtle text-warning">
                    <i class="fas fa-tools"></i>
                </div>
                <h5 class="nav-card-title">Laporan Aset</h5>
                <p class="nav-card-subtitle">Data inventaris aset produksi.</p>
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

<style>
    /* Modern Clean Stats Cards */
    .modern-stats-card {
        position: relative;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        padding: 0;
        min-height: 220px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .modern-stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: rgba(99, 102, 241, 0.2);
    }

    .card-glow {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.02) 0%, rgba(168, 85, 247, 0.02) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modern-stats-card:hover .card-glow {
        opacity: 1;
    }

    .stats-content {
        position: relative;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        z-index: 2;
    }

    .stats-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .stats-icon-container {
        position: relative;
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.3s ease;
    }

    .icon-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .stats-icon-container.coffee {
        background: rgba(251, 191, 36, 0.1);
        color: #f59e0b;
    }

    .stats-icon-container.coffee .icon-bg {
        background: rgba(251, 191, 36, 0.1);
    }

    .stats-icon-container.users {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .stats-icon-container.users .icon-bg {
        background: rgba(34, 197, 94, 0.1);
    }

    .stats-icon-container.tools {
        background: rgba(168, 85, 247, 0.1);
        color: #a855f7;
    }

    .stats-icon-container.tools .icon-bg {
        background: rgba(168, 85, 247, 0.1);
    }

    .modern-stats-card:hover .stats-icon-container .icon-bg {
        transform: scale(1.1);
    }

    .stats-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(241, 245, 249, 0.8);
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.6);
    }

    .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #94a3b8;
    }

    .badge-dot.active {
        background: #22c55e;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
    }

    .badge-dot.warning {
        background: #f59e0b;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
    }

    .badge-text {
        font-size: 11px;
        font-weight: 500;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-main {
        flex: 1;
        margin-bottom: 20px;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        color: #1e293b;
        margin-bottom: 8px;
        letter-spacing: -0.025em;
    }

    .stats-unit {
        font-size: 1rem;
        font-weight: 500;
        color: #64748b;
        margin-left: 4px;
    }

    .stats-title {
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        margin: 0 0 6px 0;
        line-height: 1.4;
    }

    .stats-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    .stats-footer {
        margin-top: auto;
    }

    .stats-indicator {
        height: 3px;
        background: #f1f5f9;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 12px;
        position: relative;
    }

    .indicator-bar {
        height: 100%;
        background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 2px;
        width: 0;
        transition: width 2s cubic-bezier(0.4, 0, 0.2, 1);
        animation: loadBar 2s ease-out 0.5s forwards;
    }

    .stats-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .meta-text {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
    }

    /* Animation for indicator bars */
    @keyframes loadBar {
        to {
            width: var(--percentage, 0%);
        }
    }

    .indicator-bar[data-percentage="50"] {
        --percentage: 50%;
    }

    .indicator-bar[data-percentage="30"] {
        --percentage: 30%;
    }

    .indicator-bar[data-percentage="65"] {
        --percentage: 65%;
    }

    /* Card entrance animation */
    .modern-stats-card {
        animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        animation-fill-mode: both;
    }

    .modern-stats-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .modern-stats-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .modern-stats-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(24px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .modern-stats-card {
            min-height: 200px;
        }

        .stats-content {
            padding: 20px;
        }

        .stats-number {
            font-size: 2rem;
        }

        .stats-icon-container {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }
    }

    /* Micro interactions */
    .stats-icon-container i {
        transition: transform 0.2s ease;
    }

    .modern-stats-card:hover .stats-icon-container i {
        transform: scale(1.05);
    }

    .stats-badge {
        transition: all 0.2s ease;
    }

    .modern-stats-card:hover .stats-badge {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(99, 102, 241, 0.2);
    }
</style>

<?= $this->endSection() ?>