<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4 bg-light min-vh-100">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 text-dark font-weight-bolder">Dashboard Komersial</h1>
            <p class="text-secondary small">Ringkasan operasional dan visualisasi Komersial Bumdes Melung.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-lg">
        <div class="card-body py-3">
            <form method="get" action="<?= base_url('dashboard') ?>">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="bulan" class="form-label text-muted small mb-1">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control form-control-sm rounded-pill">
                            <?php
                            $namaBulan = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ];
                            foreach ($namaBulan as $num => $nama): ?>
                                <option value="<?= $num ?>" <?= ($bulan == $num) ? 'selected' : '' ?>>
                                    <?= $nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tahun" class="form-label text-muted small mb-1">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control form-control-sm rounded-pill">
                            <?php foreach ($years as $y): ?>
                                <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill shadow-sm mt-3 mt-md-0">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kopi Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($totalMasuk ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-box fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Kopi Keluar</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($totalKeluar ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-truck-loading fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Stok Bersih</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($stokBersih ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-balance-scale fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Petani Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalPetani ?? 0 ?> <span class="text-muted small">Orang</span></div>
                        </div>
                        <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Aset Terdaftar</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalAset ?? 0 ?> <span class="text-muted small">Unit</span></div>
                    </div>
                    <i class="fas fa-cubes fa-2x text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100 rounded-lg animated--grow-in">
                <div class="card-header bg-white py-3 border-0 rounded-top-lg d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Tren Kopi Masuk & Keluar</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height:300px;">
                        <canvas id="kopiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100 rounded-lg animated--grow-in">
                <div class="card-header bg-white py-3 border-0 rounded-top-lg d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi per Jenis Kopi</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="chart-pie pt-4 pb-2" style="position: relative; height:250px;">
                        <canvas id="jenisKopiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kopi Chart
        const ctx = document.getElementById('kopiChart').getContext('2d');
        const chartKopi = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Kopi Masuk',
                    data: <?= $dataMasuk ?>,
                    borderColor: '#4bc0c0',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#4bc0c0',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    pointRadius: 4
                }, {
                    label: 'Kopi Keluar',
                    data: <?= $dataKeluar ?>,
                    borderColor: '#ff6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#ff6384',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 10,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)'
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Tanggal',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        title: {
                            display: true,
                            text: 'Jumlah (Kg)',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });

        // Jenis Kopi Chart
        const ctxJenis = document.getElementById('jenisKopiChart').getContext('2d');
        const chartJenis = new Chart(ctxJenis, {
            type: 'doughnut',
            data: {
                labels: <?= $jenisLabels ?>,
                datasets: [{
                    data: <?= $jenisTotals ?>,
                    backgroundColor: [
                        '#4bc0c0',
                        '#ff6384',
                        '#ffcd56',
                        '#36a2eb',
                        '#9966ff'
                    ],
                    hoverBackgroundColor: [
                        '#3cb3b3',
                        '#e85a73',
                        '#e6b840',
                        '#2e8bdc',
                        '#855ce6'
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                let label = tooltipItem.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += tooltipItem.raw.toLocaleString('id-ID') + ' Kg';
                                return label;
                            }
                        },
                        padding: 10,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)'
                    }
                }
            }
        });
    });
</script>

<?= $this->endSection() ?>