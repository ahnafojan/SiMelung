<?= $this->extend('layouts/main_layout_landing') ?>
<?= $this->section('content') ?>

<!-- Hero Section with Video Background -->
<section id="home" class="hero-section position-relative">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-index-2">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6 text-center text-lg-left">
                <div class="hero-content">
                    <h1 class="display-3 font-weight-bold text-white mb-4 animate-fade-in">
                        Selamat Datang di<br>
                        <span class="text-warning">BUMDes Melung</span>
                    </h1>
                    <p class="lead text-white-50 mb-5 animate-fade-in-delay">
                        Membangun ekonomi desa yang berkelanjutan melalui
                        Badan Usaha Milik Desa yang inovatif dan berdaya saing.
                    </p>
                    <div class="hero-buttons animate-fade-in-delay-2">
                        <a href="#tentang" class="btn btn-warning btn-lg mr-3 mb-2">
                            <i class="fas fa-play mr-2"></i>Jelajahi
                        </a>
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-lg mb-2">
                            <i class="fas fa-user-shield mr-2"></i>Login Admin
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <img src="<?= base_url('img/mellung.jpg') ?>" alt="Desa Melung"
                        class="img-fluid rounded-lg shadow-lg animate-float">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section with Timeline -->
<section id="tentang" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title text-primary">Tentang Desa Melung</h2>
                <p class="lead text-muted">Mengenal lebih dekat dengan BUMDes Melung</p>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <div class="about-image">
                    <img src="<?= base_url('img/pagubugan.png') ?>" alt="Desa Melung"
                        class="img-fluid rounded-lg shadow">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <h3 class="text-primary mb-4">Sejarah & Visi</h3>
                    <p class="mb-4">
                        Desa Melung terletak di Kecamatan Kedungbanteng, Kabupaten Banyumas,
                        Jawa Tengah. Dengan luas wilayah ± 1.270 hektar, kami memiliki
                        potensi besar di sektor pertanian khususnya kopi robusta.
                    </p>
                    <div class="feature-list">
                        <div class="feature-item mb-3">
                            <i class="fas fa-check-circle text-success mr-3"></i>
                            <span>Pengembangan UMKM lokal yang berkelanjutan</span>
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-check-circle text-success mr-3"></i>
                            <span>Pemberdayaan petani kopi melalui pelatihan</span>
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-check-circle text-success mr-3"></i>
                            <span>Pengelolaan aset desa secara profesional</span>
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-check-circle text-success mr-3"></i>
                            <span>Peningkatan kesejahteraan masyarakat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- UMKM Showcase -->
<section id="umkm" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title text-primary">UMKM Terbaik Kami</h2>
                <p class="lead text-muted">Produk unggulan dari masyarakat Desa Melung</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="umkm-card card border-0 shadow-lg">
                    <div class="card-img-wrapper">
                        <img src="<?= base_url('img/llung.png') ?>" alt="Warung Kopi"
                            class="card-img-top">
                        <div class="card-overlay">
                            <span class="badge badge-warning">Terlaris</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-primary">Warung Kopi Melung</h5>
                        <p class="card-text text-muted">Kopi robusta asli dengan cita rasa khas Desa Melung</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-warning">⭐ 4.8/5</span>
                            <span class="badge badge-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="umkm-card card border-0 shadow-lg">
                    <div class="card-img-wrapper">
                        <img src="<?= base_url('img/lung.png') ?>" alt="Kerajinan"
                            class="card-img-top">
                        <div class="card-overlay">
                            <span class="badge badge-info">Baru</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-primary">Kerajinan Tangan</h5>
                        <p class="card-text text-muted">Produk anyaman bambu dan kerajinan kopi unik</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-warning">⭐ 4.6/5</span>
                            <span class="badge badge-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="umkm-card card border-0 shadow-lg">
                    <div class="card-img-wrapper">
                        <img src="<?= base_url('img/kopp.png') ?>" alt="Oleh-oleh"
                            class="card-img-top">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-primary">Toko Oleh-oleh</h5>
                        <p class="card-text text-muted">Produk khas Desa Melung untuk kenang-kenangan</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-warning">⭐ 4.7/5</span>
                            <span class="badge badge-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Petani Section with Cards -->
