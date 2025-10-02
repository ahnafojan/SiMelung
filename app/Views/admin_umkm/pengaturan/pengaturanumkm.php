<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>

    <!-- Tombol Export -->
    <div class="mb-3">
        <a href="<?= base_url('pengaturanumkm/exportUmkmExcel') ?>" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="<?= base_url('pengaturanumkm/exportUmkmPdf') ?>" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>

    <!-- Tabel Data UMKM -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data UMKM Desa Melung</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama UMKM</th>
                        <th>Deskripsi</th>
                        <th>Pemilik</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $umkmModel = new \App\Models\UmkmModel();
                    $umkm = $umkmModel->findAll();
                    ?>
                    <?php if (!empty($umkm)): ?>
                        <?php $no=1; foreach ($umkm as $u): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= esc($u['nama_umkm']) ?></td>
                                <td><?= esc($u['deskripsi']) ?></td>
                                <td><?= esc($u['pemilik']) ?></td>
                                <td><?= esc($u['alamat']) ?></td>
                                <td><?= esc($u['kontak']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data UMKM</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
