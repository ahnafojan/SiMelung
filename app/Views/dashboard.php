<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

<div class="row">

    <!-- Total Kopi Masuk -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Kopi Masuk Bulan Ini</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">120 Kg</div>
            </div>
        </div>
    </div>

    <!-- Total Kopi Keluar -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Kopi Keluar Bulan Ini</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">75 Kg</div>
            </div>
        </div>
    </div>

    <!-- Jumlah Petani -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Petani Terdaftar</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">35 Orang</div>
            </div>
        </div>
    </div>

    <!-- Jumlah Aset -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Aset Produksi</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">12 Unit</div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Stok Bersih -->
    <div class="col-xl-12 col-md-12 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    Ringkasan Stok Bersih</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">45 Kg</div>
                <div class="mt-2 text-muted small">Kopi masuk - kopi keluar</div>
            </div>
        </div>
    </div>

    <!-- UMKM Terdaftar -->
    <div class="col-xl-12 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">UMKM Terdaftar</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama UMKM</th>
                                <th>Pemilik</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data Dummy -->
                            <tr>
                                <td>1</td>
                                <td>Lung Coffe</td>
                                <td>Bu Siti</td>
                                <td>0812-3456-7890</td>
                                <td>Desa Melung RT 02</td>
                                <td>Produksi kopi bubuk dan kemasan.</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Kopi Lestari</td>
                                <td>Budi Santosa</td>
                                <td>0813-2222-1111</td>
                                <td>Dusun Kaliputih</td>
                                <td>Menjual kopi robusta asli Melung.</td>
                            </tr>
                            <!-- End Dummy -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Kopi Masuk dan Keluar -->
    <div class="col-xl-12 col-lg-12 mb-4">
        <div class="card shadow mb-4">
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

    <!-- Aktivitas Terbaru -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
        </div>
        <div class="card-body">
            <ul>
                <li>31 Juli 2025: Petani "Ahmad" ditambahkan</li>
                <li>30 Juli 2025: Kopi masuk 50 kg</li>
                <li>29 Juli 2025: Aset baru ditambahkan</li>
            </ul>
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
        type: 'bar',
        data: {
            labels: ['1-7', '8-14', '15-21', '22-28', '29-31'],
            datasets: [{
                    label: 'Kopi Masuk (Kg)',
                    data: [30, 25, 50, 20, 10],
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Kopi Keluar (Kg)',
                    data: [10, 15, 20, 60, 10],
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 2
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