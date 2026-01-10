<!-- app/Views/admin_komersial/kopi/pendapatan.php -->
<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">
                Rekapitulasi Pendapatan Kopi
            </h1>
            <p class="mb-0 page-subtitle text-muted">Fungsi: Menampilkan ringkasan pendapatan, biaya, dan laba bersih berdasarkan transaksi kopi masuk dan kopi keluar.</p>
        </div>
    </div>

    <!-- Filter Rentang Tanggal -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i> Filter Periode Laporan
            </h6>
        </div>
        <div class="card-body">
            <form method="get" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-4 col-12 mb-3 mb-md-0">
                        <label for="tanggal_awal" class="font-weight-bold mb-2" style="color: #5a5c69; font-size: 0.875rem;">
                            <i class="fas fa-calendar-alt mr-1" style="color: #4e73df;"></i>Tanggal Awal
                        </label>
                        <input type="date"
                            class="form-control form-control-lg border-left-primary"
                            id="tanggal_awal"
                            name="tanggal_awal"
                            value="<?= $tanggalAwal ?? date('Y-m-01') ?>"
                            style="font-size: 0.875rem;"
                            required>
                    </div>
                    <div class="col-md-4 col-12 mb-3 mb-md-0">
                        <label for="tanggal_akhir" class="font-weight-bold mb-2" style="color: #5a5c69; font-size: 0.875rem;">
                            <i class="fas fa-calendar-alt mr-1" style="color: #4e73df;"></i>Tanggal Akhir
                        </label>
                        <input type="date"
                            class="form-control form-control-lg border-left-primary"
                            id="tanggal_akhir"
                            name="tanggal_akhir"
                            value="<?= $tanggalAkhir ?? date('Y-m-d') ?>"
                            style="font-size: 0.875rem;"
                            required>
                    </div>
                    <div class="col-md-4 col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-2"></i>Tampilkan Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Cepat (Summary Cards) -->
    <div class="row mb-4">
        <!-- Total Pendapatan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pendapatan Kotor
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Biaya Pokok -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Biaya Pokok
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?= number_format($totalBiaya ?? 0, 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laba Bersih -->
        <div class="col-xl-3 col-md-6 mb-4">
            <?php
            $labaBersih = ($totalPendapatan ?? 0) - ($totalBiaya ?? 0);
            $labaColor = $labaBersih >= 0 ? 'success' : 'danger';
            $labaIcon = $labaBersih >= 0 ? 'arrow-up' : 'arrow-down';
            ?>
            <div class="card border-left-<?= $labaColor ?> shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $labaColor ?> text-uppercase mb-1">
                                Laba Bersih
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?= number_format($labaBersih, 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-<?= $labaIcon ?> fa-2x text-<?= $labaColor ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Transaksi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 hover-lift">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jumlah Transaksi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($jumlahTransaksi ?? 0, 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Pendapatan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chart-area mr-2"></i> Grafik Pendapatan Harian
            </h6>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Transaksi - Desktop -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-2"></i> Detail Transaksi Penjualan
            </h6>
            <div class="export-buttons d-flex flex-wrap gap-2 mt-2 mt-md-0">
                <a href="<?= base_url('admin-komersial/kopi/pendapatan/export/excel?' . http_build_query([
                                'tanggal_awal' => $tanggalAwal ?? '',
                                'tanggal_akhir' => $tanggalAkhir ?? '',
                            ])) ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('admin-komersial/kopi/pendapatan/export/pdf?' . http_build_query([
                                'tanggal_awal' => $tanggalAwal ?? '',
                                'tanggal_akhir' => $tanggalAkhir ?? '',
                            ])) ?>" class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($transaksi)) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th style="width: 50px;">No</th>
                                <th>Tanggal</th>
                                <th>Jenis Pohon</th>
                                <th>Tujuan</th>
                                <th>Jumlah (Kg)</th>
                                <th>Harga Jual/Kg</th>
                                <th>Harga Beli/Kg</th>
                                <th>Total Jual</th>
                                <th>Total Beli</th>
                                <th>Laba</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = ($currentPage - 1) * $perPage + 1;
                            foreach ($transaksi as $t) :
                                $totalJual = $t['jumlah'] * $t['harga_jual_per_kg'];
                                $totalBeli = $t['jumlah'] * $t['harga_beli_per_kg'];
                                $laba = $totalJual - $totalBeli;
                                $labaClass = $laba >= 0 ? 'text-success' : 'text-danger';
                            ?>
                                <tr>
                                    <td class="text-center"><?= $nomor++ ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($t['tanggal'])) ?></td>
                                    <td><?= esc($t['nama_jenis_pohon']) ?></td>
                                    <td><?= esc($t['tujuan']) ?></td>
                                    <td class="text-right"><?= number_format($t['jumlah'], 2, ',', '.') ?></td>
                                    <td class="text-right">Rp <?= number_format($t['harga_jual_per_kg'], 0, ',', '.') ?></td>
                                    <td class="text-right">Rp <?= number_format($t['harga_beli_per_kg'], 0, ',', '.') ?></td>
                                    <td class="text-right font-weight-bold">Rp <?= number_format($totalJual, 0, ',', '.') ?></td>
                                    <td class="text-right">Rp <?= number_format($totalBeli, 0, ',', '.') ?></td>
                                    <td class="text-right font-weight-bold <?= $labaClass ?>">
                                        Rp <?= number_format($laba, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-light">
                            <tr class="font-weight-bold">
                                <td colspan="7" class="text-right">TOTAL:</td>
                                <td class="text-right text-primary">Rp <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?></td>
                                <td class="text-right text-warning">Rp <?= number_format($totalBiaya ?? 0, 0, ',', '.') ?></td>
                                <td class="text-right <?= $labaBersih >= 0 ? 'text-success' : 'text-danger' ?>">
                                    Rp <?= number_format($labaBersih, 0, ',', '.') ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
                    <div class="pagination-wrapper mt-4">
                        <form method="get" class="per-page-selector">
                            <input type="hidden" name="tanggal_awal" value="<?= $tanggalAwal ?? '' ?>">
                            <input type="hidden" name="tanggal_akhir" value="<?= $tanggalAkhir ?? '' ?>">
                            <label class="per-page-label">
                                <i class="fas fa-list-ul mr-2"></i>Tampilkan
                            </label>
                            <div class="dropdown-container">
                                <select name="per_page" class="per-page-select" onchange="this.form.submit()">
                                    <option value="10" <?= ($perPage == 10 ? 'selected' : '') ?>>10</option>
                                    <option value="25" <?= ($perPage == 25 ? 'selected' : '') ?>>25</option>
                                    <option value="50" <?= ($perPage == 50 ? 'selected' : '') ?>>50</option>
                                    <option value="100" <?= ($perPage == 100 ? 'selected' : '') ?>>100</option>
                                </select>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </div>
                            <span class="per-page-suffix">data per halaman</span>
                        </form>
                        <nav class="pagination-nav" aria-label="Navigasi Halaman">
                            <?= $pager->links('default', 'custom_pagination_template') ?>
                        </nav>
                        <div class="page-info">
                            <span class="info-text">
                                <i class="fas fa-info-circle mr-2"></i>
                                <?php
                                $totalItems = $pager->getTotal();
                                $startItem = (($currentPage - 1) * $perPage) + 1;
                                $endItem = min($currentPage * $perPage, $totalItems);
                                ?>
                                Menampilkan <?= $startItem ?>-<?= $endItem ?> dari <?= $totalItems ?> total data
                            </span>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <div class="alert alert-info text-center mb-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    Tidak ada data transaksi dalam periode yang dipilih.
                </div>
            <?php endif; ?>
        </div>
    </div>


</div>

<!-- Script Section -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Chart.js
        const ctx = document.getElementById('pendapatanChart');

        if (ctx) {
            // Data dari controller (harus dikirim dari PHP)
            const chartLabels = <?= json_encode($chartData['labels'] ?? []) ?>;
            const chartPendapatan = <?= json_encode($chartData['pendapatan'] ?? []) ?>;
            const chartBiaya = <?= json_encode($chartData['biaya'] ?? []) ?>;
            const chartLaba = <?= json_encode($chartData['laba'] ?? []) ?>;

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                            label: 'Pendapatan',
                            data: chartPendapatan,
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Biaya',
                            data: chartBiaya,
                            borderColor: 'rgb(255, 159, 64)',
                            backgroundColor: 'rgba(255, 159, 64, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Laba',
                            data: chartLaba,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                padding: 15,
                                font: {
                                    size: window.innerWidth < 576 ? 11 : 12
                                },
                                boxWidth: window.innerWidth < 576 ? 20 : 40,
                                usePointStyle: true
                            }
                        },
                        title: {
                            display: true,
                            text: 'Tren Pendapatan, Biaya, dan Laba Harian',
                            font: {
                                size: window.innerWidth < 576 ? 13 : 16,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    // Format rupiah dengan singkatan
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    }
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                },
                                font: {
                                    size: window.innerWidth < 576 ? 9 : 11
                                }
                            },
                            grid: {
                                display: true,
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: window.innerWidth < 576 ? 45 : 0,
                                minRotation: window.innerWidth < 576 ? 45 : 0,
                                font: {
                                    size: window.innerWidth < 576 ? 9 : 11
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Update chart saat window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    myChart.options.scales.x.ticks.maxRotation = window.innerWidth < 576 ? 45 : 0;
                    myChart.options.scales.x.ticks.minRotation = window.innerWidth < 576 ? 45 : 0;
                    myChart.options.scales.x.ticks.font.size = window.innerWidth < 576 ? 9 : 11;
                    myChart.options.scales.y.ticks.font.size = window.innerWidth < 576 ? 9 : 11;
                    myChart.options.plugins.legend.labels.font.size = window.innerWidth < 576 ? 11 : 12;
                    myChart.options.plugins.legend.labels.boxWidth = window.innerWidth < 576 ? 20 : 40;
                    myChart.options.plugins.title.font.size = window.innerWidth < 576 ? 13 : 16;
                    myChart.update();
                }, 250);
            });
        }

        // Validasi Form Filter
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                const tanggalAwal = document.getElementById('tanggal_awal').value;
                const tanggalAkhir = document.getElementById('tanggal_akhir').value;

                if (tanggalAwal && tanggalAkhir && tanggalAwal > tanggalAkhir) {
                    e.preventDefault();
                    alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir!');
                }
            });
        }

        // Hover Effect untuk Cards
        const cards = document.querySelectorAll('.hover-lift');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

<style>
    /* Custom Styles untuk Laporan Pendapatan */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    /* Chart Container - Responsive */
    .chart-container {
        position: relative;
        width: 100%;
        height: 400px;
        margin: 0 auto;
    }

    /* Responsive untuk tablet */
    @media (max-width: 992px) {
        .chart-container {
            height: 350px;
        }
    }

    /* Responsive untuk mobile */
    @media (max-width: 768px) {
        .chart-container {
            height: 280px;
        }
    }

    @media (max-width: 576px) {
        .chart-container {
            height: 250px;
        }

        .card-body {
            padding: 1rem 0.5rem !important;
        }
    }

    /* Pagination Styling */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .per-page-select {
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        background-color: #fff;
        cursor: pointer;
    }

    .dropdown-container {
        position: relative;
    }

    .dropdown-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #858796;
    }

    .page-info {
        color: #858796;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .pagination-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .per-page-selector {
            justify-content: center;
        }

        .page-info {
            text-align: center;
        }
    }
</style>

<?= $this->endSection() ?>