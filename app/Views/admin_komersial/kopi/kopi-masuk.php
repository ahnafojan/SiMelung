<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Kopi Masuk</h1>
            <p class="mb-0 page-subtitle">Fungsi: Mencatat kopi yang disetor oleh petani ke BUMDes.</p>
        </div>
    </div>

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
                        <!-- Dropdown Petani -->
                        <div class="form-group">
                            <label>Nama Petani</label>
                            <select id="petani" name="petani_user_id" class="form-control" required>
                                <option value="">-- Pilih Petani --</option>
                                <?php foreach ($petani as $p): ?>
                                    <option value="<?= $p['user_id'] ?>">
                                        <?= $p['user_id'] ?> - <?= $p['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <!-- Dropdown Jenis Pohon -->
                        <div class="form-group">
                            <label>Jenis Pohon</label>
                            <select id="jenis_pohon" name="petani_pohon_id" class="form-control" required>
                                <option value="">-- Pilih Jenis Pohon --</option>
                            </select>
                        </div>


                        <!-- Input Jumlah -->
                        <div class="form-group">
                            <label for="jumlah">Jumlah (Kg)</label>
                            <input type="number" step="0.01" min="0"
                                class="form-control"
                                id="jumlah" name="jumlah"
                                placeholder="Masukkan jumlah kopi Kg">
                        </div>


                        <!-- Input Tanggal -->
                        <div class="form-group">
                            <label>Tanggal Setor</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Input Keterangan -->
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukan Keterangan jika ada"></textarea>
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
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Petani</th>
                        <th>Jenis Pohon Kopi</th>
                        <th>Tanggal</th>
                        <th>Stok Saat Ini (Kg)</th>
                        <th>Kopi Masuk Harian (Kg)</th>
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
                                <td><?= esc($k['nama_pohon']) ?></td>
                                <td><?= esc($k['tanggal']) ?></td>
                                <td><?= esc($k['stok'] ?? 0) ?> Kg</td>
                                <td><?= esc($k['jumlah']) ?> Kg</td>
                                <td><?= esc($k['keterangan']) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($k['can_edit']): ?>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditKopi<?= $k['id'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-warning btn-request-access"
                                                data-kopimasuk-id="<?= $k['id'] ?>"
                                                data-action-type="edit" title="Minta Izin Edit">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($k['can_delete']): ?>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopi<?= $k['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-danger btn-request-access"
                                                data-kopimasuk-id="<?= $k['id'] ?>"
                                                data-action-type="delete" title="Minta Izin Hapus">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
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

                                                <!-- Pilih Petani -->
                                                <div class="form-group">
                                                    <label>Nama Petani</label>
                                                    <select name="petani_user_id" class="form-control">
                                                        <?php foreach ($petani as $p): ?>
                                                            <option value="<?= $p['user_id'] ?>" <?= $p['user_id'] == $k['petani_user_id'] ? 'selected' : '' ?>>
                                                                <?= $p['user_id'] ?> - <?= $p['nama'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <!-- Pilih Jenis Pohon -->
                                                <div class="form-group">
                                                    <label for="jenis_pohon">Jenis Pohon</label>
                                                    <select id="jenis_pohon_edit_<?= $k['id'] ?>" name="petani_pohon_id" class="form-control jenis-pohon-dropdown" data-petani="<?= $k['petani_user_id'] ?>" data-selected="<?= $k['petani_pohon_id'] ?>" required>
                                                        <option value="">-- Pilih Jenis Pohon --</option>
                                                        <!-- Akan diisi melalui JS -->
                                                    </select>
                                                </div>



                                                <!-- Jumlah -->
                                                <div class="form-group">
                                                    <label>Jumlah (Kg)</label>
                                                    <input type="number" step="0.01" min="0" name="jumlah" class="form-control" value="<?= esc($k['jumlah']) ?>" required>
                                                </div>

                                                <!-- Tanggal -->
                                                <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <input type="date" name="tanggal" class="form-control" value="<?= esc($k['tanggal']) ?>" required>
                                                </div>

                                                <!-- Keterangan -->
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
                                                Apakah Anda yakin ingin menghapus data kopi masuk dari
                                                <strong><?= esc($k['nama_petani']) ?></strong> (<?= esc($k['nama_pohon']) ?>)?
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
<script>
    $(document).ready(function() {
        // ------------------------------
        // FORM CREATE
        // ------------------------------
        $('#petani').change(function() {
            let petaniId = $(this).val();
            let $jenisPohon = $('#jenis_pohon');

            $jenisPohon.prop('disabled', true).html('<option>Loading...</option>');

            if (petaniId) {
                $.getJSON("<?= base_url('get-jenis-pohon') ?>/" + petaniId, function(data) {
                    let options = '<option value="">-- Pilih Jenis Pohon --</option>';
                    $.each(data, function(i, item) {
                        options += `<option value="${item.id}">${item.nama_jenis}</option>`;
                    });
                    $jenisPohon.html(options).prop('disabled', false);
                });
            } else {
                $jenisPohon.html('<option value="">-- Pilih Jenis Pohon --</option>').prop('disabled', true);
            }
        });

        // ------------------------------
        // FORM EDIT (modal)
        // ------------------------------
        $('.modal').on('shown.bs.modal', function() {
            let $jenisPohon = $(this).find('.jenis-pohon-dropdown');
            let petaniId = $jenisPohon.data('petani'); // user_id petani
            let selectedId = $jenisPohon.data('selected'); // id pohon yang tersimpan

            if (petaniId) {
                $.getJSON("<?= base_url('get-jenis-pohon') ?>/" + petaniId, function(data) {
                    let options = '<option value="">-- Pilih Jenis Pohon --</option>';
                    $.each(data, function(i, item) {
                        let selected = (item.id == selectedId) ? 'selected' : '';
                        options += `<option value="${item.id}" ${selected}>${item.nama_jenis}</option>`;
                    });
                    $jenisPohon.html(options).prop('disabled', false);
                });
            }
        });

        // ------------------------------
        // Kalau di modal edit user ganti petani
        // ------------------------------
        $(document).on('change', '.modal select[name="petani_user_id"]', function() {
            let $modal = $(this).closest('.modal');
            let petaniId = $(this).val();
            let $jenisPohon = $modal.find('.jenis-pohon-dropdown');

            $jenisPohon.prop('disabled', true).html('<option>Loading...</option>');

            if (petaniId) {
                $.getJSON("<?= base_url('get-jenis-pohon') ?>/" + petaniId, function(data) {
                    let options = '<option value="">-- Pilih Jenis Pohon --</option>';
                    $.each(data, function(i, item) {
                        options += `<option value="${item.id}">${item.nama_jenis}</option>`;
                    });
                    $jenisPohon.html(options).prop('disabled', false);
                });
            } else {
                $jenisPohon.html('<option value="">-- Pilih Jenis Pohon --</option>').prop('disabled', true);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // ... (script Anda yang sudah ada untuk dropdown biarkan saja)

        // SCRIPT BARU UNTUK PERMINTAAN IZIN
        $('.btn-request-access').on('click', function() {
            const button = $(this);
            const kopiMasukId = button.data('kopimasuk-id');
            const action = button.data('action-type');

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: "<?= site_url('kopi-masuk/requestAccess') ?>",
                method: "POST",
                data: {
                    kopimasuk_id: kopiMasukId,
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
    });
</script>
<?= $this->endSection() ?>