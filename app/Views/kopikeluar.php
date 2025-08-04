<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kopi Keluar</h1>
    <p>Fungsi: Mencatat kopi yang keluar karena terjual atau digunakan.</p>

    <div class="mb-3">
        <a href="<?= site_url('kopikeluar/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Data Kopi Keluar
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Tujuan</th>
                        <th>Jumlah (Kg)</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kopikeluar)) : ?>
                        <?php foreach ($kopikeluar as $index => $row) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($row['tujuan']) ?></td>
                                <td><?= esc($row['jumlah']) ?></td>
                                <td><?= esc($row['tanggal']) ?></td>
                                <td>
                                    <a href="<?= site_url('kopikeluar/edit/' . $row['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="<?= site_url('kopikeluar/delete/' . $row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data kopi keluar</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>