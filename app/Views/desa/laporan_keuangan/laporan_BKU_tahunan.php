<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content'); ?>

<style>
    .stat-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .chart-container {
        position: relative;
        height: 350px;
        /* Tinggi default untuk chart */
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title"><?= esc($title) ?></h1>
            <p class="mb-0 page-subtitle">Rekapan BKU Tahunan Desa Melung.</p>
        </div>
    </div>
    <!-- Card Filter Laporan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Pilih Periode Laporan</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('LaporanBkuTahunan'); ?>" method="get">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="tahun">Pilih Tahun:</label>
                        <!-- DIUBAH: Menggunakan class form-control untuk Bootstrap 4 -->
                        <select name="tahun" id="tahun" class="form-control" required>
                            <option value="">-- Pilih Tahun --</option>
                            <?php foreach ($daftar_tahun as $th): ?>
                                <option value="<?= $th['tahun']; ?>" <?= (isset($tahunDipilih) && $tahunDipilih == $th['tahun']) ? 'selected' : ''; ?>>
                                    <?= $th['tahun']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <!-- DIUBAH: Menggunakan class mr-2 untuk Bootstrap 4 -->
                        <button type="submit" class="btn btn-primary"><i class="fas fa-eye mr-2"></i>Tampilkan Laporan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tampilan Hasil Laporan -->
    <?php if (isset($hasil)): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- DIUBAH: Tombol aksi ekspor telah dihapus -->
                <h6 class="m-0 font-weight-bold text-primary">Hasil Laporan Tahun <?= esc($tahunDipilih); ?></h6>
            </div>
            <div class="card-body">
                <!-- Stat Cards -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow-sm h-100 py-2 stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp ' . number_format($hasil['totalPendapatan'], 0, ',', '.'); ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-wallet fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-danger shadow-sm h-100 py-2 stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp ' . number_format($hasil['totalPengeluaran'], 0, ',', '.'); ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-receipt fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow-sm h-100 py-2 stat-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sisa Saldo</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp ' . number_format($hasil['saldoAkhirTahun'], 0, ',', '.'); ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-piggy-bank fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Rincian Tabel dan Chart -->
                <div class="row">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h5 class="mb-3 text-center"><strong>Rincian Pengeluaran per Kategori</strong></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" width="100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kategori Pengeluaran</th>
                                        <th class="text-right">Total Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($hasil['pengeluaranPerKategori'])): ?>
                                        <tr>
                                            <td colspan="2" class="text-center">Tidak ada data pengeluaran.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($hasil['pengeluaranPerKategori'] as $row): ?>
                                            <tr>
                                                <td><?= esc($row['nama_kategori']); ?></td>
                                                <td class="text-right"><?= 'Rp ' . number_format($row['total_per_kategori'], 0, ',', '.'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="thead-light">
                                    <tr>
                                        <th class="text-right">TOTAL KESELURUHAN</th>
                                        <th class="text-right"><?= 'Rp ' . number_format($hasil['totalPengeluaran'], 0, ',', '.'); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="mb-3 text-center"><strong>Visualisasi Pengeluaran</strong></h5>
                        <div class="chart-container" id="chart-wrapper">
                            <canvas id="pengeluaranChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<?php if (isset($hasil) && !empty($hasil['pengeluaranPerKategori'])): ?>
    <!-- Memuat Chart.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pengeluaranData = <?= json_encode($hasil['pengeluaranPerKategori']); ?>;
            const chartWrapper = document.getElementById('chart-wrapper');

            if (pengeluaranData && pengeluaranData.length > 0) {
                const labels = pengeluaranData.map(item => item.nama_kategori);
                const data = pengeluaranData.map(item => item.total_per_kategori);
                const legendPosition = window.innerWidth < 768 ? 'bottom' : 'right';

                const ctx = document.getElementById('pengeluaranChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pengeluaran',
                            data: data,
                            backgroundColor: [
                                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                                '#858796', '#5a5c69', '#fd7e14', '#6610f2', '#e83e8c'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: legendPosition,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed !== null) {
                                            label += new Intl.NumberFormat('id-ID', {
                                                style: 'currency',
                                                currency: 'IDR',
                                                minimumFractionDigits: 0
                                            }).format(context.parsed);
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                chartWrapper.innerHTML = `<div class="d-flex align-items-center justify-content-center h-100 text-center text-muted p-5"><div><i class="fas fa-info-circle fa-3x mb-2"></i><br>Tidak ada data pengeluaran untuk divisualisasikan.</div></div>`;
            }
        });
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>