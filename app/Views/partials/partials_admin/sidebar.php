<ul class="navbar-nav sidebar sidebar-dark accordion bg-main" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('/') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3 fs-3">Simelung</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('/') ?>">
            <i class="fas fa-fw fa-tachometer-alt fa-lg"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Data Petani -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('Petani') ?>">
            <i class="fas fa-fw fa-user fa-lg"></i>
            <span>Data Petani</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        UMKM
    </div>
    <!-- Nav Item - Kopi Masuk -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('Kopimasuk') ?>">
            <i class="fas fa-fw fa-coffee fa-lg"></i>
            <span>Kopi Masuk</span>
        </a>
    </li>

    <!-- Nav Item - Kopi Keluar -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('Kopikeluar') ?>">
            <i class="fas fa-fw fa-dolly fa-lg"></i>
            <span>Kopi Keluar</span>
        </a>
    </li>
    <!-- Nav Item - UMKM -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('Umkm') ?>">
            <i class="fas fa-store fa-lg"></i>
            <span>Manajemen UMKM</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Report
    </div>

    <!-- Nav Item - Aset BUMDES -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAset" aria-expanded="false" aria-controls="collapseAset">
            <i class="fas fa-fw fa-suitcase fa-lg"></i>
            <span>Aset BUMDES</span>
        </a>
        <div id="collapseAset" class="collapse" aria-labelledby="headingAset" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu Aset:</h6>
                <a class="collapse-item" href="<?= site_url('Aset') ?>">Manajemen Aset</a>
                <a class="collapse-item" href="<?= site_url('MasterAset') ?>">Master Aset</a>
            </div>
        </div>
    </li>


    <!-- Nav Item - Laporan -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('Laporan') ?>">
            <i class="fas fa-fw fa-book fa-lg"></i>
            <span>Laporan</span>
        </a>
    </li>

    <!-- Nav Item - Log Aktivitas -->
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('Log_aktivitas') ?>">
            <i class="fas fa-fw fa-paperclip fa-lg"></i>
            <span>Log Aktivitas</span>
        </a>
    </li>
    <hr class="sidebar-divider">

</ul>