<section id="petani" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title text-primary">Daftar Petani Kami</h2>
                <p class="lead text-muted">Para Petani Desa Melung</p>
            </div>
        </div>
        <!-- Tabel untuk layar medium ke atas -->
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover shadow">
                    <thead class="bg-primary-custom text-white">
                        <tr>
                            <th>Foto</th>
                            <th>Nama Petani</th>
                            <th>Jenis Komoditas Kopi</th>
                            <th>Luas Lahan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="<?= base_url('img/p1.png') ?>" alt="Petani 1" class="rounded-circle" width="40" height="40"></td>
                            <td class="font-weight-bold">Bapak Sutrisno</td>
                            <td>Kopi Robusta</td>
                            <td>2.5 Ha</td>
                            <td><span class="badge badge-success">Aktif</span></td>
                        </tr>
                        <tr>
                            <td><img src="<?= base_url('img/p2.png') ?>" alt="Petani 2" class="rounded-circle" width="40" height="40"></td>
                            <td class="font-weight-bold">Ibu Siti Nurhaliza</td>
                            <td>Kopi Arabika</td>
                            <td>1.8 Ha</td>
                            <td><span class="badge badge-success">Aktif</span></td>
                        </tr>
                        <tr>
                            <td><img src="<?= base_url('img/p3.png') ?>" alt="Petani 3" class="rounded-circle" width="40" height="40"></td>
                            <td class="font-weight-bold">Bapak Joko Santoso</td>
                            <td>Kopi Robusta</td>
                            <td>3.2 Ha</td>
                            <td><span class="badge badge-success">Aktif</span></td>
                        </tr>
                        <tr>
                            <td><img src="<?= base_url('img/p4.png') ?>" alt="Petani 4" class="rounded-circle" width="40" height="40"></td>
                            <td class="font-weight-bold">Ibu Ratna Dewi</td>
                            <td>Kopi Arabika</td>
                            <td>1.5 Ha</td>
                            <td><span class="badge badge-warning">Dalam Proses</span></td>
                        </tr>
                    </tbody>
                </table>
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
                        'status' => ['Aktif', 'success']
                    ],
                    [
                        'img' => 'p2.png',
                        'nama' => 'Ibu Siti Nurhaliza',
                        'jenis' => 'Kopi Arabika',
                        'luas' => '1.8 Ha',
                        'status' => ['Aktif', 'success']
                    ],
                    [
                        'img' => 'p3.png',
                        'nama' => 'Bapak Joko Santoso',
                        'jenis' => 'Kopi Robusta',
                        'luas' => '3.2 Ha',
                        'status' => ['Aktif', 'success']
                    ],
                    [
                        'img' => 'p4.png',
                        'nama' => 'Ibu Ratna Dewi',
                        'jenis' => 'Kopi Arabika',
                        'luas' => '1.5 Ha',
                        'status' => ['Dalam Proses', 'warning']
                    ],
                ];
                foreach ($petani as $p): ?>
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <img src="<?= base_url('img/' . $p['img']) ?>" alt="<?= $p['nama'] ?>" class="rounded-circle mr-3" width="48" height="48">
                                <div>
                                    <div class="font-weight-bold"><?= $p['nama'] ?></div>
                                    <div class="small text-muted"><?= $p['jenis'] ?> &bull; <?= $p['luas'] ?></div>
                                    <span class="badge badge-<?= $p['status'][1] ?> mt-1"><?= $p['status'][0] ?></span>
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
<section id="grafik" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title text-primary">Statistik Produksi Kopi</h2>
                <p class="lead text-muted">Perkembangan produksi kopi Desa Melung</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <canvas id="grafikKopi" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Aset Section with Icons -->
<section id="aset" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title text-primary">Aset Produksi Kami</h2>
                <p class="lead text-muted">Fasilitas pendukung produksi kopi berkualitas</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="aset-card card border-0 shadow text-center h-100">
                    <div class="card-body">
                        <div class="aset-icon mb-3">
                            <i class="fas fa-cogs fa-4x text-primary"></i>
                        </div>
                        <h5 class="card-title text-primary">Mesin Giling</h5>
                        <p class="card-text">
                            <strong>3 Unit</strong><br>
                            <span class="badge badge-success">Baik</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="aset-card card border-0 shadow text-center h-100">
                    <div class="card-body">
                        <div class="aset-icon mb-3">
                            <i class="fas fa-warehouse fa-4x text-primary"></i>
                        </div>
                        <h5 class="card-title text-primary">Gudang</h5>
                        <p class="card-text">
                            <strong>2 Unit</strong><br>
                            <span class="badge badge-success">Baik</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="aset-card card border-0 shadow text-center h-100">
                    <div class="card-body">
                        <div class="aset-icon mb-3">
                            <i class="fas fa-truck fa-4x text-primary"></i>
                        </div>
                        <h5 class="card-title text-primary">Kendaraan</h5>
                        <p class="card-text">
                            <strong>4 Unit</strong><br>
                            <span class="badge badge-warning">Perawatan</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="aset-card card border-0 shadow text-center h-100">
                    <div class="card-body">
                        <div class="aset-icon mb-3">
                            <i class="fas fa-seedling fa-4x text-primary"></i>
                        </div>
                        <h5 class="card-title text-primary">Peralatan</h5>
                        <p class="card-text">
                            <strong>15 Set</strong><br>
                            <span class="badge badge-success">Baik</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom CSS -->
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #2D336B 0%, #1a1f4a 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('<?= base_url('img/mellung.jpg') ?>') center/cover;
        opacity: 0.2;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(45, 51, 107, 0.8);
    }

    .z-index-2 {
        position: relative;
        z-index: 2;
    }

    /* Animations */
    .animate-fade-in {
        animation: fadeIn 1s ease-in;
    }

    .animate-fade-in-delay {
        animation: fadeIn 1s ease-in 0.3s both;
    }

    .animate-fade-in-delay-2 {
        animation: fadeIn 1s ease-in 0.6s both;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
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
            transform: translateY(-10px);
        }
    }

    /* Stats Section */
    .stats-section {
        background: #2D336B;
    }

    .stat-card {
        padding: 20px;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    /* Section Titles */
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 50px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: #F7B801;
    }

    /* Cards */
    .umkm-card,
    .petani-card,
    .aset-card {
        transition: all 0.3s ease;
        border-radius: 15px;
    }

    .umkm-card:hover,
    .petani-card:hover,
    .aset-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .card-img-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 15px 15px 0 0;
    }

    .card-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            text-align: center;
            padding: 100px 0;
        }

        .display-3 {
            font-size: 2.5rem;
        }
    }
</style>

<!-- Chart.js Script -->
<script>
    const ctx = document.getElementById('grafikKopi').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Kopi Masuk (kg)',
                data: [120, 140, 180, 130, 170, 160, 190, 175, 200, 185, 210, 195],
                borderColor: '#2D336B',
                backgroundColor: 'rgba(45, 51, 107, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }, {
                label: 'Kopi Keluar (kg)',
                data: [100, 130, 160, 110, 150, 140, 200, 155, 200, 165, 190, 175],
                borderColor: '#F7B801',
                backgroundColor: 'rgba(247, 184, 1, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                title: {
                    display: true,
                    text: 'Produksi Kopi 2025',
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah (kg)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>