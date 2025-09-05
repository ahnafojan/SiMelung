<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <!-- Header yang Lebih Ringkas -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 text-dark font-weight-bolder">Dashboard Pariwisata</h1>
            <p class="text-secondary small">Ringkasan Aset Pariwisata Desa Melung</p>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('asetpariwisata') ?>" class="btn btn-primary">
                <i class="fas fa-list"></i> Kelola Aset
            </a>
            <a href="<?= base_url('objekwisata') ?>" class="btn btn-success">
                <i class="fas fa-map-marker-alt"></i> Kelola Wisata
            </a>
        </div>
    </div>

    <!-- Stats Cards - Tetap sama -->
    <div class="row">
        <!-- Jumlah Aset Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Aset</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($jumlah_aset ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cubes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Lokasi Wisata Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Lokasi Wisata</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($jumlah_wisata ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Nilai Aset Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Nilai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_nilai ?? 0, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rata-rata Nilai Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Rata-rata Nilai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($rata_rata_nilai ?? 0, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hanya Dua Chart Utama -->
    <div class="row">
        <!-- Bar Chart - Aset per Tahun -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aset per Tahun Perolehan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="chartTahun"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart - Aset per Lokasi -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Aset per Lokasi Wisata</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="chartLokasi"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hanya Tabel Aset Terbaru -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Aset Terbaru</h6>
                    <a href="<?= base_url('asetpariwisata') ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nama Aset</th>
                                    <th>Lokasi Wisata</th>
                                    <th>Nilai</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($aset_terbaru)): ?>
                                    <?php foreach ($aset_terbaru as $aset): ?>
                                        <?php dd($aset); // HENTIKAN di sini 
                                        ?>
                                        <tr>
                                            <td><?= esc($aset['nama_aset']) ?></td>
                                            <td><?= esc($aset['nama_wisata']) ?></td>
                                            <td>Rp <?= number_format($aset['nilai_perolehan'], 0, ',', '.') ?></td>
                                            <td><?= date('d/m/Y', strtotime($aset['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data aset</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 100%;
    }

    .chart-bar,
    .chart-pie {
        height: 250px;
    }
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi helper untuk memastikan data array aman digunakan
        function safeArray(arr) {
            return Array.isArray(arr) && arr.length ? arr : [];
        }

        // Data dari Controller
        const dataTahun = safeArray(<?= json_encode($aset_per_tahun ?? []) ?>);
        const dataLokasi = safeArray(<?= json_encode($aset_per_lokasi ?? []) ?>);

        // Color palette
        const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#2e59d9'];

        // Chart 1: Aset per Tahun (Bar Chart)
        if (document.getElementById('chartTahun')) {
            new Chart(document.getElementById('chartTahun'), {
                type: 'bar',
                data: {
                    labels: dataTahun.map(item => item.tahun_perolehan),
                    datasets: [{
                        label: 'Jumlah Aset',
                        data: dataTahun.map(item => item.jumlah),
                        backgroundColor: 'rgba(78, 115, 223, 0.8)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Chart 2: Aset per Lokasi (Doughnut Chart)
        if (document.getElementById('chartLokasi')) {
            new Chart(document.getElementById('chartLokasi'), {
                type: 'doughnut',
                data: {
                    labels: dataLokasi.map(item => item.nama_wisata),
                    datasets: [{
                        data: dataLokasi.map(item => item.jumlah),
                        backgroundColor: colors.slice(0, dataLokasi.length),
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>