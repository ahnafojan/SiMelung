<ul class="navbar-nav sidebar sidebar-dark accordion bg-main" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('DashboardAdminKomersial') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3 fs-3">Simelung</div>
    </a>

    <hr class="sidebar-divider my-0">


    <li class="nav-item active">
        <a class="nav-link" href="<?= site_url('dashboard/dashboard_keuangan'); ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Laporan Keuangan
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#bkuBulanan" aria-expanded="false" aria-controls="bkuBulanan">
            <i class="fas fa-fw fa-book"></i>
            <span>BKU Bulanan</span>
        </a>
        <div id="bkuBulanan" class="collapse" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('bku-bulanan'); ?>">Lihat Data</a>
                <a class="collapse-item" href="<?= site_url('bku-bulanan/new'); ?>">Tambah Baru</a>
                <a class="collapse-item" href="<?= site_url('/history'); ?>">Log Aktivitas</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#bkuTahunan" aria-expanded="false" aria-controls="bkuTahunan">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>BKU Tahunan</span>
        </a>
        <div id="bkuTahunan" class="collapse" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('bku-tahunan'); ?>">Lihat Data</a>
                <a class="collapse-item" href="<?= site_url('bku-tahunan/new'); ?>">Tambah Baru</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#arusKas" aria-expanded="false" aria-controls="arusKas">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Laporan Arus Kas</span>
        </a>
        <div id="arusKas" class="collapse" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('laporan-arus-kas'); ?>">Lihat Data</a>
                <a class="collapse-item" href="<?= site_url('laporan-arus-kas/new'); ?>">Tambah Baru</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#perubahanModal" aria-expanded="false" aria-controls="perubahanModal">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Perubahan Modal</span>
        </a>
        <div id="perubahanModal" class="collapse" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('laporan-perubahan-modal'); ?>">Lihat Data</a>
                <a class="collapse-item" href="<?= site_url('laporan-perubahan-modal/new'); ?>">Tambah Baru</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#neraca" aria-expanded="false" aria-controls="neraca">
            <i class="fas fa-fw fa-balance-scale"></i>
            <span>Neraca Keuangan</span>
        </a>
        <div id="neraca" class="collapse" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('neraca-keuangan'); ?>">Lihat Data</a>
                <a class="collapse-item" href="<?= site_url('neraca-keuangan/new'); ?>">Tambah Baru</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Master & Lainnya
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#masterdata" aria-expanded="false" aria-controls="masterdata">
            <i class="fas fa-fw fa-database"></i>
            <span>Master Data</span>
        </a>
        <div id="masterdata" class="collapse" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= site_url('master-kategori'); ?>">Kategori Pengeluaran</a>
                <a class="collapse-item" href="<?= site_url('master-pendapatan'); ?>">Pendapatan</a>
                <a class="collapse-item" href="<?= site_url('pengaturan'); ?>">Pengaturan TTD</a>
                <a class="collapse-item" href="<?= site_url('master-neraca'); ?>">Komponen Neraca</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('logout'); ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>