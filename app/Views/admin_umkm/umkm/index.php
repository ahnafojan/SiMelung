<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen UMKM Desa</h1>

    <!-- Form Tambah UMKM -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah UMKM Baru</h6>
        </div>
        <div class="card-body">
            <form action="#" method="post">
                <div class="form-group">
                    <label>Nama UMKM</label>
                    <input type="text" class="form-control" name="nama_umkm" placeholder="Contoh: Kopi Sejahtera">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Tuliskan deskripsi UMKM..."></textarea>
                </div>
                <div class="form-group">
                    <label>Nama Pemilik</label>
                    <input type="text" class="form-control" name="pemilik" placeholder="Contoh: Budi Santoso">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat" rows="2" placeholder="Alamat lengkap UMKM"></textarea>
                </div>
                <div class="form-group">
                    <label>Kontak</label>
                    <input type="text" class="form-control" name="kontak" placeholder="Contoh: 081234567890 atau email">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Tabel UMKM -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar UMKM</h6>
        </div>
        <div class="card shadow">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama UMKM</th>
                            <th>Deskripsi</th>
                            <th>Pemilik</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <!-- Dummy Data -->
                        <tr>
                            <td>1</td>
                            <td>Kopi Sejahtera</td>
                            <td>Produksi kopi robusta dengan kemasan sachet</td>
                            <td>Budi Santoso</td>
                            <td>Balai Desa</td>
                            <td>081234567890</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus UMKM ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit UMKM -->
    <div class="modal fade" id="editModal1" tabindex="-1" role="dialog" aria-labelledby="editModalLabel1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="#">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel1">Edit UMKM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama UMKM</label>
                            <input type="text" class="form-control" name="nama_umkm" value="Kopi Sejahtera">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3">Produksi kopi robusta dengan kemasan sachet</textarea>
                        </div>
                        <div class="form-group">
                            <label>Pemilik</label>
                            <input type="text" class="form-control" name="pemilik" value="Budi Santoso">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2">Balai Desa</textarea>
                        </div>
                        <div class="form-group">
                            <label>Kontak</label>
                            <input type="text" class="form-control" name="kontak" value="081234567890">
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