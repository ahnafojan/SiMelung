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
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard/dashboard_pariwisata') ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" width="60" height="60" style="object-fit: cover;">
        </div>
        <div class="sidebar-brand-text mx-3">SIMELUNG</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('dashboard/dashboard_pariwisata') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Data Master
    </div>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('objekwisata') ?>">
            <i class="fas fa-fw fa-mountain"></i>
            <span>Objek Wisata</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('asetpariwisata') ?>">
            <i class="fas fa-fw fa-archive"></i>
            <span>Manajemen Aset</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Laporan
    </div>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('laporanasetpariwisata') ?>">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan Aset</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link fs-3" href="<?= site_url('pengaturan/pariwisata') ?>">
            <i class="fas fa-fw fa-cog fa-lg"></i>
            <span>Pengaturan Export</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->