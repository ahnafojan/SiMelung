<?= $this->extend('layouts/main_layout_landing') ?>
<?= $this->section('content') ?>

<!-- Carousel Foto Desa -->
<div id="carouselDesa" class="carousel slide mb-4" data-ride="carousel" data-interval="2000">
    <div class="carousel-inner" style="height: 350px;">
        <div class="carousel-item active">
            <img src="<?= base_url('img/pagubugan.png') ?>" class="d-block w-100" style="object-fit:cover; height:350px;" alt="Desa 1">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="font-weight-bold">Desa Melung</h2>
            </div>
        </div>
        <div class="carousel-item">
            <img src="<?= base_url('img/mellung.jpg') ?>" class="d-block w-100" style="object-fit:cover; height:350px;" alt="Desa 2">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="font-weight-bold">Desa Melung</h2>
            </div>
        </div>
        <div class="carousel-item">
            <img src="<?= base_url('img/desa3.jpg') ?>" class="d-block w-100" style="object-fit:cover; height:350px;" alt="Desa 3">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="font-weight-bold">Desa Melung</h2>
            </div>
        </div>
    </div>
</div>

<!-- Cardview Aset -->
<div class="row mb-4" id="aset">
    <div class="col-md-4">
        <div class="card h-100 shadow">
            <img src="<?= base_url('img/aset1.jpg') ?>" class="card-img-top" style="height:180px; object-fit:cover;" alt="Aset 1">
            <div class="card-body">
                <h5 class="card-title">Mesin Giling Kopi</h5>
                <p class="card-text">Mesin giling kopi modern yang digunakan untuk meningkatkan efisiensi produksi kopi di Desa Melung.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow">
            <img src="<?= base_url('img/aset2.jpg') ?>" class="card-img-top" style="height:180px; object-fit:cover;" alt="Aset 2">
            <div class="card-body">
                <h5 class="card-title">Gudang Penyimpanan</h5>
                <p class="card-text">Gudang penyimpanan hasil panen kopi yang terjaga kebersihan dan keamanannya.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow">
            <img src="<?= base_url('img/aset3.jpg') ?>" class="card-img-top" style="height:180px; object-fit:cover;" alt="Aset 3">
            <div class="card-body">
                <h5 class="card-title">Kendaraan Operasional</h5>
                <p class="card-text">Kendaraan operasional untuk distribusi kopi dari petani ke gudang dan pasar lokal.</p>
            </div>
        </div>
    </div>
</div>

<!-- Cardview Jumlah Petani Kopi -->
<div class="row mb-4" id="petani">
    <div class="col-md-12">
        <div class="card text-white bg-main shadow text-center">
            <div class="card-body">
                <h4 class="card-title mb-2">Total Petani Kopi Terdaftar</h4>
                <h2 class="display-4 font-weight-bold">128</h2>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Kopi Masuk & Keluar -->
<div class="row mb-4" id="grafik">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Grafik Kopi Masuk & Keluar</h5>
                <canvas id="grafikKopi"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikKopi').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                    label: 'Kopi Masuk (kg)',
                    data: [120, 150, 180, 130, 170, 160],
                    backgroundColor: 'rgba(40, 167, 69, 0.7)'
                },
                {
                    label: 'Kopi Keluar (kg)',
                    data: [100, 130, 160, 110, 150, 140],
                    backgroundColor: 'rgba(220, 53, 69, 0.7)'

                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>