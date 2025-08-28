<ul class="navbar-nav sidebar sidebar-dark accordion bg-main" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('DashboardAdminKomersial') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" class="img-fluid" style="width: 50px; height: 50px;">
        </div>
        <div class="sidebar-brand-text mx-3">Simelung</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('/dashboard/dashboard_komersial') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Manajemen Data
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDataMaster" aria-expanded="true" aria-controls="collapseDataMaster">
            <i class="fas fa-fw fa-database"></i>
            <span>Data Master</span>
        </a>
        <div id="collapseDataMaster" class="collapse" aria-labelledby="headingDataMaster" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('Petani') ?>">Data Petani</a>
                <a class="collapse-item" href="<?= site_url('jenispohon') ?>">Jenis Pohon</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        UMKM
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransaksi" aria-expanded="true" aria-controls="collapseTransaksi">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Transaksi Kopi</span>
        </a>
        <div id="collapseTransaksi" class="collapse" aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('Kopimasuk') ?>">Kopi Masuk</a>
                <a class="collapse-item" href="<?= site_url('Kopikeluar') ?>">Kopi Keluar</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAset" aria-expanded="true" aria-controls="collapseAset">
            <i class="fas fa-fw fa-tools"></i>
            <span>Manajemen Aset</span>
        </a>
        <div id="collapseAset" class="collapse" aria-labelledby="headingAset" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('AsetKomersial') ?>">Master Aset</a>
                <a class="collapse-item" href="<?= site_url('ManajemenAsetKomersial') ?>">Manajemen Aset</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Laporan & Lainnya
    </div>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin-komersial/laporan') ?>">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>