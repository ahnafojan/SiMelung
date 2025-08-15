<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kopi Masuk</h1>
    <p>Fungsi: Mencatat kopi yang disetor oleh petani ke BUMDes.</p>

    <!-- Tombol Tambah Data -->
    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKopi">
            <i class="fas fa-plus"></i> Tambah Data Kopi Masuk
        </button>
    </div>

    <!-- Modal Form Tambah Data -->
    <div class="modal fade" id="modalTambahKopi" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url('kopi-masuk/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Form Tambah Kopi Masuk</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Petani</label>
                            <select name="petani_user_id" class="form-control">
                                <?php foreach ($petani as $p): ?>
                                    <option value="<?= $p['user_id'] ?>">
                                        <?= $p['user_id'] ?> - <?= $p['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="form-group">
                            <label>Jumlah (Kg)</label>
                            <input type="number" name="jumlah" class="form-control" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Setor</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer px-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Kopi Masuk -->
    <div class="card shadow mt-4">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Nama Petani</th>
                        <th>Jumlah (Kg)</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (!empty($kopiMasuk)): ?>
                        <?php foreach ($kopiMasuk as $index => $k): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($k['nama_petani']) ?></td>
                                <td><?= esc($k['jumlah']) ?></td>
                                <td><?= esc($k['tanggal']) ?></td>
                                <td><?= esc($k['keterangan']) ?></td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditKopi<?= $k['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Tombol Hapus -->
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopi<?= $k['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEditKopi<?= $k['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="<?= base_url('kopi-masuk/update/' . $k['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title">Edit Data Kopi Masuk</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Petani</label>
                                                    <select name="petani_user_id" class="form-control">
                                                        <?php foreach ($petani as $p): ?>
                                                            <option value="<?= $p['user_id'] ?>">
                                                                <?= $p['user_id'] ?> - <?= $p['nama'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jumlah (Kg)</label>
                                                    <input type="number" name="jumlah" class="form-control" value="<?= esc($k['jumlah']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <input type="date" name="tanggal" class="form-control" value="<?= esc($k['tanggal']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="2"><?= esc($k['keterangan']) ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="modalHapusKopi<?= $k['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="<?= base_url('kopi-masuk/delete/' . $k['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus data kopi masuk dari <strong><?= esc($k['nama_petani']) ?></strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>