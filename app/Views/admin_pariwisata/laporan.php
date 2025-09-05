<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Aset Pariwisata</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih Lokasi Wisata</h6>
        </div>
        <div class="card-body">
            <p>Silakan pilih lokasi objek wisata untuk membuat laporan aset dalam format PDF atau Excel.</p>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pariwisata</th>
                            <th class="text-center">Opsi Laporan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pariwisata)) : ?>
                            <?php foreach ($pariwisata as $index => $p) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($p['nama_wisata']) ?></td>
                                    <td class="text-center">
                                        <!-- ======================================================= -->
                                        <!-- PERUBAHAN DI SINI: URL disesuaikan menjadi 'laporanasetpariwisata' -->
                                        <!-- ======================================================= -->
                                        <a href="<?= base_url('laporanasetpariwisata/exportPDF/' . $p['id']) ?>" class="btn btn-sm btn-danger">
                                            <i class="fas fa-file-pdf"></i> Export PDF
                                        </a>
                                        <a href="<?= base_url('laporanasetpariwisata/exportExcel/' . $p['id']) ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-file-excel"></i> Export Excel
                                        </a>
                                        <!-- ======================================================= -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data objek wisata. Silakan tambahkan terlebih dahulu.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>