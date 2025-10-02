<?php
// Ambil service URI sekali saja di atas
$uri = service('uri');

// [BARU] Helper function untuk menyederhanakan logika penentuan menu aktif
function is_sidebar_active(array $segments)
{
    $uri = service('uri');
    $current_segment = $uri->getSegment(1);
    return in_array($current_segment, $segments);
}
?>

<style>
    /* Transisi halus untuk sidebar toggle */
    #wrapper #sidebar {
        transition: margin-left 0.3s ease-in-out;
    }

    /* Style dasar untuk link di sidebar */
    .sidebar .nav-link {
        display: flex;
        align-items: center;
        padding: 0.9rem 1.25rem;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.7);
        border-left: 4px solid transparent;
        transition: all 0.2s ease;
    }

    /* Efek hover yang lebih baik */
    .sidebar .nav-link:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.05);
        border-left-color: #4e73df;
    }

    /* Style untuk menu aktif */
    .sidebar .nav-link.active {
        color: #fff;
        font-weight: 600;
        background-color: rgba(0, 0, 0, 0.2);
        border-left-color: #4e73df;
    }

    /* Indikator panah dropdown */
    .sidebar .nav-link .arrow {
        margin-left: auto;
        transition: transform 0.3s ease;
    }

    /* Rotasi panah saat submenu terbuka */
    .sidebar .nav-link:not(.collapsed) .arrow {
        transform: rotate(-90deg);
    }

    /* Styling untuk submenu */
    .sidebar .collapse .nav-link {
        padding-left: 3rem;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
        position: relative;
    }

    .sidebar .collapse .nav-link::before {
        content: '';
        position: absolute;
        left: 1.75rem;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
    }

    .sidebar .collapse .nav-link:hover,
    .sidebar .collapse .nav-link.active {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar .collapse .nav-link.active::before {
        background-color: #4e73df;
    }

    /* GANTI BAGIAN INI di CSS Anda */
    .sidebar .sidebar-brand {
        display: flex;
        align-items: center;
        /* Memastikan logo dan teks sejajar vertikal */
        justify-content: center;
        padding: 1rem 0.75rem;
        /* Sedikit padding agar tidak terlalu mepet */
        text-decoration: none;
        font-size: 1.25rem;
        /* Sedikit memperbesar font agar seimbang */
        font-weight: 700;
        /* Membuat tulisan lebih tebal dan jelas */
        color: #fff;
        gap: 0.75rem;
        /* Memberi jarak antara logo dan tulisan */
    }
</style>

<aside class="sidebar d-flex flex-column bg-dark">
    <div class="sidebar-header">
        <a class="sidebar-brand" href="<?= site_url('/dashboard/dashboard_keuangan') ?>">
            <div class="sidebar-brand-icon">
                <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="45" height="45" style="object-fit: cover; border-radius: 50%;">
            </div>
            <div class="sidebar-brand-text">Simelung</div>
        </a>
    </div>

    <ul class="nav flex-column flex-grow-1">
        <li class="nav-item">
            <a class="nav-link <?= is_sidebar_active(['dashboard']) ? 'active' : '' ?>" href="<?= site_url('/dashboard/dashboard_keuangan'); ?>">
                <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
            </a>
        </li>

        <hr class="sidebar-divider my-2">

        <li class="nav-item">
            <a class="nav-link <?= is_sidebar_active(['bku-bulanan']) ? 'active' : '' ?>" href="<?= site_url('bku-bulanan'); ?>">
                <i class="fas fa-book fa-fw me-2"></i> BKU Bulanan
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= is_sidebar_active(['bku-tahunan']) ? 'active' : '' ?>" href="<?= site_url('bku-tahunan'); ?>">
                <i class="fas fa-calendar-alt fa-fw me-2"></i> BKU Tahunan
            </a>
        </li>

        <hr class="sidebar-divider my-2">
        <div class="sidebar-heading px-3 text-uppercase fs-7 text-muted">Laporan Keuangan</div>

        <?php $laporanAktif = is_sidebar_active(['laba-rugi', 'arus-kas', 'perubahan-modal', 'neraca-keuangan']); ?>
        <li class="nav-item">
            <a class="nav-link <?= $laporanAktif ? '' : 'collapsed' ?>" data-bs-toggle="collapse" href="#laporan" aria-expanded="<?= $laporanAktif ? 'true' : 'false' ?>">
                <i class="fas fa-file-invoice fa-fw me-2"></i> Laporan <i class="fas fa-angle-left arrow"></i>
            </a>
            <div class="collapse <?= $laporanAktif ? 'show' : '' ?>" id="laporan">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'laba-rugi') ? 'active' : '' ?>" href="<?= site_url('laba-rugi'); ?>">Laba Rugi</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'arus-kas') ? 'active' : '' ?>" href="<?= site_url('arus-kas'); ?>">Arus Kas</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'perubahan-modal') ? 'active' : '' ?>" href="<?= site_url('perubahan-modal'); ?>">Perubahan Modal</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'neraca-keuangan') ? 'active' : '' ?>" href="<?= site_url('neraca-keuangan'); ?>">Neraca Keuangan</a></li>
                </ul>
            </div>
        </li>

        <?php $masterDataAktif = is_sidebar_active(['master-pendapatan', 'master-kategori', 'master-laba-rugi', 'master-arus-kas', 'master-perubahan-modal', 'master-neraca']); ?>
        <li class="nav-item">
            <a class="nav-link <?= $masterDataAktif ? '' : 'collapsed' ?>" data-bs-toggle="collapse" href="#masterdata" aria-expanded="<?= $masterDataAktif ? 'true' : 'false' ?>">
                <i class="fas fa-database fa-fw me-2"></i> Master Data <i class="fas fa-angle-left arrow"></i>
            </a>
            <div class="collapse <?= $masterDataAktif ? 'show' : '' ?>" id="masterdata">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'master-pendapatan') ? 'active' : '' ?>" href="<?= site_url('master-pendapatan'); ?>">Pendapatan</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'master-kategori') ? 'active' : '' ?>" href="<?= site_url('master-kategori'); ?>">Kategori Pengeluaran</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'master-laba-rugi') ? 'active' : '' ?>" href="<?= site_url('master-laba-rugi'); ?>">Komponen Laba Rugi</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'master-arus-kas') ? 'active' : '' ?>" href="<?= site_url('master-arus-kas'); ?>">Komponen Arus Kas</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'master-perubahan-modal') ? 'active' : '' ?>" href="<?= site_url('master-perubahan-modal'); ?>">Komponen Perubahan Modal</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($uri->getSegment(1) == 'master-neraca') ? 'active' : '' ?>" href="<?= site_url('master-neraca'); ?>">Komponen Neraca</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= is_sidebar_active(['pengaturan-keuangan']) ? 'active' : '' ?>" href="<?= site_url('pengaturan-keuangan'); ?>">
                <i class="fas fa-cog fa-fw me-2"></i> Tanda Tangan
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= is_sidebar_active(['history']) ? 'active' : '' ?>" href="<?= site_url('history'); ?>">
                <i class="fas fa-history fa-fw me-2"></i> Log Aktivitas
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a class="nav-link text-danger" href="<?= site_url('logout'); ?>">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>
</aside>