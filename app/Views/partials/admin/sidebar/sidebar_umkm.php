<ul class="navbar-nav sidebar sidebar-dark accordion bg-main" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('DashboardAdminUmkm') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3 fs-3">Simelung</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('DashboardAdminUmkm') ?>">
            <i class="fas fa-fw fa-tachometer-alt fa-lg"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Manajemen UMKM -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('umkm') ?>">
            <i class="fas fa-store"></i>
            <span>Manajemen UMKM</span>
        </a>
    </li>

    <!-- Nav Item - Pengaturan Export -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('pengaturanumkm') ?>">
            <i class="fas fa-file-export"></i>
            <span>Pengaturan Export</span>
        </a>
    </li>

    <!-- Nav Item - Informasi -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('informasi') ?>">
            <i class="fas fa-info-circle"></i>
            <span>Informasi</span>
        </a>
    </li>

</ul>
