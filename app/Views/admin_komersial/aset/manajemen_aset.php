<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Aset</h1>
    <p>Fungsi: Admin dapat mengedit atau menghapus data aset komersial.</p>

    <!-- Tabel Daftar Aset -->
    <div class="card shadow mt-4">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kode Aset</th>
                        <th>NUP</th>
                        <th>Tahun Perolehan</th>
                        <th>Merk / Type</th>
                        <th>Nilai Perolehan (Rp)</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($asets)) : ?>
                        <?php foreach ($asets as $index => $a) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($a['nama_aset']) ?></td>
                                <td><?= esc($a['kode_aset']) ?></td>
                                <td><?= esc($a['nup']) ?></td>
                                <td><?= esc($a['tahun_perolehan']) ?></td>
                                <td><?= esc($a['merk_type']) ?></td>
                                <td><?= number_format($a['nilai_perolehan'], 0, ',', '.') ?></td>
                                <td><?= esc($a['keterangan']) ?></td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button
                                        class="btn btn-warning btn-sm btn-edit-aset"
                                        data-id="<?= $a['id_aset'] ?>"
                                        data-nama_aset="<?= esc($a['nama_aset']) ?>"
                                        data-kode_aset="<?= esc($a['kode_aset']) ?>"
                                        data-nup="<?= esc($a['nup']) ?>"
                                        data-tahun_perolehan="<?= esc($a['tahun_perolehan']) ?>"
                                        data-merk_type="<?= esc($a['merk_type']) ?>"
                                        data-nilai_perolehan="<?= esc($a['nilai_perolehan']) ?>"
                                        data-keterangan="<?= esc($a['keterangan']) ?>"
                                        data-toggle="modal" data-target="#modalEditAset">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Tombol Delete pakai modal -->
                                    <button
                                        class="btn btn-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#deleteModal<?= $a['id_aset'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Delete -->
                            <div class="modal fade" id="deleteModal<?= $a['id_aset'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus <strong><?= esc($a['nama_aset']) ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <a href="<?= base_url('ManajemenAsetKomersial/delete/' . $a['id_aset']) ?>" class="btn btn-danger">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9">Tidak ada data aset</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Aset -->
<div class="modal fade" id="modalEditAset" tabindex="-1" role="dialog" aria-labelledby="modalEditAsetLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditAset" method="post">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditAsetLabel">Edit Data Aset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_aset" id="editIdAset">

                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_aset" id="editNamaAset" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Aset</label>
                        <input type="text" name="kode_aset" id="editKodeAset" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>NUP</label>
                        <input type="text" name="nup" id="editNup" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan" id="editTahunPerolehan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Merk / Tipe</label>
                        <input type="text" name="merk_type" id="editMerkType" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nilai Perolehan (Rp)</label>
                        <input type="number" name="nilai_perolehan" id="editNilaiPerolehan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" id="editKeterangan" class="form-control">
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

<!-- Script Edit Aset -->
<script>
    $(function() {
        $('.btn-edit-aset').click(function() {
            let id = $(this).data('id');
            $('#formEditAset').attr('action', '<?= site_url('ManajemenAsetKomersial/update') ?>/' + id);

            $('#editIdAset').val(id);
            $('#editNamaAset').val($(this).data('nama_aset'));
            $('#editKodeAset').val($(this).data('kode_aset'));
            $('#editNup').val($(this).data('nup'));
            $('#editTahunPerolehan').val($(this).data('tahun_perolehan'));
            $('#editMerkType').val($(this).data('merk_type'));
            $('#editNilaiPerolehan').val($(this).data('nilai_perolehan'));
            $('#editKeterangan').val($(this).data('keterangan'));
        });
    });
</script>

<?= $this->endSection() ?>