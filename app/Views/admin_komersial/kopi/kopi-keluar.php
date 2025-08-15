<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kopi Keluar</h1>
    <p>Fungsi: Mencatat kopi yang keluar karena terjual atau digunakan.</p>

    <!-- Button Tambah -->
    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
            <i class="fas fa-plus"></i> Tambah Data Kopi Keluar
        </button>
    </div>

    <!-- Table -->
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Tujuan</th>
                        <th>Jumlah (Kg)</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
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
                                <td><?= esc($row['keterangan']) ?></td>
                                <td>
                                    <a href="<?= site_url('kopikeluar/delete/' . $row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data kopi keluar</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= site_url('kopikeluar/store') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Data Kopi Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tujuan</label>
                        <input type="text" name="tujuan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah (Kg)</label>
                        <input type="number" step="0.01" name="jumlah" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>