<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Laporan Aset Pariwisata</h1>

<!-- Daftar Aset Pariwisata Terdaftar -->
<div class="card shadow mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-info">Daftar Aset Pariwisata Terdaftar</h6>
        <div>
            <a href="<?= base_url('laporanpariwisata/exportExcel') ?>" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="<?= base_url('laporanpariwisata/exportPDF') ?>" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Aset</th>
                        <th>Lokasi</th>
                        <th>Kode & NUP</th>
                        <th>Tahun</th>
                        <th>Nilai Perolehan</th>
                        <th>Pengadaan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($asets)): ?>
                        <?php foreach($asets as $index => $a): ?>
                            <tr>
                                <td><?= $index+1 ?></td>
                                <td><?= esc($a['nama_aset']) ?></td>
                                <td><?= esc($a['nama_pariwisata']) ?></td>
                                <td>
                                    <span class="fw-bold"><?= esc($a['kode_aset']) ?></span><br>
                                    <small class="text-muted">NUP: <?= esc($a['nup']) ?></small>
                                </td>
                                <td><?= esc($a['tahun_perolehan']) ?></td>
                                <td>Rp <?= number_format($a['nilai_perolehan'], 0, ',', '.') ?></td>
                                <td>
                                    <?= esc($a['metode_pengadaan']) ?><br>
                                    <small class="text-muted"><?= esc($a['sumber_pengadaan']) ?></small>
                                </td>
                                <td><?= esc($a['keterangan']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Data aset pariwisata belum tersedia.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
