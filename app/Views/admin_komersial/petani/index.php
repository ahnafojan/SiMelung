<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Data Petani</h1>
    <p>Fungsi: Admin dapat menambahkan, mengedit, atau menghapus data petani.</p>

    <!-- Tombol Tambah Petani -->
    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahPetani">
            <i class="fas fa-plus"></i> Tambah Petani
        </button>
    </div>

    <!-- Modal Form Tambah Petani -->
    <div class="modal fade" id="modalTambahPetani" tabindex="-1" role="dialog" aria-labelledby="modalTambahPetaniLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formTambahPetani" method="post" action="<?= site_url('petani/create') ?>">
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTambahPetaniLabel">Form Tambah Petani</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Petani</label>
                            <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama petani" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No HP</label>
                            <input type="text" id="no_hp" name="no_hp" class="form-control" placeholder="Masukkan nomor HP" required>
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

    <!-- Tabel Daftar Petani -->
    <div class="card shadow mt-4">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center">
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
                                    <button
                                        class="btn btn-warning btn-sm btn-edit-petani"
                                        data-id="<?= esc($row['id']) ?>"
                                        data-user_id="<?= esc($row['user_id']) ?>"
                                        data-nama="<?= esc($row['nama']) ?>"
                                        data-alamat="<?= esc($row['alamat']) ?>"
                                        data-no_hp="<?= esc($row['no_hp']) ?>"
                                        data-toggle="modal" data-target="#modalEditPetani">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button
                                        class="btn btn-danger btn-sm btn-delete-petani"
                                        data-id="<?= esc($row['id']) ?>"
                                        data-nama="<?= esc($row['nama']) ?>"
                                        data-toggle="modal" data-target="#modalHapusPetani">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Tidak ada data petani</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Petani -->
<div class="modal fade" id="modalEditPetani" tabindex="-1" role="dialog" aria-labelledby="modalEditPetaniLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditPetani" method="post" action="<?= site_url('petani/update') ?>">
            <input type="hidden" name="id" id="editPetaniId" value="">
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditPetaniLabel">Edit Data Petani</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editUserId">User ID</label>
                        <input type="text" id="editUserId" name="user_id" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editNama">Nama Petani</label>
                        <input type="text" id="editNama" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editAlamat">Alamat</label>
                        <textarea id="editAlamat" name="alamat" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editNoHp">No HP</label>
                        <input type="text" id="editNoHp" name="no_hp" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer px-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Petani -->
<div class="modal fade" id="modalHapusPetani" tabindex="-1" role="dialog" aria-labelledby="modalHapusPetaniLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formHapusPetani" method="post">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalHapusPetaniLabel">Konfirmasi Hapus Data Petani</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data petani <strong id="hapusPetaniNama"></strong>?
                </div>
                <div class="modal-footer px-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        // Isi data modal edit
        $('.btn-edit-petani').click(function() {
            let id = $(this).data('id');
            let user_id = $(this).data('user_id');
            let nama = $(this).data('nama');
            let alamat = $(this).data('alamat');
            let no_hp = $(this).data('no_hp');

            $('#editPetaniId').val(id);
            $('#editUserId').val(user_id);
            $('#editNama').val(nama);
            $('#editAlamat').val(alamat);
            $('#editNoHp').val(no_hp);
        });

        // Isi data modal hapus
        $('.btn-delete-petani').click(function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            $('#hapusPetaniNama').text(nama);
            $('#formHapusPetani').attr('action', '<?= site_url('petani/delete') ?>/' + id);
        });
    });
</script>
<?= $this->endSection() ?>