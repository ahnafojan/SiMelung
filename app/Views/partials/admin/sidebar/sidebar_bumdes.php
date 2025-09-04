<ul class="navbar-nav sidebar sidebar-dark accordion bg-main" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('DashboardBumdes') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3 fs-3">Simelung</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('dashboard/dashboard_bumdes') ?>">
            <i class="fas fa-fw fa-tachometer-alt fa-lg"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('admin-user') ?>">
            <i class="fas fa-fw fa-users fa-lg"></i>
            <span>Manajemen Admin</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('PersetujuanKomersial') ?>">
            <i class="fas fa-fw fa-tasks fa-lg"></i>
            <span>Persetujuan</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Report
    </div>

    <li class="nav-item">
        <!-- Menggunakan path 'bumdes/laporan' yang sesuai dengan file Routes.php -->
        <a class="nav-link fs-3" href="<?= site_url('bumdes/laporan') ?>">
            <i class="fas fa-fw fa-book fa-lg"></i>
            <span>Laporan</span>
        </a>
    </li>
    <li class="nav-item">
        <!-- URL disesuaikan dengan rute '/pengaturan' -->
        <a class="nav-link fs-3" href="<?= site_url('pengaturan/bumdes') ?>">
            <!-- Ikon 'fa-cog' lebih cocok untuk pengaturan -->
            <i class="fas fa-fw fa-cog fa-lg"></i>
            <span>Pengaturan Export</span>
        </a>
    </li>
    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>