<ul class="navbar-nav sidebar sidebar-dark accordion bg-main" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('DashboardDesa') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3 fs-3">Simelung</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('dashboard/dashboard_desa') ?>">
            <i class="fas fa-fw fa-tachometer-alt fa-lg"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Laporan & Data
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed fs-3" href="#" data-toggle="collapse" data-target="#collapseKeuangan"
            aria-expanded="true" aria-controls="collapseKeuangan">
            <i class="fas fa-fw fa-chart-pie fa-lg"></i>
            <span>Rekap Keuangan</span>
        </a>
        <div id="collapseKeuangan" class="collapse" aria-labelledby="headingKeuangan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Laporan Utama:</h6>
                <a class="collapse-item" href="<?= site_url('LaporanArusKas') ?>">Arus Kas</a>
                <a class="collapse-item" href="<?= site_url('LaporanLabaRugi') ?>">Laba Rugi</a>
                <a class="collapse-item" href="<?= site_url('LaporanModal') ?>">Perubahan Modal</a>
                <a class="collapse-item" href="<?= site_url('LaporanNeraca') ?>">Neraca</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed fs-3" href="#" data-toggle="collapse" data-target="#collapseKopi"
            aria-expanded="true" aria-controls="collapseKopi">
            <i class="fas fa-fw fa-coffee fa-lg"></i>
            <span>Komersial/Kopi</span>
        </a>
        <div id="collapseKopi" class="collapse" aria-labelledby="headingKopi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Laporan Kopi:</h6>
                <a class="collapse-item" href="<?= site_url('DesaRekapKopi') ?>">Laporan Rekap Kopi</a>
                <a class="collapse-item" href="<?= site_url('desa/laporan_komersial/petani') ?>">Laporan Petani</a>
                <a class="collapse-item" href="<?= site_url('desa/laporan_komersial/aset') ?>">Laporan Aset Produksi</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('AsetPariwisata') ?>">
            <i class="fas fa-fw fa-map-marked-alt fa-lg"></i>
            <span>Aset Pariwisata</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('UmkmTerdaftar') ?>">
            <i class="fas fa-fw fa-store fa-lg"></i>
            <span>UMKM Terdaftar</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>