<?= $this->extend('layouts/main_layout_landing') ?>
<?= $this->section('content') ?>

<!-- Hero Section with Background Image -->
<section id="home" class="hero-section position-relative">
    <div class="hero-background"></div>
    <div class="hero-overlay"></div>
    <div class="hero-particles"></div>
    <div class="container position-relative z-index-3">
        <div class="row min-vh-100">
            <div class="col-12">
                <div class="hero-content-wrapper">
                    <div class="hero-content text-center">
                        <h1 class="hero-title font-weight-bold text-white mb-4 animate-fade-in">
                            Selamat Datang Di<br>
                            <span class="text-coffee-gold">BUMDES MELUNG</span>
                        </h1>
                        <p class="hero-subtitle text-white-90 mb-5 animate-fade-in-delay">
                            Membangun ekonomi desa yang berkelanjutan melalui
                            Badan Usaha Milik Desa yang inovatif dan berdaya saing
                            di kaki pegunungan Gunung Slamet yang asri dan subur.
                        </p>
                        <div class="hero-buttons animate-fade-in-delay-2">
                            <a href="#tentang" class="btn btn-coffee-primary btn-lg mr-3 mb-3 btn-explore">
                                <i class="fas fa-leaf mr-2"></i>Jelajahi Desa
                            </a>
                            <a href="#umkm" class="btn btn-outline-light btn-lg mb-3 btn-products">
                                <i class="fas fa-coffee mr-2"></i>Produk UMKM
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- About Section with Timeline -->
<section id="tentang" class="py-5 bg-mountain-mist scroll-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-header fade-up">
                    <span class="section-subtitle text-coffee-medium">Mari Berkenalan</span>
                    <h2 class="section-title text-mountain-dark">Tentang Desa Melung</h2>
                    <p class="section-description text-muted">Mengenal lebih dekat dengan BUMDES Melung di kaki pegunungan yang subur</p>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image-container slide-left">
                    <div class="about-decoration-1"></div>
                    <div class="about-decoration-2"></div>
                    <img src="<?= base_url('img/melung3.jpg') ?>" alt="Desa Melung"
                        class="img-fluid rounded-xl shadow-soft">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <div class="stat-circle">
                                <span class="stat-number">8+</span>
                                <span class="stat-label">Tahun Berdiri</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content slide-right">
                    <div class="about-tag mb-3">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Kedungbanteng, Banyumas
                    </div>
                    <h3 class="text-mountain-dark mb-4 about-title">Sejarah & Visi Kami</h3>
                    <p class="about-description mb-4">
                        Desa Melung terletak di Kecamatan Kedungbanteng, Kabupaten Banyumas,
                        Jawa Tengah. Dengan luas wilayah ± 1.270 hektar di kaki pegunungan yang asri,
                        kami memiliki potensi besar di sektor pertanian khususnya kopi berkualitas tinggi.
                    </p>
                    <div class="feature-grid">
                        <div class="feature-item fade-up" data-delay="100">
                            <div class="feature-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="feature-content">
                                <h6>UMKM Berkelanjutan</h6>
                                <span>Pengembangan usaha lokal yang ramah lingkungan</span>
                            </div>
                        </div>
                        <div class="feature-item fade-up" data-delay="200">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Pemberdayaan Petani</h6>
                                <span>Pelatihan dan pendampingan petani kopi</span>
                            </div>
                        </div>
                        <div class="feature-item fade-up" data-delay="300">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Pengelolaan Profesional</h6>
                                <span>Manajemen aset desa yang transparan</span>
                            </div>
                        </div>
                        <div class="feature-item fade-up" data-delay="400">
                            <div class="feature-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Kesejahteraan Bersama</h6>
                                <span>Peningkatan taraf hidup masyarakat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- UMKM Showcase -->
