<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Master Aset</h1>

    <!-- Tombol Tambah Aset -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahAset">
        Tambah Aset
    </button>

    <!-- Tabel Data Dummy -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Kondisi</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dummy Data -->
                        <tr>
                            <td>1</td>
                            <td>Produksi</td>
                            <td>Baik</td>
                            <td>Gudang 1</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <!-- Tambahkan data lainnya sesuai kebutuhan -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Modal Tambah Aset -->
<div class="modal fade" id="modalTambahAset" tabindex="-1" role="dialog" aria-labelledby="modalTambahAsetLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= site_url('aset/master/save') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAsetLabel">Tambah Master Aset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Kategori -->
                    <div class="form-group">
                        <label for="kategori">Kategori Aset</label>
                        <input type="text" name="kategori" class="form-control" required>
                    </div>

                    <!-- Kondisi -->
                    <div class="form-group">
                        <label for="kondisi">Kondisi Aset</label>
                        <input type="text" name="kondisi" class="form-control" required>
                    </div>

                    <!-- Lokasi -->
                    <div class="form-group">
                        <label for="lokasi">Lokasi Aset</label>
                        <input type="text" name="lokasi" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>