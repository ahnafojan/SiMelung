<style>
    /*
     * ===============================================
     * GAYA BARU UNTUK SIDEBAR MODERN
     * ===============================================
     */

    /* 1. Latar Belakang & Teks Utama Sidebar */
    #accordionSidebar {
        background-color: #2c3e50;
        /* Warna latar gelap (biru tua keabu-abuan) */

        /* --- PERBAIKAN & PENAMBAHAN --- */
        position: sticky;
        /* BARU: Membuat sidebar menempel di layar */
        top: 0;
        /* BARU: Posisi menempel di bagian paling atas */
        height: 100vh;
        /* BARU: Tinggi sidebar 100% dari tinggi layar (viewport height) */
        z-index: 1000;
        /* BARU: Memastikan sidebar selalu di atas konten lain */
        transition: width 0.3s ease;
        /* BARU: Menambahkan animasi saat lebar sidebar diubah */
    }

    /* Mengubah warna semua teks & ikon menjadi terang */
    .sidebar .sidebar-brand-text,
    .sidebar .nav-item .nav-link span,
    .sidebar .sidebar-heading,
    .sidebar .nav-item .nav-link i {
        color: #ecf0f1;
        /* Warna putih keabu-abuan agar tidak terlalu silau */
    }

    .sidebar .nav-item .nav-link {
        /* Menambahkan sedikit transisi untuk hover */
        transition: background-color 0.2s ease-in-out;
        /* Dibutuhkan agar garis biru ::before dapat diposisikan dengan benar */
        position: relative;
    }

    /* 2. Gaya untuk Menu yang Sedang Aktif */
    .sidebar .nav-item.active {
        background-color: #34495e;
        /* Warna latar sedikit lebih terang untuk item aktif */
    }

    .sidebar .nav-item.active .nav-link span {
        font-weight: bold;
        /* Menebalkan teks menu aktif */
        color: #ffffff;
        /* Warna teks putih cerah */
    }

    .sidebar .nav-item.active .nav-link i {
        color: #ffffff;
        /* Warna ikon putih cerah */
    }

    /* Indikator garis biru di sisi kiri menu aktif */
    .sidebar .nav-item.active .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background-color: #3498db;
        /* Warna biru cerah */
    }

    /* 3. Efek Hover (saat kursor diarahkan ke menu) */
    .sidebar .nav-item:not(.active):hover {
        background-color: #34495e;
        /* Warna latar yang sama dengan item aktif */
    }

    /* 4. Garis Pemisah (Divider) */
    .sidebar-divider {
        border-top: 1px solid #4a627a;
        /* Warna garis pemisah yang lebih soft */
    }
</style>
<ul class="navbar-nav sidebar sidebar-dark accordion bg-main" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('DashboardAdminKomersial') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3 fs-3">Simelung</div>
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
            <i class="fas fa-seedling"></i>
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
    <li class="nav-item">
        <!-- URL disesuaikan dengan rute '/pengaturan' -->
        <a class="nav-link fs-3" href="<?= site_url('pengaturan/komersial') ?>">
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