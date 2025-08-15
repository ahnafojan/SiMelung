<ul class="navbar-nav sidebar sidebar-dark accordion bg-main" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('DashboardBumdes') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3 fs-3">Simelung</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('DashboardBumdes') ?>">
            <i class="fas fa-fw fa-tachometer-alt fa-lg"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('admin-user') ?>">
            <i class="fas fa-users"></i>
            <span>Manajemen Admin</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Report
    </div>

    <!-- Nav Item - Laporan -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('LaporanBumdes') ?>">
            <i class="fas fa-fw fa-book fa-lg"></i>
            <span>Laporan</span>
        </a>
    </li>

</ul>