<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen UMKM Desa</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah UMKM Baru</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('umkm/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Nama UMKM</label>
                    <input type="text" class="form-control" name="nama_umkm" placeholder="Contoh: Kopi Sejahtera" required>
                </div>
                <div class="form-group">
                    <label>Pemilik</label>
                    <input type="text" class="form-control" name="pemilik" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select class="form-control" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                        <option value="kerajinan">Kerajinan</option>
                        <option value="agribisnis">Agribisnis</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Tuliskan deskripsi UMKM..."></textarea>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat" rows="2" placeholder="Alamat lengkap UMKM"></textarea>
                </div>
                <div class="form-group">
                    <label>Alamat Google Maps (URL)</label>
                    <input type="url" class="form-control" name="gmaps_url" placeholder="https://maps.google.com/...">
                </div>
                <div class="form-group">
                    <label>Kontak</label>
                    <input type="text" class="form-control" name="kontak" placeholder="081234567890 atau email">
                </div>
                <div class="form-group">
                    <label>Foto UMKM</label>
                    <input type="file" class="form-control-file" name="foto_umkm" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar UMKM</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama UMKM</th>
                        <th>Pemilik</th>
                        <th>Kategori</th> <th>Deskripsi</th>
                        <th>Alamat</th>
                        <th>Google Maps</th>
                        <th>Kontak</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (!empty($umkm) && is_array($umkm)): ?>
                        <?php $no = 1; foreach ($umkm as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($u['nama_umkm']) ?></td>
                                <td><?= esc($u['pemilik']) ?></td>
                                <td><?= esc($u['kategori']) ?></td> <td><?= esc($u['deskripsi']) ?></td>
                                <td><?= esc($u['alamat']) ?></td>
                                <td>
                                    <?php if (!empty($u['gmaps_url'])): ?>
                                        <a href="<?= esc($u['gmaps_url']) ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-map-marker-alt"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($u['kontak']) ?></td>
                                <td>
                                    <?php if (!empty($u['foto_umkm'])): ?>
                                        <img src="<?= base_url('uploads/foto_umkm/' . $u['foto_umkm']) ?>" alt="Foto UMKM" width="70">
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $u['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= base_url('umkm/delete/' . $u['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus UMKM ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal<?= $u['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $u['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="post" action="<?= base_url('umkm/update/' . $u['id']) ?>" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?= $u['id'] ?>">Edit UMKM</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama UMKM</label>
                                                    <input type="text" class="form-control" name="nama_umkm" value="<?= esc($u['nama_umkm']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Pemilik</label>
                                                    <input type="text" class="form-control" name="pemilik" value="<?= esc($u['pemilik']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Kategori</label>
                                                    <select class="form-control" name="kategori" required>
                                                        <option value="makanan" <?= ($u['kategori'] == 'makanan') ? 'selected' : '' ?>>Makanan</option>
                                                        <option value="minuman" <?= ($u['kategori'] == 'minuman') ? 'selected' : '' ?>>Minuman</option>
                                                        <option value="kerajinan" <?= ($u['kategori'] == 'kerajinan') ? 'selected' : '' ?>>Kerajinan</option>
                                                        <option value="agribisnis" <?= ($u['kategori'] == 'agribisnis') ? 'selected' : '' ?>>Agribisnis</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Deskripsi</label>
                                                    <textarea class="form-control" name="deskripsi" rows="3"><?= esc($u['deskripsi']) ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <textarea class="form-control" name="alamat" rows="2"><?= esc($u['alamat']) ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alamat Google Maps (URL)</label>
                                                    <input type="url" class="form-control" name="gmaps_url" value="<?= esc($u['gmaps_url']) ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Kontak</label>
                                                    <input type="text" class="form-control" name="kontak" value="<?= esc($u['kontak']) ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Foto UMKM</label><br>
                                                    <?php if (!empty($u['foto_umkm'])): ?>
                                                        <img src="<?= base_url('uploads/foto_umkm/' . $u['foto_umkm']) ?>" alt="Foto UMKM" width="70" class="mb-2"><br>
                                                    <?php endif; ?>
                                                    <input type="file" class="form-control-file" name="foto_umkm" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Simpan
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Batal
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">Belum ada UMKM</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>