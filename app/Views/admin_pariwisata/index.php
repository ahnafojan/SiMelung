<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Pariwisata Desa</h1>

    <!-- Form Tambah Objek Wisata -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Objek Wisata Baru</h6>
        </div>
        <div class="card-body">
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Objek Wisata</label>
                    <input type="text" class="form-control" name="nama_wisata" placeholder="Contoh: Pagubugan Melung">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Tuliskan deskripsi objek wisata..."></textarea>
                </div>
                <div class="form-group">
                    <label>Pengelola</label>
                    <input type="text" class="form-control" name="pengelola" placeholder="Contoh: Pak Maman">
                </div>
                <div class="form-group">
                    <label>Kontak</label>
                    <input type="text" class="form-control" name="kontak" placeholder="Contoh: 081234567890 atau email">
                </div>
                <div class="form-group">
                    <label>Foto Objek Wisata</label>
                    <input type="file" class="form-control" name="foto_wisata">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Tabel Objek Wisata -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Objek Wisata</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Objek Wisata</th>
                        <th>Deskripsi</th>
                        <th>Pengelola</th>
                        <th>Kontak</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <!-- Dummy Data -->
                    <tr>
                        <td>1</td>
                        <td>Pagubugan Melung</td>
                        <td>Kolam renang tengah sawah dengan pemandangan asri</td>
                        <td>Pak Yanto</td>
                        <td>081234567890</td>
                        <td>
                            <img src="<?= base_url('uploads/wisata/air_terjun.jpg') ?>" alt="Foto Wisata" width="100">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus objek wisata ini?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Objek Wisata -->
    <div class="modal fade" id="editModal1" tabindex="-1" role="dialog" aria-labelledby="editModalLabel1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="#" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel1">Edit Objek Wisata</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Objek Wisata</label>
                            <input type="text" class="form-control" name="nama_wisata" value="Pagubugan Melung">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3">Kolam renang tengah sawah dengan pemandangan asri</textarea>
                        </div>
                        <div class="form-group">
                            <label>Pengelola</label>
                            <input type="text" class="form-control" name="pengelola" value="Pak Maman">
                        </div>
                        <div class="form-group">
                            <label>Kontak</label>
                            <input type="text" class="form-control" name="kontak" value="081234567890">
                        </div>
                        <div class="form-group">
                            <label>Foto Objek Wisata</label>
                            <input type="file" class="form-control" name="foto_wisata">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary" type="submit" title="Simpan Perubahan">
                            <i class="fas fa-save"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" title="Batal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
