<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Dashboard Admin UMKM</h1>

<div class="row">

    <!-- 1. Card: Jumlah UMKM Terdaftar (DINAMIS - FOKUS) -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total UMKM Terdaftar</div>
                        <!-- Menampilkan total UMKM, default 0 jika data tidak ada -->
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($totalUmkm ?? 0) ?></div>
                    </div>
                    <i class="fas fa-store fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Card Rincian UMKM per Kategori -->
    <div class="col-xl-8 col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rincian UMKM per Kategori</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    // Menggunakan data dari Controller
                    $umkmPerKategori = $umkmPerKategori ?? [];
                    if (empty($umkmPerKategori) || $totalUmkm == 0): ?>
                        <div class="col-12 text-center text-muted py-3">Data kategori belum tersedia atau total UMKM masih 0.</div>
                    <?php else: ?>
                        <?php
                        // Tampilkan kategori dalam bentuk card kecil (maksimal 4 per baris)
                        $colors = ['success', 'info', 'warning', 'danger', 'primary', 'secondary'];
                        $colorIndex = 0;

                        foreach ($umkmPerKategori as $item):
                            // Pastikan kategori tidak kosong
                            if (!empty($item['kategori'])):
                        ?>
                                <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                    <div class="card bg-light border-left-<?= $colors[$colorIndex % count($colors)] ?> shadow-sm">
                                        <div class="card-body py-2">
                                            <div class="text-xs font-weight-bold text-<?= $colors[$colorIndex % count($colors)] ?> text-uppercase mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($item['kategori']) ?></div>
                                            <div class="h6 mb-0 font-weight-bold text-gray-800"><?= esc($item['jumlah']) ?> UMKM</div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                                $colorIndex++;
                            endif;
                        endforeach;
                        ?>
                    <?php endif; ?>
                </div>
                <div class="mt-2 text-right">
                    <span class="text-muted small">Total: <?= esc($totalUmkm ?? 0) ?> UMKM</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">

    <!-- 3. Grafik Distribusi UMKM per Kategori (Doughnut Chart) - Penuh (col-xl-12) -->
    <div class="col-xl-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Visualisasi Distribusi UMKM Berdasarkan Kategori</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:350px;">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <!-- 4. Tabel Detail UMKM Terdaftar (DINAMIS - Full Width) -->
    <div class="col-xl-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Semua UMKM Terdaftar</h6>
            </div>
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
                        <?php
                        $umkmData = $umkmData ?? []; // Memastikan variabel ada
                        if (empty($umkmData)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data UMKM yang terdaftar.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach ($umkmData as $umkm): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($umkm['nama_umkm'] ?? 'N/A') ?></td>
                                    <td><?= esc($umkm['pemilik'] ?? 'N/A') ?></td>
                                    <td><?= esc($umkm['kontak'] ?? 'N/A') ?></td>
                                    <td><?= esc($umkm['alamat'] ?? 'N/A') ?></td>
                                    <td><?= esc($umkm['deskripsi'] ?? 'N/A') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    #kategoriChart {
        max-width: 100%;
        height: auto;
    }

    @media (min-width: 768px) {
        #kategoriChart {
            height: 350px !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari PHP Controller
    const kategoriLabels = <?= json_encode($kategoriLabels ?? []) ?>;
    const kategoriData = <?= json_encode($kategoriData ?? []) ?>;

    // --- CHART DISTRIBUSI KATEGORI (DOUGHNUT) ---
    const ctxKategori = document.getElementById('kategoriChart').getContext('2d');
    const backgroundColors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'
    ];

    new Chart(ctxKategori, {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                data: kategoriData,
                backgroundColor: backgroundColors.slice(0, kategoriLabels.length),
                hoverBackgroundColor: backgroundColors.slice(0, kategoriLabels.length).map(c => c + 'd0'),
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, data) {
                        const label = data.labels[tooltipItem.index] || '';
                        const value = data.datasets[0].data[tooltipItem.index];
                        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1) + '%';
                        return `${label}: ${value} UMKM (${percentage})`;
                    }
                }
            },
            legend: {
                display: true,
                position: 'right'
            },
            cutoutPercentage: 80,
        }
    });
</script>

<?= $this->endSection() ?>