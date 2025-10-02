<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Dashboard Admin UMKM</h1>

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah UMKM Terdaftar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">25</div>
                    </div>
                    <i class="fas fa-store fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Jumlah Karyawan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">150 Orang</div>
                    </div>
                    <i class="fas fa-users fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pendapatan Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp 50.000.000</div>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jumlah Produk Terjual</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">500 Unit</div>
                    </div>
                    <i class="fas fa-box-open fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row mb-4">

    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-center">
                <i class="fas fa-chart-line fa-2x text-info mr-3"></i>
                <div class="text-center">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">UMKM Aktif</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">22 UMKM</div>
                    <div class="mt-2 text-muted small">Dari total 25 UMKM</div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan dan Pendaftaran UMKM Baru</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="umkmChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="col-xl-12 mb-4">
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">UMKM Terdaftar</h6>
        </div>
        <div class="card shadow">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
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
                        <tr>
                            <td>1</td>
                            <td>Toko Baju Mandiri</td>
                            <td>Budi Santoso</td>
                            <td>0812-3456-7890</td>
                            <td>Jl. Merdeka No. 10</td>
                            <td>Menjual berbagai macam pakaian lokal.</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Kue Enak Bu Rini</td>
                            <td>Rini Susanti</td>
                            <td>0813-2222-1111</td>
                            <td>Dusun Kaliputih</td>
                            <td>Produksi kue basah dan kering untuk acara.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    #umkmChart {
        max-width: 100%;
        height: auto;
    }

    @media (min-width: 768px) {
        #umkmChart {
            height: 300px !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('umkmChart').getContext('2d');
    const umkmChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
            datasets: [{
                label: 'Pendapatan (Juta Rp)',
                data: [10, 15, 20, 18],
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 2
            }, {
                label: 'UMKM Baru',
                data: [2, 3, 5, 2],
                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 2
            }]
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