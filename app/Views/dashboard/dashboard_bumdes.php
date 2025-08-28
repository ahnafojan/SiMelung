<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Dashboard Aset Pariwisata</h1>

<div class="row">

<<<<<<< HEAD
    <!-- Total Kopi Masuk -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kopi Masuk Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">120 Kg</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">75 Kg</div>
                    </div>
                    <i class="fas fa-warehouse fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Petani -->
=======
    <!-- Jumlah Aset -->
>>>>>>> f97281d (Aset Pariwisata)
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
<<<<<<< HEAD
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Petani Terdaftar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">35 Orang</div>
=======
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Aset</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_aset ?? 0 ?> Unit</div>
>>>>>>> f97281d (Aset Pariwisata)
                    </div>
                    <i class="fas fa-map-marked-alt fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Nilai Perolehan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Nilai Perolehan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_nilai ?? 0,0,',','.') ?></div>
                    </div>
                    <i class="fas fa-coins fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Aset per Tahun -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tahun Perolehan Terbanyak</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $tahun_terbanyak['tahun_perolehan'] ?? '-' ?> (<?= $tahun_terbanyak['jumlah'] ?? 0 ?> aset)
                        </div>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Metode Pengadaan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Metode Pengadaan Terbanyak</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $metode_terbanyak['metode_pengadaan'] ?? '-' ?> (<?= $metode_terbanyak['jumlah'] ?? 0 ?> aset)
                        </div>
                    </div>
                    <i class="fas fa-truck-loading fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD
<!-- Ringkasan Stok dan Grafik dalam 1 baris -->
<div class="row mb-4">

    <!-- Ringkasan Stok Bersih -->
    <div class="col-xl-4 col-lg-5 mb-4">
=======
<!-- Ringkasan & Grafik -->
<div class="row mb-4">
    <!-- Ringkasan Nilai -->
    <div class="col-xl-4 col-lg-5">
>>>>>>> f97281d (Aset Pariwisata)
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-center">
                <i class="fas fa-balance-scale fa-2x text-info mr-3"></i>
                <div class="text-center">
<<<<<<< HEAD
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ringkasan Stok Bersih</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">45 Kg</div>
                    <div class="mt-2 text-muted small">Kopi masuk - kopi keluar</div>
=======
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Nilai Aset</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp <?= number_format($rata_rata_nilai ?? 0, 0, ',', '.') ?>
                    </div>
                    <div class="mt-2 text-muted small">Total nilai ÷ jumlah aset</div>
>>>>>>> f97281d (Aset Pariwisata)
                </div>
            </div>
        </div>
    </div>

<<<<<<< HEAD

    <!-- Grafik Kopi Masuk dan Keluar -->
    <div class="col-xl-8 col-lg-7 mb-4">
=======
    <!-- Grafik -->
    <div class="col-xl-8 col-lg-7">
>>>>>>> f97281d (Aset Pariwisata)
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistik Aset Pariwisata</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="asetChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- UMKM Terdaftar -->
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('asetChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
<<<<<<< HEAD
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
=======
            labels: <?= json_encode(array_column($aset_per_tahun ?? [], 'tahun_perolehan')) ?>,
            datasets: [{
                label: 'Jumlah Aset per Tahun',
                data: <?= json_encode(array_column($aset_per_tahun ?? [], 'jumlah')) ?>,
                backgroundColor: 'rgba(30, 64, 175, 0.5)',
                borderColor: 'rgba(30, 64, 175, 1)',
                borderWidth: 2
            }]
>>>>>>> f97281d (Aset Pariwisata)
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<?= $this->endSection() ?>
