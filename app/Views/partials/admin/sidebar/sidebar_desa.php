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

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('dashboard/dashboard_desa') ?>">
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
                <a class="collapse-item" href="<?= site_url('/desa/laporan_keuangan/laporan_aruskas') ?>">Arus Kas</a>
                <a class="collapse-item" href="<?= site_url('LaporanLabaRugi') ?>">Laba Rugi</a>
                <a class="collapse-item" href="<?= site_url('LaporanModal') ?>">Perubahan Modal</a>
                <a class="collapse-item" href="<?= site_url('LaporanNeraca') ?>">Neraca Keuangan</a>
                <a class="collapse-item" href="<?= site_url('LaporanBkuBulanan') ?>">BKU Bulanan</a>
                <a class="collapse-item" href="<?= site_url('LaporanBkuTahunan') ?>">BKU Tahunan</a>
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
        <a class="nav-link collapsed fs-3" href="#" data-toggle="collapse" data-target="#collapsePariwisata"
            aria-expanded="true" aria-controls="collapsePariwisata">
            <i class="fas fa-fw fa-map-marked-alt fa-lg"></i>
            <span>Laporan Pariwisata</span>
        </a>
        <div id="collapsePariwisata" class="collapse" aria-labelledby="headingPariwisata" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Jenis Laporan:</h6>
                <!-- Tautan ini sekarang mengarah ke rute laporan objek wisata -->
                <a class="collapse-item" href="<?= site_url('desa/laporan_pariwisata/objekwisata') ?>">Laporan Objek Wisata</a>
                <!-- Tautan ini sekarang mengarah ke rute laporan aset -->
                <a class="collapse-item" href="<?= site_url('desa/laporan_pariwisata/asetpariwisata') ?>">Laporan Aset</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('DesaRekapUmkm') ?>">
            <i class="fas fa-fw fa-store fa-lg"></i>
            <span>UMKM Terdaftar</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>