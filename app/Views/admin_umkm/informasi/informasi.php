<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Informasi UMKM Desa Melung</h1>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Informasi UMKM</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama UMKM</th>
                        <th>Pemilik</th>
                        <th>Alamat</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (!empty($umkm) && is_array($umkm)): ?>
                        <?php $no = 1; foreach ($umkm as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($u['nama_umkm']) ?></td>
                                <td><?= esc($u['pemilik']) ?></td>
                                <td><?= esc($u['alamat']) ?></td>
                                <td>
                                    <?php if (!empty($u['foto_umkm'])): ?>
                                        <img src="<?= base_url('uploads/foto_umkm/' . $u['foto_umkm']) ?>" 
                                             alt="Foto UMKM" width="80" height="80" 
                                             style="object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Belum ada data UMKM</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
