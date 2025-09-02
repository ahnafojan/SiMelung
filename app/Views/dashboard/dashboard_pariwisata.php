<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>📊 Dashboard Aset Pariwisata</h3>
    <hr>

    <div class="row text-center">
        <div class="col-md-6">
            <div class="card bg-main text-white mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Aset</h5>
                    <h3><?= $jumlah_aset ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-accent text-white mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Nilai Perolehan</h5>
                    <h3>Rp <?= number_format($total_nilai ?? 0, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Aset per Tahun -->
    <div class="card mt-4 shadow">
        <div class="card-header bg-main text-white">Aset per Tahun Perolehan</div>
        <div class="card-body">
            <canvas id="chartTahun"></canvas>
        </div>
    </div>

    <!-- Chart Aset per Metode Pengadaan -->
    <div class="card mt-4 shadow">
        <div class="card-header bg-accent text-white">Aset per Metode Pengadaan</div>
        <div class="card-body">
            <canvas id="chartMetode"></canvas>
        </div>
    </div>
</div>

<script>
    function safeArray(arr) {
        return Array.isArray(arr) && arr.length ? arr : [];
    }

    // Chart per Tahun
    const ctxTahun = document.getElementById('chartTahun');
    new Chart(ctxTahun, {
        type: 'bar',
        data: {
            labels: safeArray(<?= json_encode(array_column($aset_per_tahun ?? [], 'tahun_perolehan')) ?>),
            datasets: [{
                label: 'Jumlah Aset',
                data: safeArray(<?= json_encode(array_column($aset_per_tahun ?? [], 'jumlah')) ?>),
                borderWidth: 2,
                backgroundColor: '#1e1f54',
                borderColor: '#f59e0b'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });

    // Chart per Metode
    const ctxMetode = document.getElementById('chartMetode');
    new Chart(ctxMetode, {
        type: 'pie',
        data: {
            labels: safeArray(<?= json_encode(array_column($aset_per_metode ?? [], 'metode_pengadaan')) ?>),
            datasets: [{
                label: 'Jumlah Aset',
                data: safeArray(<?= json_encode(array_column($aset_per_metode ?? [], 'jumlah')) ?>),
                backgroundColor: [
                    '#1e1f54',
                    '#f59e0b',
                    '#10b981',
                    '#ef4444'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>