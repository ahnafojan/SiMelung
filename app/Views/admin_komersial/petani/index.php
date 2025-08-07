<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Data Petani</h1>

    <p>Fungsi: Admin dapat menambahkan, mengedit, atau menghapus data petani.</p>

    <div class="mb-3">
        <a href="<?= site_url('petani/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Petani
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($petani)) : ?>
                        <?php foreach ($petani as $index => $row) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($row['user_id']) ?></td>
                                <td><?= esc($row['nama']) ?></td>
                                <td><?= esc($row['alamat']) ?></td>
                                <td><?= esc($row['no_hp']) ?></td>
                                <td>
                                    <a href="<?= site_url('petani/edit/' . $row['id']) ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= site_url('petani/delete/' . $row['id']) ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data petani</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>