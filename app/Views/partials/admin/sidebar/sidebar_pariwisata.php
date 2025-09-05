<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard/dashboard_pariwisata') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3">SIMELUNG</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Menu -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('dashboard/dashboard_pariwisata') ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Data Master
    </div>

    <!-- MENU BARU UNTUK OBJEK WISATA DITAMBAHKAN DI SINI -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('objekwisata') ?>">
            <i class="fas fa-mountain"></i>
            <span>Objek Wisata</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('asetpariwisata') ?>">
            <i class="fas fa-archive"></i>
            <span>Manajemen Aset</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Laporan
    </div>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('laporanasetpariwisata') ?>">
            <i class="fas fa-file-alt"></i>
            <span>Laporan Aset</span></a>
    </li>
    <li class="nav-item">
        <!-- URL disesuaikan dengan rute '/pengaturan' -->
        <a class="nav-link fs-3" href="<?= site_url('pengaturan/pariwisata') ?>">
            <!-- Ikon 'fa-cog' lebih cocok untuk pengaturan -->
            <i class="fas fa-fw fa-cog fa-lg"></i>
            <span>Pengaturan Export</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->