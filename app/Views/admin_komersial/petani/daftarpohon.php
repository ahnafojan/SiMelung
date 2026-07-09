<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<style>
    /* Gaya Kustom untuk Tampilan Modern & Minimalis */
    body {
        background-color: #f8f9fa;
    }

    .page-title {
        font-weight: 700;
        color: #343a40;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 1rem;
    }

    .card {
        border: none;
        border-radius: 0.75rem;
    }

    .table thead th {
        background-color: #f1f3f5;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
</style>

<div class="container-fluid py-4">

    <!-- Judul Halaman -->
    <div class="mb-4">
        <h1 class="h3 page-title">Master Jenis Kopi</h1>
        <p class="page-subtitle">Manajemen data untuk semua jenis pohon kopi dan harga terkait.</p>
    </div>

    <div class="row">
        <!-- Kolom Form Tambah & Form Edit Harga -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Jenis Kopi Baru
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('jenispohon/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="nama_jenis" class="font-weight-bold">Nama Jenis Kopi</label>
                            <input type="text" id="nama_jenis" name="nama_jenis" class="form-control" placeholder="Contoh: Arabika Gayo" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                            <i class="fas fa-save mr-2"></i>Simpan Jenis Kopi
                        </button>
                    </form>
                </div>
            </div>

            <!-- Form Input/Edit Harga -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-money-bill-wave mr-2"></i>Atur Harga Jenis Kopi
                    </h6>
                </div>
                <div class="card-body">
                    <form id="formHargaJenisPohon" action="javascript:void(0)" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" id="harga_id" name="id">

                        <div class="form-group">
                            <label for="harga_jenis_pohon_id" class="font-weight-bold">Jenis Kopi</label>
                            <select id="harga_jenis_pohon_id" name="jenis_pohon_id" class="form-control" required>
                                <option value="">-- Pilih Jenis Kopi --</option>
                                <?php foreach ($jenisPohon as $jp): ?>
                                    <option value="<?= $jp['id'] ?>"><?= esc($jp['nama_jenis']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="harga_beli_per_kg" class="font-weight-bold">Harga Kopi (Rp/Kg)</label>
                            <input type="number" step="0.01" min="0" id="harga_beli_per_kg" name="harga_beli_per_kg" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="harga_jual_per_kg" class="font-weight-bold">Harga Jual Bumdes (Rp/Kg)</label>
                            <input type="number" step="0.01" min="0" id="harga_jual_per_kg" name="harga_jual_per_kg" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_berlaku" class="font-weight-bold">Tanggal Berlaku</label>
                            <input type="date" id="tanggal_berlaku" name="tanggal_berlaku" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- tombol baru -->
                        <button type="button" id="btn-kirim-permintaan-harga" class="btn btn-success btn-block shadow-sm">
                            <i class="fas fa-paper-plane mr-2"></i>Simpan & Kirim Permintaan
                        </button>

                        <button type="button" id="btn-batal-edit-harga" class="btn btn-secondary btn-block shadow-sm d-none">
                            <i class="fas fa-times mr-2"></i>Reset
                        </button>

                        <!-- info UX biar user paham -->
                        <small class="text-muted d-block mt-2">
                            Harga tidak akan berubah sebelum disetujui oleh admin Bumdes.
                        </small>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Tabel Daftar -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Daftar Jenis Kopi & Harga Saat Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Nama Jenis Kopi</th>
                                    <th class="text-right">Harga Kopi Saat Ini (Rp/Kg)</th>
                                    <th class="text-right">Harga Jual Saat Ini (Rp/Kg)</th>
                                    <th width="180">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($jenisPohon)): ?>
                                    <?php $i = 1; ?>
                                    <?php foreach ($jenisPohon as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $i++ ?></td>
                                            <td><?= esc($row['nama_jenis']) ?></td>
                                            <td class="text-right">
                                                <?= isset($row['harga_beli_saat_ini']) ? number_format($row['harga_beli_saat_ini'], 0, ',', '.') : '-' ?>
                                            </td>
                                            <td class="text-right">
                                                <?= isset($row['harga_jual_saat_ini']) ? number_format($row['harga_jual_saat_ini'], 0, ',', '.') : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                                    <?php if ($row['edit_status'] == 'approved') : ?>
                                                        <button class="btn btn-warning btn-edit"
                                                            data-id="<?= $row['id'] ?>"
                                                            data-nama="<?= esc($row['nama_jenis']) ?>"
                                                            title="Edit Jenis Pohon">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    <?php elseif ($row['edit_status'] == 'pending') : ?>
                                                        <button class="btn btn-secondary disabled" title="Permintaan edit sedang diproses">
                                                            <i class="fas fa-clock"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button class="btn btn-outline-warning btn-request-access"
                                                            data-jenispohon-id="<?= $row['id'] ?>"
                                                            data-action-type="edit"
                                                            title="Minta Izin Edit">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <?php if ($row['harga_edit_status'] == 'approved') : ?>
                                                        <button class="btn btn-info btn-edit-harga"
                                                            data-id="<?= $row['id'] ?>"
                                                            data-nama="<?= esc($row['nama_jenis']) ?>"
                                                            data-harga-id="<?= $row['harga_id'] ?? '' ?>"
                                                            data-harga-beli="<?= $row['harga_beli_saat_ini'] ?? 0 ?>"
                                                            data-harga-jual="<?= $row['harga_jual_saat_ini'] ?? 0 ?>"
                                                            data-tanggal-berlaku="<?= date('Y-m-d') ?>"
                                                            title="Edit Harga">
                                                            <i class="fas fa-tag"></i>
                                                        </button>
                                                        <?php if ($row['harga_edit_status'] == 'pending') : ?>
                                                            <button class="btn btn-secondary disabled" title="Permintaan harga sedang diproses">
                                                                <i class="fas fa-clock"></i>
                                                            </button>
                                                        <?php else : ?>
                                                            <button class="btn btn-info btn-edit-harga"
                                                                data-id="<?= $row['id'] ?>"
                                                                data-nama="<?= esc($row['nama_jenis']) ?>"
                                                                data-harga-beli="<?= $row['harga_beli_saat_ini'] ?? 0 ?>"
                                                                data-harga-jual="<?= $row['harga_jual_saat_ini'] ?? 0 ?>"
                                                                data-tanggal-berlaku="<?= date('Y-m-d') ?>"
                                                                title="Usulkan perubahan harga">
                                                                <i class="fas fa-tag"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button class="btn btn-outline-info btn-request-access"
                                                            data-jenispohon-id="<?= $row['id'] ?>"
                                                            data-harga-id="<?= $row['harga_id'] ?? 0 ?>"
                                                            data-action-type="harga_edit"
                                                            title="Minta Izin Edit Harga">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <?php if ($row['delete_status'] == 'approved') : ?>
                                                        <button class="btn btn-danger"
                                                            data-toggle="modal"
                                                            data-target="#modalHapusJenisPohon<?= $row['id'] ?>"
                                                            title="Hapus Jenis Pohon">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php elseif ($row['delete_status'] == 'pending') : ?>
                                                        <button class="btn btn-secondary disabled" title="Permintaan hapus sedang diproses">
                                                            <i class="fas fa-clock"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button class="btn btn-outline-danger btn-request-access"
                                                            data-jenispohon-id="<?= $row['id'] ?>"
                                                            data-action-type="delete"
                                                            title="Minta Izin Hapus">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Modal Hapus -->
                                        <div class="modal fade" id="modalHapusJenisPohon<?= $row['id'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <form action="<?= site_url('jenispohon/delete/' . $row['id']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus jenis kopi? <strong><?= esc($row['nama_jenis']) ?></strong>?</p>
                                                            <p class="text-danger small">Tindakan ini tidak dapat diurungkan.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <em>Belum ada data jenis kopi.</em>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Edit Jenis Pohon -->
<div class="modal fade" id="modalEditJenisPohon" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditJenisPohon" action="" method="post">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditLabel">Form Edit Jenis Kopi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_nama_jenis" class="font-weight-bold">Nama Jenis Kopi</label>
                        <input type="text" id="edit_nama_jenis" name="nama_jenis" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Modal Edit Jenis Pohon
        $('#modalEditJenisPohon').on('click', '[data-dismiss="modal"]', function() {
            $('#modalEditJenisPohon').modal('hide');
        });

        // Event handler untuk tombol edit jenis pohon
        $('.btn-edit').on('click', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            $('#edit_id').val(id);
            $('#edit_nama_jenis').val(nama);
            $('#formEditJenisPohon').attr('action', `<?= site_url('jenispohon/update') ?>/${id}`);
            $('#modalEditJenisPohon').modal('show');
        });

        // Event handler untuk tombol "Edit Harga"
        $(document).on('click', '.btn-edit-harga', function() {
            const id = $(this).data('id');
            const hargaId = $(this).data('harga-id');
            const hargaBeli = $(this).data('harga-beli');
            const hargaJual = $(this).data('harga-jual');
            const tanggalBerlaku = $(this).data('tanggal-berlaku');

            $('#harga_jenis_pohon_id').val(id);
            $('#harga_beli_per_kg').val(hargaBeli);
            $('#harga_jual_per_kg').val(hargaJual);
            $('#tanggal_berlaku').val(tanggalBerlaku);
            $('#harga_id').val(hargaId);
            $('#btn-batal-edit-harga').removeClass('d-none');

            $('html, body').animate({
                scrollTop: $('#formHargaJenisPohon').offset().top - 100
            }, 'slow');
        });

        // Event handler untuk tombol "Batal Edit Harga"
        $('#btn-batal-edit-harga').on('click', function() {
            $('#formHargaJenisPohon')[0].reset();
            $('#harga_jenis_pohon_id').val('');
            $('#tanggal_berlaku').val('<?= date('Y-m-d') ?>');
            $('#harga_id').val('');
            $(this).addClass('d-none');
        });

        // AJAX REQUEST ACCESS - FIXED VERSION
        $('#btn-kirim-permintaan-harga').on('click', function() {
            const jenisPohonId = $('#harga_jenis_pohon_id').val();
            const hargaId = $('#harga_id').val(); // ✅ TAMBAH INI
            const hargaBeli = $('#harga_beli_per_kg').val();
            const hargaJual = $('#harga_jual_per_kg').val();
            const tanggalBerlaku = $('#tanggal_berlaku').val();

            if (!jenisPohonId) {
                Swal.fire('Gagal', 'Pilih jenis kopi dulu.', 'error');
                return;
            }
            if (!hargaBeli || !hargaJual || !tanggalBerlaku) {
                Swal.fire('Gagal', 'Harga beli, harga jual, dan tanggal berlaku wajib diisi.', 'error');
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');

            $.ajax({
                url: "<?= site_url('jenispohon/requestAccess') ?>",
                method: "POST",
                dataType: "json",
                data: {
                    jenispohon_id: jenisPohonId,
                    harga_id: hargaId,
                    action_type: 'harga_edit',

                    // ✅ simpan ke kolom baru permission_requests
                    requested_jenis_pohon_id: jenisPohonId,
                    requested_harga_beli_per_kg: hargaBeli,
                    requested_harga_jual_per_kg: hargaJual,
                    requested_tanggal_berlaku: tanggalBerlaku,

                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil', res.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message || 'Permintaan gagal.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Koneksi/server bermasalah.', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false)
                        .html('<i class="fas fa-paper-plane mr-2"></i>Simpan & Kirim Permintaan (Approval Bumdes)');
                }
            });
        });
        // ✅ HANDLER UNTUK TOMBOL REQUEST ACCESS (EDIT / DELETE / HARGA_EDIT / HARGA_DELETE)
        $(document).on('click', '.btn-request-access', function() {
            const btn = $(this);

            const jenisPohonId = btn.data('jenispohon-id'); // wajib untuk semua request jenis_pohon
            const actionType = btn.data('action-type'); // edit | delete | harga_edit | harga_delete
            const hargaId = btn.data('harga-id') ?? ''; // optional (khusus harga)

            if (!jenisPohonId || !actionType) {
                Swal.fire('Gagal', 'Data tombol tidak lengkap (jenispohon_id / action_type).', 'error');
                return;
            }

            Swal.fire({
                title: 'Kirim permintaan izin?',
                text: 'Permintaan akan dikirim ke admin Bumdes untuk disetujui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (!res.isConfirmed) return;

                btn.prop('disabled', true);

                $.ajax({
                    url: "<?= site_url('jenispohon/requestAccess') ?>",
                    method: "POST",
                    dataType: "json",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }, // ✅ penting biar controller anggap AJAX
                    data: {
                        jenispohon_id: jenisPohonId,
                        harga_id: hargaId, // kosong untuk edit/delete biasa, ada untuk harga
                        action_type: actionType, // edit/delete/harga_edit/harga_delete
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Berhasil', response.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', response.message || 'Permintaan gagal.', 'error');
                            btn.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'Server/koneksi bermasalah.', 'error');
                        btn.prop('disabled', false);
                    }
                });
            });
        });

    });
</script>

<?= $this->endSection() ?>