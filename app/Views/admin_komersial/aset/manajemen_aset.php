<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Manajemen Aset</h1>
            <p class="mb-0 page-subtitle text-muted">Kelola data aset komersial, termasuk melihat, mengedit, dan menghapus.</p>
        </div>
        <a href="<?= base_url('aset-komersial') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Tambah Aset Baru
        </a>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th class="text-start">#</th>
                            <th>Foto Aset</th>
                            <th>Nama Barang</th>
                            <th>Kode & NUP</th>
                            <th>Tahun & Merek</th>
                            <th>Nilai Perolehan</th>
                            <th>Pengadaan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($asets)) : ?>
                            <?php foreach ($asets as $index => $a) : ?>
                                <tr>
                                    <td class="text-start fw-bold"><?= $index + 1 ?></td>
                                    <td>
                                        <a href="<?= base_url('uploads/foto_aset/' . $a['foto']) ?>" data-lightbox="aset-images" data-title="<?= esc($a['nama_aset']) ?>">
                                            <img src="<?= base_url('uploads/foto_aset/' . $a['foto']) ?>" alt="<?= esc($a['nama_aset']) ?>" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                        </a>
                                    </td>

                                    <td><?= esc($a['nama_aset']) ?></td>
                                    <td>
                                        <span class="fw-bold d-block"><?= esc($a['kode_aset']) ?></span>
                                        <small class="text-muted">NUP: <?= esc($a['nup']) ?: '-' ?></small>
                                    </td>
                                    <td>
                                        <span class="fw-bold d-block"><?= esc($a['tahun_perolehan']) ?></span>
                                        <small class="text-muted"><?= esc($a['merk_type']) ?: '-' ?></small>
                                    </td>
                                    <td>Rp <?= number_format($a['nilai_perolehan'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="d-block"><?= esc($a['metode_pengadaan']) ?></span>
                                        <small class="text-muted"><?= esc($a['sumber_pengadaan']) ?></small>
                                    </td>

                                    <td class="text-muted fst-italic"><?= esc($a['keterangan']) ?: 'Tidak ada keterangan' ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($a['can_edit']): ?>
                                                <button class="btn btn-sm btn-outline-warning mx-1 btn-edit-aset"
                                                    data-id="<?= $a['id_aset'] ?>"
                                                    data-nama_aset="<?= esc($a['nama_aset']) ?>"
                                                    data-kode_aset="<?= esc($a['kode_aset']) ?>"
                                                    data-nup="<?= esc($a['nup']) ?>"
                                                    data-tahun_perolehan="<?= esc($a['tahun_perolehan']) ?>"
                                                    data-merk_type="<?= esc($a['merk_type']) ?>"
                                                    data-nilai_perolehan="<?= esc($a['nilai_perolehan']) ?>"
                                                    data-keterangan="<?= esc($a['keterangan']) ?>"
                                                    data-metode_pengadaan="<?= esc($a['metode_pengadaan']) ?>"
                                                    data-sumber_pengadaan="<?= esc($a['sumber_pengadaan']) ?>"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditAset">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-warning mx-1 btn-request-access"
                                                    data-aset-id="<?= $a['id_aset'] ?>"
                                                    data-action-type="edit" title="Minta Izin Edit">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($a['can_delete']): ?>
                                                <button class="btn btn-sm btn-outline-danger mx-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal<?= $a['id_aset'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-danger mx-1 btn-request-access"
                                                    data-aset-id="<?= $a['id_aset'] ?>"
                                                    data-action-type="delete" title="Minta Izin Hapus">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal<?= $a['id_aset'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus aset <strong><?= esc($a['nama_aset']) ?></strong>?
                                                Aksi ini tidak dapat dibatalkan.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <a href="<?= base_url('ManajemenAsetKomersial/delete/' . $a['id_aset']) ?>" class="btn btn-danger">Hapus Permanen</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <p class="fw-bold mb-0">Belum ada data aset yang dicatat.</p>
                                        <p>Silakan tambahkan data aset baru melalui tombol di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditAset" tabindex="-1" aria-labelledby="modalEditAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formEditAset" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditAsetLabel">Edit Data Aset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_aset" id="editIdAset">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Barang / Aset</label>
                            <input type="text" name="nama_aset" id="editNamaAset" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kode Aset</label>
                            <input type="text" name="kode_aset" id="editKodeAset" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NUP</label>
                            <input type="text" name="nup" id="editNup" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun Perolehan</label>
                            <input type="number" name="tahun_perolehan" id="editTahunPerolehan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Merk / Tipe</label>
                            <input type="text" name="merk_type" id="editMerkType" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nilai Perolehan (Rp)</label>
                            <input type="number" name="nilai_perolehan" id="editNilaiPerolehan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" id="editKeterangan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Metode Pengadaan</label>
                            <select name="metode_pengadaan" id="editMetodePengadaan" class="form-select" required>
                                <option value="">-- Pilih Jenis Pengadaan --</option>
                                <option value="Hibah">Hibah</option>
                                <option value="Pembelian">Pembelian</option>
                                <option value="Penyewaan">Penyewaan</option>
                                <option value="Peminjaman">Peminjaman</option>
                                <option value="Penukaran">Penukaran</option>
                                <option value="Pembuatan sendiri">Pembuatan sendiri</option>
                                <option value="Perbaikan/rekondisi">Perbaikan/rekondisi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sumber Pengadaan</label>
                            <input type="text" name="sumber_pengadaan" id="editSumberPengadaan" class="form-control" required>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Foto Aset (opsional)</label>
                            <input type="file" name="foto" id="editFoto" class="form-control" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            $('#editMetodePengadaan').val($(this).data('metode_pengadaan'));
            $('#editSumberPengadaan').val($(this).data('sumber_pengadaan'));

        });
    });
    $('.btn-request-access').on('click', function() {
        const button = $(this);
        const asetId = button.data('aset-id');
        const action = button.data('action-type');

        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "<?= site_url('ManajemenAsetKomersial/requestAccess') ?>",
            method: "POST",
            data: {
                aset_id: asetId,
                action_type: action,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                    button.removeClass('btn-outline-warning btn-outline-danger').addClass('btn-secondary disabled')
                        .html('<i class="fas fa-clock"></i>');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                    button.prop('disabled', false).html('<i class="fas fa-lock"></i>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan koneksi.'
                });
                button.prop('disabled', false).html('<i class="fas fa-lock"></i>');
            }
        });
    });
</script>

<?= $this->endSection() ?>