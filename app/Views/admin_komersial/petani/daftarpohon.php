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
        <h1 class="h3 page-title">Master Jenis Pohon</h1>
        <p class="page-subtitle">Manajemen data untuk semua jenis pohon kopi yang terdaftar.</p>
    </div>

    <div class="row">
        <!-- Kolom Form Tambah -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Jenis Pohon Baru
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('jenispohon/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="nama_jenis" class="font-weight-bold">Nama Jenis Pohon</label>
                            <input type="text" id="nama_jenis" name="nama_jenis" class="form-control" placeholder="Contoh: Arabika Gayo" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Tabel Daftar -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Daftar Jenis Pohon
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Nama Jenis Pohon</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($jenisPohon)): ?>
                                    <?php foreach ($jenisPohon as $i => $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $i + 1 ?></td>
                                            <td><?= esc($row['nama_jenis']) ?></td>
                                            <td class="text-center">
                                                <div class="btn-group">

                                                    <?php if ($row['edit_status'] == 'approved') : ?>
                                                        <button class="btn btn-warning btn-sm btn-edit"
                                                            data-id="<?= $row['id'] ?>"
                                                            data-nama="<?= esc($row['nama_jenis']) ?>"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    <?php elseif ($row['edit_status'] == 'pending') : ?>
                                                        <button class="btn btn-sm btn-secondary disabled" title="Permintaan edit sedang diproses">
                                                            <i class="fas fa-clock"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button class="btn btn-sm btn-outline-warning btn-request-access"
                                                            data-jenispohon-id="<?= $row['id'] ?>"
                                                            data-action-type="edit" title="Minta Izin Edit">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <?php if ($row['delete_status'] == 'approved') : ?>
                                                        <button class="btn btn-danger btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#modalHapusJenisPohon<?= $row['id'] ?>"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php elseif ($row['delete_status'] == 'pending') : ?>
                                                        <button class="btn btn-sm btn-secondary disabled" title="Permintaan hapus sedang diproses">
                                                            <i class="fas fa-clock"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button class="btn btn-sm btn-outline-danger btn-request-access"
                                                            data-jenispohon-id="<?= $row['id'] ?>"
                                                            data-action-type="delete" title="Minta Izin Hapus">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal Hapus untuk setiap baris -->
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
                                                            <p>Apakah Anda yakin ingin menghapus jenis pohon <strong><?= esc($row['nama_jenis']) ?></strong>?</p>
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
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <em>Belum ada data jenis pohon.</em>
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

<!-- Modal Form Edit Data (Satu untuk semua) -->
<div class="modal fade" id="modalEditJenisPohon" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditJenisPohon" action="" method="post">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditLabel">Form Edit Jenis Pohon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_nama_jenis" class="font-weight-bold">Nama Jenis Pohon</label>
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

<!-- =============================================================================== -->
<!-- SCRIPT DITEMPATKAN DI SINI SESUAI STRUKTUR ANDA -->
<!-- =============================================================================== -->
<script>
    $(document).ready(function() {

        // --- BLOK UNTUK MEMPERBAIKI TOMBOL BATAL & X PADA MODAL EDIT ---
        $('#modalEditJenisPohon').on('click', '[data-dismiss="modal"]', function() {
            $('#modalEditJenisPohon').modal('hide');
        });
        // -----------------------------------------------------------

        // Event handler untuk tombol edit
        $('.btn-edit').on('click', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            // Isi form modal edit
            $('#edit_id').val(id);
            $('#edit_nama_jenis').val(nama);

            // Atur action form
            $('#formEditJenisPohon').attr('action', `<?= site_url('jenispohon/update') ?>/${id}`);

            // Tampilkan modal
            $('#modalEditJenisPohon').modal('show');
        });

        // --- AJAX REQUEST ACCESS DENGAN RELOAD HALAMAN ---
        $('.btn-request-access').on('click', function() {
            const button = $(this);
            const jenisPohonId = button.data('jenispohon-id');
            const action = button.data('action-type');

            // Ambil CSRF dari meta tag (lebih aman)
            const csrfTokenMeta = document.head.querySelector('meta[name="csrf_token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.content : null;
            const csrfHash = '<?= csrf_hash() ?>';

            if (!csrfToken) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Token CSRF tidak ditemukan.'
                });
                return;
            }

            // Nonaktifkan tombol + spinner
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: "<?= site_url('jenispohon/requestAccess') ?>",
                method: "POST",
                data: {
                    jenispohon_id: jenisPohonId,
                    action_type: action,
                    [csrfToken]: csrfHash
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            // 🔁 Reload halaman agar PHP render ulang status tombol
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });

                        // Kembalikan tombol ke bentuk awal
                        button.prop('disabled', false);
                        if (action === 'edit') {
                            button.html('<i class="fas fa-lock"></i>');
                        } else {
                            button.html('<i class="fas fa-lock"></i>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan koneksi. Coba lagi.'
                    });

                    console.error('AJAX Error:', error);
                    console.error('Response:', xhr.responseText);

                    button.prop('disabled', false);
                    if (action === 'edit') {
                        button.html('<i class="fas fa-lock"></i>');
                    } else {
                        button.html('<i class="fas fa-lock"></i>');
                    }
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>