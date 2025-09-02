<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Laporan Aset Pariwisata</h1>

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
                        <th>Kode Aset</th>
                        <th>NUP</th>
                        <th>Tahun</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($asets as $aset): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $aset['nama_aset'] ?></td>
                            <td><?= $aset['nama_pariwisata'] ?></td>
                            <td><?= $aset['kode_aset'] ?></td>
                            <td><?= $aset['nup'] ?></td>
                            <td><?= $aset['tahun_perolehan'] ?></td>
                            <td>Rp <?= number_format($aset['nilai_perolehan'], 0, ',', '.') ?></td>
                            <td><?= $aset['keterangan'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>