<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Dashboard Admin Komersial</h1>

<div class="row">

    <!-- Total Kopi Masuk -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kopi Masuk Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalMasuk ?? 0 ?> Kg</div>
                    </div>
                    <i class="fas fa-coffee fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Kopi Keluar -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Kopi Keluar Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalKeluar ?? 0 ?> Kg</div>
                    </div>
                    <i class="fas fa-warehouse fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Petani -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Petani Terdaftar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalPetani ?? 0 ?> Orang</div>
                    </div>
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Aset -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Aset Produksi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalAset ?? 0 ?> Unit</div>
                    </div>
                    <i class="fas fa-tools fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Ringkasan Stok Bersih -->
<div class="row mb-4">
    <div class="col-xl-4 col-lg-5">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-center">
                <i class="fas fa-balance-scale fa-2x text-info mr-3"></i>
                <div class="text-center">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ringkasan Stok Bersih</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format($stokBersih ?? 0, 0) ?> Kg
                    </div>

                    <div class="mt-2 text-muted small">Kopi masuk - kopi keluar</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Kopi Masuk dan Keluar -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Kopi Masuk & Keluar Bulan Ini</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="kopiChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional CSS for Chart Responsiveness -->
<style>
    #kopiChart {
        max-width: 100%;
        height: auto;
    }

    @media (min-width: 768px) {
        #kopiChart {
            height: 300px !important;
        }
    }
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('kopiChart').getContext('2d');
    const kopiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $labels ?? '[]' ?>, // label tanggal dari controller
            datasets: [{
                    label: 'Kopi Masuk (Kg)',
                    data: <?= $dataMasuk ?? '[]' ?>, // data dinamis dari controller
                    backgroundColor: 'rgba(40, 167, 69, 0.4)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2,
                    fill: true
                },
                {
                    label: 'Kopi Keluar (Kg)',
                    data: <?= $dataKeluar ?? '[]' ?>, // data dinamis dari controller
                    backgroundColor: 'rgba(220, 53, 69, 0.4)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 2,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>