<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Laporan Aset Pariwisata</h1>

<div class="card shadow mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-info">Daftar Pariwisata</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Pariwisata</th>
                        <th>Lokasi</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($pariwisata as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $p['nama_pariwisata'] ?></td>
                            <td><?= $p['lokasi'] ?></td>
                            <td>
                                <a href="<?= base_url('laporanpariwisata/exportPDF/' . $p['id']) ?>" class="btn btn-sm btn-danger">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a href="<?= base_url('laporanpariwisata/exportExcel/' . $p['id']) ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-file-excel"></i> Excel
                                </a>
                                <a href="<?= base_url('laporanpariwisata/detail/' . $p['id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>