<section id="umkm" class="py-5 bg-white scroll-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-header fade-up">
                    <span class="section-subtitle text-coffee-medium">Produk Unggulan</span>
                    <h2 class="section-title text-mountain-dark">UMKM Terbaik Kami</h2>
                    <p class="section-description text-muted">Produk berkualitas dari tangan-tangan terampil masyarakat Desa Melung</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="umkm-card card border-0 shadow-elegant fade-up" data-delay="100">
                    <div class="card-img-wrapper">
                        <img src="<?= base_url('img/llung.png') ?>" alt="Warung Kopi"
                            class="card-img-top">
                        <div class="card-overlay">
                            <span class="badge badge-bestseller">
                                <i class="fas fa-fire mr-1"></i>Terlaris
                            </span>
                        </div>
                        <div class="card-hover-overlay">
                            <div class="hover-content">
                                <i class="fas fa-coffee"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="product-category mb-2">Kuliner</div>
                        <h5 class="card-title text-mountain-dark">Warung Kopi Melung</h5>
                        <p class="card-text text-muted">Kopi robusta asli dengan cita rasa khas pegunungan Desa Melung yang autentik</p>
                        <div class="product-footer">
                            <div class="rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-text">4.8/5</span>
                            </div>
                            <span class="badge badge-active">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="umkm-card card border-0 shadow-elegant fade-up" data-delay="200">
                    <div class="card-img-wrapper">
                        <img src="<?= base_url('img/lung.png') ?>" alt="Kerajinan"
                            class="card-img-top">
                        <div class="card-overlay">
                            <span class="badge badge-new">
                                <i class="fas fa-star mr-1"></i>Baru
                            </span>
                        </div>
                        <div class="card-hover-overlay">
                            <div class="hover-content">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="product-category mb-2">Kerajinan</div>
                        <h5 class="card-title text-mountain-dark">Kerajinan Tangan</h5>
                        <p class="card-text text-muted">Produk anyaman bambu dan kerajinan kopi unik dengan sentuhan seni lokal</p>
                        <div class="product-footer">
                            <div class="rating">
                                <span class="stars">★★★★☆</span>
                                <span class="rating-text">4.6/5</span>
                            </div>
                            <span class="badge badge-active">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="umkm-card card border-0 shadow-elegant fade-up" data-delay="300">
                    <div class="card-img-wrapper">
                        <img src="<?= base_url('img/kopp.png') ?>" alt="Oleh-oleh"
                            class="card-img-top">
                        <div class="card-hover-overlay">
                            <div class="hover-content">
                                <i class="fas fa-gift"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="product-category mb-2">Souvenir</div>
                        <h5 class="card-title text-mountain-dark">Toko Oleh-oleh</h5>
                        <p class="card-text text-muted">Produk khas Desa Melung untuk kenang-kenangan yang berkesan</p>
                        <div class="product-footer">
                            <div class="rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-text">4.7/5</span>
                            </div>
                            <span class="badge badge-active">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Petani Section with Cards -->
<section id="petani" class="py-5 bg-mountain-mist scroll-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-header fade-up">
                    <span class="section-subtitle text-coffee-medium">Mitra Terbaik</span>
                    <h2 class="section-title text-mountain-dark">Daftar Petani Kami</h2>
                    <p class="section-description text-muted">Para petani kopi berpengalaman yang menjadi tulang punggung produksi</p>
                </div>
            </div>
        </div>
        <!-- Tabel untuk layar medium ke atas -->
        <div class="d-none d-md-block">
            <div class="table-container fade-up">
                <div class="table-responsive">
                    <table class="table table-elegant">
                        <thead>
                            <tr>
                                <th>Profil</th>
                                <th>Nama Petani</th>
                                <th>Jenis Komoditas</th>
                                <th>Luas Lahan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="farmer-avatar">
                                        <img src="<?= base_url('img/p1.png') ?>" alt="Petani 1">
                                        <div class="avatar-ring"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="farmer-info">
                                        <div class="farmer-name">Bapak Sutrisno</div>
                                        <div class="farmer-experience">15 tahun pengalaman</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="commodity-tag coffee-robusta">
                                        <i class="fas fa-coffee mr-1"></i>Kopi Robusta
                                    </div>
                                </td>
                                <td>
                                    <div class="land-area">
                                        <span class="area-number">2.5</span>
                                        <span class="area-unit">Ha</span>
                                    </div>
                                </td>
                                <td><span class="status-badge active">Aktif</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="farmer-avatar">
                                        <img src="<?= base_url('img/p2.png') ?>" alt="Petani 2">
                                        <div class="avatar-ring"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="farmer-info">
                                        <div class="farmer-name">Ibu Siti Nurhaliza</div>
                                        <div class="farmer-experience">12 tahun pengalaman</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="commodity-tag coffee-arabica">
                                        <i class="fas fa-coffee mr-1"></i>Kopi Arabika
                                    </div>
                                </td>
                                <td>
                                    <div class="land-area">
                                        <span class="area-number">1.8</span>
                                        <span class="area-unit">Ha</span>
                                    </div>
                                </td>
                                <td><span class="status-badge active">Aktif</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="farmer-avatar">
                                        <img src="<?= base_url('img/p3.png') ?>" alt="Petani 3">
                                        <div class="avatar-ring"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="farmer-info">
                                        <div class="farmer-name">Bapak Joko Santoso</div>
                                        <div class="farmer-experience">20 tahun pengalaman</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="commodity-tag coffee-robusta">
                                        <i class="fas fa-coffee mr-1"></i>Kopi Robusta
                                    </div>
                                </td>
                                <td>
                                    <div class="land-area">
                                        <span class="area-number">3.2</span>
                                        <span class="area-unit">Ha</span>
                                    </div>
                                </td>
                                <td><span class="status-badge active">Aktif</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="farmer-avatar">
                                        <img src="<?= base_url('img/p4.png') ?>" alt="Petani 4">
                                        <div class="avatar-ring"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="farmer-info">
                                        <div class="farmer-name">Ibu Ratna Dewi</div>
                                        <div class="farmer-experience">8 tahun pengalaman</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="commodity-tag coffee-arabica">
                                        <i class="fas fa-coffee mr-1"></i>Kopi Arabika
                                    </div>
                                </td>
                                <td>
                                    <div class="land-area">
                                        <span class="area-number">1.5</span>
                                        <span class="area-unit">Ha</span>
                                    </div>
                                </td>
                                <td><span class="status-badge pending">Dalam Proses</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Card list untuk layar kecil -->
        <div class="d-block d-md-none">
            <div class="row">
                <?php
                $petani = [
                    [
                        'img' => 'p1.png',
                        'nama' => 'Bapak Sutrisno',
                        'jenis' => 'Kopi Robusta',
                        'luas' => '2.5 Ha',
                        'experience' => '15 tahun',
                        'status' => ['Aktif', 'success']
                    ],
                    [
                        'img' => 'p2.png',
                        'nama' => 'Ibu Siti Nurhaliza',
                        'jenis' => 'Kopi Arabika',
                        'luas' => '1.8 Ha',
                        'experience' => '12 tahun',
                        'status' => ['Aktif', 'success']
                    ],
                    [
                        'img' => 'p3.png',
                        'nama' => 'Bapak Joko Santoso',
                        'jenis' => 'Kopi Robusta',
                        'luas' => '3.2 Ha',
                        'experience' => '20 tahun',
                        'status' => ['Aktif', 'success']
                    ],
                    [
                        'img' => 'p4.png',
                        'nama' => 'Ibu Ratna Dewi',
                        'jenis' => 'Kopi Arabika',
                        'luas' => '1.5 Ha',
                        'experience' => '8 tahun',
                        'status' => ['Dalam Proses', 'warning']
                    ],
                ];
                foreach ($petani as $index => $p): ?>
                    <div class="col-12 mb-3">
                        <div class="farmer-card-mobile fade-up" data-delay="<?= ($index + 1) * 100 ?>">
                            <div class="farmer-header">
                                <div class="farmer-avatar-mobile">
                                    <img src="<?= base_url('img/' . $p['img']) ?>" alt="<?= $p['nama'] ?>">
                                    <div class="avatar-ring-mobile"></div>
                                </div>
                                <div class="farmer-details">
                                    <div class="farmer-name-mobile"><?= $p['nama'] ?></div>
                                    <div class="farmer-meta"><?= $p['experience'] ?> pengalaman</div>
                                </div>
                                <span class="status-badge-mobile <?= $p['status'][1] ?>"><?= $p['status'][0] ?></span>
                            </div>
                            <div class="farmer-stats">
                                <div class="stat-item">
                                    <i class="fas fa-coffee"></i>
                                    <span><?= $p['jenis'] ?></span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-map"></i>
                                    <span><?= $p['luas'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Grafik Section -->
<section id="grafik" class="py-5 bg-white scroll-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-header fade-up">
                    <span class="section-subtitle text-coffee-medium">Data & Analisis</span>
                    <h2 class="section-title text-mountain-dark">Statistik Produksi Kopi</h2>
                    <p class="section-description text-muted">Perkembangan produksi kopi berkualitas dari pegunungan Desa Melung</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="chart-container fade-up">
                    <div class="chart-header">
                        <div class="chart-legends">
                            <div class="legend-item">
                                <div class="legend-color bg-mountain-primary"></div>
                                <span>Kopi Masuk (kg)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color bg-coffee-gold"></div>
                                <span>Kopi Keluar (kg)</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="grafikKopi" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Aset Section with Icons -->
<section id="aset" class="py-5 bg-mountain-mist scroll-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-header fade-up">
                    <span class="section-subtitle text-coffee-medium">Infrastruktur</span>
                    <h2 class="section-title text-mountain-dark">Aset Produksi Kami</h2>
                    <p class="section-description text-muted">Fasilitas modern untuk mendukung produksi kopi berkualitas tinggi</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="asset-card fade-up" data-delay="100">
                    <div class="asset-icon-container">
                        <div class="asset-icon bg-gradient-primary">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="asset-decoration"></div>
                    </div>
                    <div class="asset-content">
                        <h5 class="asset-title">Mesin Giling</h5>
                        <div class="asset-count">
                            <span class="count-number">3</span>
                            <span class="count-unit">Unit</span>
                        </div>
                        <div class="asset-status">
                            <span class="status-indicator good"></span>
                            <span class="status-text">Kondisi Baik</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="asset-card fade-up" data-delay="200">
                    <div class="asset-icon-container">
                        <div class="asset-icon bg-gradient-success">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="asset-decoration"></div>
                    </div>
                    <div class="asset-content">
                        <h5 class="asset-title">Gudang Penyimpanan</h5>
                        <div class="asset-count">
                            <span class="count-number">2</span>
                            <span class="count-unit">Unit</span>
                        </div>
                        <div class="asset-status">
                            <span class="status-indicator good"></span>
                            <span class="status-text">Kondisi Baik</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="asset-card fade-up" data-delay="300">
                    <div class="asset-icon-container">
                        <div class="asset-icon bg-gradient-warning">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="asset-decoration"></div>
                    </div>
                    <div class="asset-content">
                        <h5 class="asset-title">Kendaraan Operasional</h5>
                        <div class="asset-count">
                            <span class="count-number">4</span>
                            <span class="count-unit">Unit</span>
                        </div>
                        <div class="asset-status">
                            <span class="status-indicator maintenance"></span>
                            <span class="status-text">Dalam Perawatan</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="asset-card fade-up" data-delay="400">
                    <div class="asset-icon-container">
                        <div class="asset-icon bg-gradient-nature">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="asset-decoration"></div>
                    </div>
                    <div class="asset-content">
                        <h5 class="asset-title">Peralatan Pertanian</h5>
                        <div class="asset-count">
                            <span class="count-number">15</span>
                            <span class="count-unit">Set</span>
                        </div>
                        <div class="asset-status">
                            <span class="status-indicator good"></span>
                            <span class="status-text">Kondisi Baik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Mitra & Partner Section -->
<section id="mitra" class="py-5 bg-white scroll-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-header fade-up">
                    <span class="section-subtitle text-coffee-medium">Kolaborasi Strategis</span>
                    <h2 class="section-title text-mountain-dark">Mitra & Partner Kami</h2>
                    <p class="section-description text-muted">Berkolaborasi dengan institusi dan organisasi terkemuka untuk pengembangan ekonomi desa</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 px-0">
                <div class="mitra-carousel">
                    <div class="mitra-track">
                        <!-- Logo Items -->
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/amikom.png') ?>" alt="AMIKOM Logo" class="mitra-logo">
                            </div>
                        </div>
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/BUMDESS.png') ?>" alt="BUMDES Logo" class="mitra-logo">
                            </div>
                        </div>
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/POKDARWIS.png') ?>" alt="POKDARWIS Logo" class="mitra-logo">
                            </div>
                        </div>
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/DIKSTI.png') ?>" alt="DIKTI Logo" class="mitra-logo">
                            </div>
                        </div>
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/logobms.png') ?>" alt="BMS Logo" class="mitra-logo">
                            </div>
                        </div>
                        <!-- Duplicate set -->
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/amikom.png') ?>" alt="AMIKOM Logo" class="mitra-logo">
                            </div>
                        </div>
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/BUMDESS.png') ?>" alt="BUMDES Logo" class="mitra-logo">
                            </div>
                        </div>
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/DIKSTI.png') ?>" alt="DIKTI Logo" class="mitra-logo">
                            </div>
                        </div>
                        <div class="mitra-item">
                            <div class="mitra-logo-container">
                                <img src="<?= base_url('img/logobms.png') ?>" alt="BMS Logo" class="mitra-logo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Mitra Carousel Styles */
    .mitra-carousel {
        position: relative;
        padding: 2rem 0;
        overflow: hidden;
    }

    .mitra-track {
        display: flex;
        align-items: center;
        will-change: transform;
        animation: mitra-scroll 30s linear infinite;
    }

    .mitra-item {
        flex: 0 0 auto;
        padding: 0 1.5rem;
    }

    .mitra-logo-container {
        position: relative;
        width: 160px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .mitra-logo {
        max-width: 100%;
        max-height: 60px;
        object-fit: contain;
        filter: none;
        /* Tidak grayscale */
        opacity: 1;
        transition: all 0.4s ease;
    }

    /* Hover Effects */
    .mitra-item:hover .mitra-logo {
        transform: scale(1.1);
        filter: contrast(1.2) brightness(1.05);
    }

    /* Animations */
    @keyframes mitra-scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .mitra-item {
            padding: 0 1.2rem;
        }

        .mitra-logo-container {
            width: 140px;
            height: 80px;
        }

        .mitra-logo {
            max-height: 50px;
        }
    }

    @media (max-width: 768px) {
        .mitra-track {
            animation-duration: 25s;
        }

        .mitra-item {
            padding: 0 1rem;
        }

        .mitra-logo-container {
            width: 120px;
            height: 70px;
        }

        .mitra-logo {
            max-height: 45px;
        }
    }

    @media (max-width: 576px) {
        .mitra-track {
            animation-duration: 20s;
        }

        .mitra-item {
            padding: 0 0.8rem;
        }

        .mitra-logo-container {
            width: 100px;
            height: 60px;
        }

        .mitra-logo {
            max-height: 40px;
        }
    }
</style>


<!-- Enhanced Custom CSS -->
<style>
    /* Color Palette - Mountain Village Coffee Theme */
    :root {
        --mountain-dark: #2c3e50;
        --mountain-primary: #34495e;
        --mountain-light: #7f8c8d;
        --mountain-mist: #f8f9fa;
        --coffee-gold: #d4a574;
        --coffee-medium: #8b4513;
        --coffee-dark: #654321;
        --nature-green: #27ae60;
        --warm-white: #fefefe;
        --soft-shadow: rgba(0, 0, 0, 0.08);
        --gradient-primary: linear-gradient(135deg, var(--mountain-primary), var(--mountain-dark));
        --gradient-coffee: linear-gradient(135deg, var(--coffee-gold), var(--coffee-medium));
    }

    /* Global Styles */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        line-height: 1.6;
        color: var(--mountain-dark);
    }

    .text-coffee-gold {
        color: var(--coffee-gold) !important;
    }

    .text-coffee-medium {
        color: var(--coffee-medium) !important;
    }

    .text-mountain-dark {
        color: var(--mountain-dark) !important;
    }

    .text-white-80 {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .bg-mountain-mist {
        background-color: var(--mountain-mist) !important;
    }

    /* Hero Section - Full Background Image */
    .hero-section {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding-top: 120px;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('<?= base_url('img/mellung.jpg') ?>') center/cover no-repeat;
        background-attachment: fixed;
        z-index: 1;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg,
                rgba(44, 62, 80, 0.85) 0%,
                rgba(52, 73, 94, 0.75) 50%,
                rgba(139, 69, 19, 0.6) 100%);
        z-index: 2;
    }

    .hero-particles {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            radial-gradient(circle at 20% 30%, rgba(212, 165, 116, 0.1) 1px, transparent 1px),
            radial-gradient(circle at 80% 70%, rgba(212, 165, 116, 0.08) 1px, transparent 1px),
            radial-gradient(circle at 60% 20%, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px, 80px 80px, 120px 120px;
        animation: particleFloat 20s ease-in-out infinite;
        z-index: 2;
    }

    @keyframes particleFloat {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(180deg);
        }
    }

    .z-index-3 {
        position: relative;
        z-index: 3;
    }

    .hero-content-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .hero-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(212, 165, 116, 0.2);
        color: var(--coffee-gold);
        padding: 10px 20px;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        border: 1px solid rgba(212, 165, 116, 0.4);
        backdrop-filter: blur(10px);
        margin-bottom: 1.5rem;
    }

    .hero-title {
        font-size: 5rem;
        font-weight: 800;
        line-height: 1.2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        line-height: 1.6;
        max-width: 650px;
        margin: 0 auto 2.5rem auto;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .text-white-90 {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .hero-buttons {
        margin-bottom: 3rem;
    }

    .btn-coffee-primary {
        background: var(--gradient-coffee);
        border: none;
        color: white;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(212, 165, 116, 0.4);
        text-decoration: none;
    }

    .btn-coffee-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(212, 165, 116, 0.5);
        color: white;
        text-decoration: none;
    }

    .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.4);
        color: white;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
        text-decoration: none;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.6);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
        text-decoration: none;
    }

    .hero-scroll-indicator {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.5rem;
        animation: bounce 2s infinite;
        z-index: 3;
        cursor: pointer;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateX(-50%) translateY(0);
        }

        40% {
            transform: translateX(-50%) translateY(-10px);
        }

        60% {
            transform: translateX(-50%) translateY(-5px);
        }
    }

    /* Section Headers */
    .section-header {
        margin-bottom: 3rem;
    }

    .section-subtitle {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: var(--gradient-coffee);
        border-radius: 2px;
    }

    .section-description {
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Enhanced About Section - Symmetric Layout */
    .about-image-container {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .about-image-container img {
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        max-height: 400px;
        width: 100%;
        object-fit: cover;
    }

    .about-image-container:hover img {
        transform: scale(1.02);
    }

    .about-decoration-1 {
        position: absolute;
        top: -30px;
        left: -30px;
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, var(--coffee-gold), var(--coffee-medium));
        border-radius: 50%;
        opacity: 0.15;
        z-index: -1;
        animation: float 6s ease-in-out infinite;
    }

    .about-decoration-2 {
        position: absolute;
        bottom: -20px;
        right: -20px;
        width: 80px;
        height: 80px;
        background: linear-gradient(45deg, var(--nature-green), #219a52);
        border-radius: 50%;
        opacity: 0.2;
        z-index: -1;
        animation: float 6s ease-in-out infinite reverse;
    }

    .image-overlay {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .stat-circle {
        text-align: center;
    }

    .stat-circle .stat-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--coffee-gold);
        display: block;
        line-height: 1;
    }

    .stat-circle .stat-label {
        font-size: 0.75rem;
        color: #666;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .about-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-left: 2rem;
    }

    .about-tag {
        display: inline-flex;
        align-items: center;
        background: rgba(212, 165, 116, 0.1);
        color: var(--coffee-medium);
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        width: fit-content;
        border: 1px solid rgba(212, 165, 116, 0.2);
    }

    .about-title {
        font-size: 2.2rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 1.5rem;
    }

    .about-description {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 2rem;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px var(--soft-shadow);
        transition: all 0.3s ease;
        border: 1px solid rgba(212, 165, 116, 0.1);
    }

    .feature-item:hover {
        transform: translateX(10px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: var(--coffee-gold);
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        background: var(--gradient-coffee);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1.25rem;
        flex-shrink: 0;
        font-size: 1.2rem;
    }

    .feature-content h6 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--mountain-dark);
        font-size: 1rem;
    }

    .feature-content span {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.4;
    }

    /* Scroll Animation Effects */
    .scroll-section {
        opacity: 0;
        transition: opacity 0.6s ease;
    }

    .scroll-section.visible {
        opacity: 1;
    }

    .fade-up {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }

    .fade-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .slide-left {
        opacity: 0;
        transform: translateX(-50px);
        transition: all 0.8s ease;
    }

    .slide-left.visible {
        opacity: 1;
        transform: translateX(0);
    }

    .slide-right {
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.8s ease;
    }

    .slide-right.visible {
        opacity: 1;
        transform: translateX(0);
    }

    /* UMKM Cards */
    .umkm-card {
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
        background: white;
        opacity: 0;
        transform: translateY(30px);
    }

    .umkm-card.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .umkm-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .card-img-wrapper {
        position: relative;
        overflow: hidden;
        height: 200px;
    }

    .card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .umkm-card:hover .card-img-wrapper img {
        transform: scale(1.1);
    }

    .card-overlay {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 2;
    }

    .card-hover-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(212, 165, 116, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .umkm-card:hover .card-hover-overlay {
        opacity: 1;
    }

    .hover-content i {
        font-size: 2rem;
        color: white;
    }

    .badge-bestseller {
        background: linear-gradient(45deg, #e74c3c, #c0392b);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-new {
        background: linear-gradient(45deg, #3498db, #2980b9);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .product-category {
        font-size: 0.8rem;
        color: var(--coffee-medium);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stars {
        color: #f39c12;
        font-size: 0.9rem;
    }

    .rating-text {
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }

    .badge-active {
        background: linear-gradient(45deg, var(--nature-green), #219a52);
        color: white;
        padding: 4px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Farmer Section */
    .table-container {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px var(--soft-shadow);
    }

    .table-elegant {
        margin: 0;
        background: transparent;
    }

    .table-elegant thead {
        background: var(--gradient-primary);
        color: white;
    }

    .table-elegant thead th {
        border: none;
        padding: 1.2rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-elegant tbody tr {
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
    }

    .table-elegant tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-elegant tbody td {
        padding: 1.2rem 1rem;
        vertical-align: middle;
        border: none;
    }

    .farmer-avatar {
        position: relative;
        display: inline-block;
    }

    .farmer-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-ring {
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        bottom: -3px;
        border: 2px solid var(--coffee-gold);
        border-radius: 50%;
        opacity: 0.7;
    }

    .farmer-info .farmer-name {
        font-weight: 600;
        color: var(--mountain-dark);
        margin-bottom: 0.25rem;
    }

    .farmer-info .farmer-experience {
        font-size: 0.8rem;
        color: #666;
    }

    .commodity-tag {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .coffee-robusta {
        background: rgba(139, 69, 19, 0.1);
        color: var(--coffee-medium);
    }

    .coffee-arabica {
        background: rgba(212, 165, 116, 0.15);
        color: var(--coffee-dark);
    }

    .land-area {
        display: flex;
        align-items: baseline;
        gap: 0.25rem;
    }

    .area-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--mountain-dark);
    }

    .area-unit {
        font-size: 0.8rem;
        color: #666;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-badge.active {
        background: linear-gradient(45deg, var(--nature-green), #219a52);
        color: white;
    }

    .status-badge.pending {
        background: linear-gradient(45deg, #f39c12, #e67e22);
        color: white;
    }

    /* Mobile Farmer Cards */
    .farmer-card-mobile {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 5px 15px var(--soft-shadow);
        transition: transform 0.3s ease;
    }

    .farmer-card-mobile:hover {
        transform: translateY(-3px);
    }

    .farmer-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .farmer-avatar-mobile {
        position: relative;
        margin-right: 1rem;
    }

    .farmer-avatar-mobile img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-ring-mobile {
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        bottom: -3px;
        border: 2px solid var(--coffee-gold);
        border-radius: 50%;
        opacity: 0.7;
    }

    .farmer-details {
        flex: 1;
    }

    .farmer-name-mobile {
        font-weight: 600;
        color: var(--mountain-dark);
        margin-bottom: 0.25rem;
    }

    .farmer-meta {
        font-size: 0.85rem;
        color: #666;
    }

    .status-badge-mobile {
        padding: 4px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
    }

    .status-badge-mobile.success {
        background: var(--nature-green);
    }

    .status-badge-mobile.warning {
        background: #f39c12;
    }

    .farmer-stats {
        display: flex;
        gap: 1rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #666;
    }

    .stat-item i {
        color: var(--coffee-medium);
    }

    /* Chart Section */
    .chart-container {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px var(--soft-shadow);
    }

    .chart-header {
        margin-bottom: 2rem;
    }

    .chart-legends {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .legend-color {
        width: 20px;
        height: 4px;
        border-radius: 2px;
    }

    .bg-mountain-primary {
        background: var(--mountain-primary);
    }

    .bg-coffee-gold {
        background: var(--coffee-gold);
    }

    /* Asset Cards */
    .asset-card {
        background: white;
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        box-shadow: 0 5px 15px var(--soft-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .asset-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-coffee);
    }

    .asset-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .asset-icon-container {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .asset-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .bg-gradient-primary {
        background: var(--gradient-primary);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, var(--nature-green), #219a52);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #f39c12, #e67e22);
    }

    .bg-gradient-nature {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
    }

    .asset-decoration {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background: var(--coffee-gold);
        border-radius: 50%;
        opacity: 0.6;
        z-index: 1;
    }

    .asset-title {
        font-weight: 700;
        color: var(--mountain-dark);
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .asset-count {
        margin-bottom: 1rem;
    }

    .count-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--mountain-primary);
        display: block;
    }

    .count-unit {
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }

    .asset-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-indicator.good {
        background: var(--nature-green);
    }

    .status-indicator.maintenance {
        background: #f39c12;
    }

    .status-text {
        font-size: 0.85rem;
        font-weight: 500;
        color: #666;
    }

    /* Enhanced Animations */
    .animate-fade-in {
        animation: fadeInUp 1.2s ease-out;
    }

    .animate-fade-in-delay {
        animation: fadeInUp 1.2s ease-out 0.4s both;
    }

    .animate-fade-in-delay-2 {
        animation: fadeInUp 1.2s ease-out 0.8s both;
    }

    .animate-fade-in-delay-3 {
        animation: fadeInUp 1.2s ease-out 1.2s both;
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 3rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .about-content {
            padding-left: 0;
            margin-top: 2rem;
        }

        .section-title {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            text-align: center;
            padding: 80px 0;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .about-title {
            font-size: 1.8rem;
        }

        .feature-grid {
            gap: 1rem;
        }

        .feature-item {
            padding: 1rem;
        }

        .feature-item:hover {
            transform: translateY(-3px);
        }

        .chart-legends {
            gap: 1rem;
        }

        .asset-card {
            padding: 1.5rem 1rem;
        }

        .asset-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .hero-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero-buttons .btn {
            width: 100%;
            max-width: 280px;
        }

        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--coffee-gold);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--coffee-medium);
    }
</style>

<!-- Scroll Animation JavaScript -->
<script>
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');

                // Handle delayed animations
                const delayedElements = entry.target.querySelectorAll('[data-delay]');
                delayedElements.forEach(el => {
                    const delay = el.getAttribute('data-delay');
                    setTimeout(() => {
                        el.classList.add('visible');
                    }, parseInt(delay));
                });
            }
        });
    }, observerOptions);

    // Observe all sections and animation elements
    document.addEventListener('DOMContentLoaded', function() {
        // Observe sections
        const sections = document.querySelectorAll('.scroll-section');
        sections.forEach(section => observer.observe(section));

        // Observe individual animation elements
        const animationElements = document.querySelectorAll('.fade-up, .slide-left, .slide-right, .umkm-card');
        animationElements.forEach(el => observer.observe(el));

        // Smooth scroll for navigation links
        const navLinks = document.querySelectorAll('a[href^="#"]');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Hero scroll indicator functionality
        const scrollIndicator = document.querySelector('.hero-scroll-indicator');
        if (scrollIndicator) {
            scrollIndicator.addEventListener('click', function() {
                const tentangSection = document.querySelector('#tentang');
                if (tentangSection) {
                    tentangSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        }

        // Add scroll progress indicator
        const createScrollProgress = () => {
            const progressBar = document.createElement('div');
            progressBar.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 0%;
                height: 4px;
                background: linear-gradient(90deg, var(--coffee-gold), var(--coffee-medium));
                z-index: 9999;
                transition: width 0.3s ease;
            `;
            document.body.appendChild(progressBar);

            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset;
                const docHeight = document.body.offsetHeight - window.innerHeight;
                const scrollPercent = (scrollTop / docHeight) * 100;
                progressBar.style.width = scrollPercent + '%';
            });
        };

        createScrollProgress();

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElement = document.querySelector('.hero-background');
            if (parallaxElement) {
                parallaxElement.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });

        // Counter animation for statistics
        const animateCounters = () => {
            const counters = document.querySelectorAll('.count-number, .stat-number');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent);
                const increment = target / 100;
                let current = 0;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };

                // Start animation when element is visible
                const counterObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            updateCounter();
                            counterObserver.unobserve(entry.target);
                        }
                    });
                });

                counterObserver.observe(counter);
            });
        };

        animateCounters();
    });

    // Add loading animation
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');

        // Trigger hero animations
        const heroElements = document.querySelectorAll('.animate-fade-in, .animate-fade-in-delay, .animate-fade-in-delay-2');
        heroElements.forEach(el => {
            el.style.animationPlayState = 'running';
        });
    });
</script>

<!-- Enhanced Chart.js Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('grafikKopi');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Kopi Masuk (kg)',
                        data: [120, 140, 180, 130, 170, 160, 190, 175, 200, 185, 210, 195],
                        borderColor: '#34495e',
                        backgroundColor: 'rgba(52, 73, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#34495e',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Kopi Keluar (kg)',
                        data: [100, 130, 160, 110, 150, 140, 200, 155, 200, 165, 190, 175],
                        borderColor: '#d4a574',
                        backgroundColor: 'rgba(212, 165, 116, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#d4a574',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Produksi Kopi Tahun 2025',
                            font: {
                                size: 18,
                                weight: 'bold',
                                family: 'Inter'
                            },
                            color: '#2c3e50',
                            padding: {
                                bottom: 30
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(44, 62, 80, 0.9)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#d4a574',
                            borderWidth: 1,
                            cornerRadius: 10,
                            displayColors: true,
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah (kg)',
                                font: {
                                    size: 14,
                                    weight: '600'
                                },
                                color: '#666'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#666',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan',
                                font: {
                                    size: 14,
                                    weight: '600'
                                },
                                color: '#666'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#666',
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>