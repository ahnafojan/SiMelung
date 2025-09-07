<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Data Petani</h1>
            <p class="mb-0 page-subtitle">Fungsi: Admin dapat menambahkan, mengedit, atau menghapus data petani.</p>
        </div>
    </div>

    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahPetani">
            <i class="fas fa-plus"></i> Tambah Petani
        </button>
    </div>

    <div class="modal fade" id="modalTambahPetani" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formTambahPetani" method="post" action="<?= site_url('petani/create') ?>" enctype="multipart/form-data">
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Form Tambah Petani</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $fields = [
                            ['nama', 'text', 'Nama Petani', 'Masukkan nama petani'],
                            ['alamat', 'textarea', 'Alamat', 'Masukkan alamat'],
                            ['no_hp', 'text', 'No HP', 'Masukkan nomor HP'],
                            ['usia', 'number', 'Usia', 'Masukkan usia petani'],
                            ['tempat_lahir', 'text', 'Tempat Lahir', 'Masukkan tempat lahir'],
                            ['tanggal_lahir', 'date', 'Tanggal Lahir', ''],
                        ];
                        foreach ($fields as $f) :
                            if ($f[1] === 'textarea') {
                                echo "<div class='form-group'>
                                    <label>{$f[2]}</label>
                                    <textarea name='{$f[0]}' class='form-control' placeholder='{$f[3]}' required></textarea>
                                </div>";
                            } else {
                                $step = $f[4] ?? '';
                                echo "<div class='form-group'>
                                    <label>{$f[2]}</label>
                                    <input type='{$f[1]}' name='{$f[0]}' class='form-control' placeholder='{$f[3]}' {$step}>
                                </div>";
                            }
                        endforeach;
                        ?>
                        <div class="form-group">
                            <label>Foto Petani</label><br>
                            <img id="previewFotoInput" src="https://via.placeholder.com/80"
                                style="width:80px;height:80px;object-fit:cover;margin-bottom:5px;">
                            <input type="file" id="foto" name="foto" class="form-control-file" accept="image/*">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mt-4 d-none d-lg-block">
        <div class="card-body">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Usia</th>
                        <th>TTL</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($petani)): ?>
                        <?php $nomor = ($currentPage - 1) * $perPage + 1; ?>
                        <?php foreach ($petani as $row): ?>
                            <tr class="align-middle">
                                <td><?= $nomor++ ?></td>
                                <td><?= esc($row['user_id']) ?></td>
                                <td><?= esc($row['nama']) ?></td>
                                <td><?= esc($row['alamat']) ?></td>
                                <td><?= esc($row['no_hp']) ?></td>
                                <td><?= esc($row['usia']) ?></td>
                                <td><?= esc($row['tempat_lahir'] . ', ' . $row['tanggal_lahir']) ?></td>
                                <td>
                                    <?php if (!empty($row['foto'])): ?>
                                        <img src="<?= base_url('uploads/foto_petani/' . esc($row['foto'])) ?>"
                                            alt="Foto Petani" class="rounded" style="width:60px; height:60px; object-fit:cover;">
                                    <?php else: ?>
                                        Tidak ada
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <!-- Tombol Edit berdasarkan status -->
                                        <?php if ($row['edit_status'] == 'approved') : ?>
                                            <button class="btn btn-warning btn-sm btn-edit-petani" data-toggle="modal" data-target="#modalEditPetani" data-id="<?= esc($row['id']) ?>" data-user_id="<?= esc($row['user_id']) ?>" data-nama="<?= esc($row['nama']) ?>" data-alamat="<?= esc($row['alamat']) ?>" data-no_hp="<?= esc($row['no_hp']) ?>" data-usia="<?= esc($row['usia']) ?>" data-tempat_lahir="<?= esc($row['tempat_lahir']) ?>" data-tanggal_lahir="<?= esc($row['tanggal_lahir']) ?>" data-foto="<?= esc($row['foto']) ?>" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php elseif ($row['edit_status'] == 'pending') : ?>
                                            <button class="btn btn-secondary btn-sm disabled" title="Permintaan sedang diproses">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                        <?php else : ?>
                                            <button class="btn btn-outline-warning btn-sm btn-request-access" data-petani-id="<?= esc($row['id']) ?>" data-action-type="edit" title="Minta Izin Edit">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Tombol Delete berdasarkan status -->
                                        <?php if ($row['delete_status'] == 'approved') : ?>
                                            <button class="btn btn-danger btn-sm btn-delete-petani" data-id="<?= esc($row['id']) ?>" data-nama="<?= esc($row['nama']) ?>" data-toggle="modal" data-target="#modalHapusPetani" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php elseif ($row['delete_status'] == 'pending') : ?>
                                            <button class="btn btn-secondary btn-sm disabled" title="Permintaan sedang diproses">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                        <?php else : ?>
                                            <button class="btn btn-outline-danger btn-sm btn-request-access" data-petani-id="<?= esc($row['id']) ?>" data-action-type="delete" title="Minta Izin Hapus">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Tombol Detail Pohon (tidak berubah) -->
                                        <a href="<?= site_url('petanipohon/index/' . $row['user_id']) ?>" class="btn btn-success btn-sm" title="Detail Pohon">
                                            <i class="fas fa-seedling"></i>
                                        </a>
                                    </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12">Tidak ada data petani</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- =============================================================== -->
        <!-- BLOK PAGINATION (DEBUG MODE: SELALU TAMPIL) -->
        <!-- Saya mengubah kondisi if di bawah ini -->
        <!-- =============================================================== -->
        <?php if (isset($pager)): ?>
            <div class="card-footer">
                <!-- DEBUG INFO: Total Data: <?= $pager->getTotal('petani') ?>, Jumlah Halaman: <?= $pager->getPageCount('petani') ?> -->
                <div class="pagination-wrapper">
                    <form method="get" class="per-page-selector">
                        <label class="per-page-label">
                            <i class="fas fa-list-ul mr-2"></i> Tampilkan
                        </label>
                        <div class="dropdown-container">
                            <select name="per_page" class="per-page-select" onchange="this.form.submit()">
                                <option value="10" <?= ($perPage == 10 ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= ($perPage == 25 ? 'selected' : '') ?>>25</option>
                                <option value="100" <?= ($perPage == 100 ? 'selected' : '') ?>>100</option>
                            </select>
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </div>
                        <span class="per-page-suffix">data per halaman</span>
                    </form>

                    <nav class="pagination-nav" aria-label="Navigasi Halaman">
                        <?= $pager->links('petani', 'custom_pagination_template') ?>
                    </nav>

                    <div class="page-info">
                        <span class="info-text">
                            <i class="fas fa-info-circle mr-2"></i>
                            <?php
                            $totalItems = $pager->getTotal('petani');
                            $startItem  = ($currentPage - 1) * $perPage + 1;
                            $endItem    = min($currentPage * $perPage, $totalItems);
                            ?>
                            Menampilkan <?= $startItem ?>-<?= $endItem ?> dari <?= $totalItems ?> total petani
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- =============================================================== -->

    </div>

    <div class="d-block d-lg-none">
        <?php if (!empty($petani)): ?>
            <?php $nomor = ($currentPage - 1) * $perPage + 1; ?>
            <?php foreach ($petani as $row): ?>
                <div class="card shadow mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <?php if (!empty($row['foto'])): ?>
                                <img src="<?= base_url('uploads/foto_petani/' . esc($row['foto'])) ?>"
                                    alt="Foto Petani" class="rounded mr-2" loading="lazy" style="width:60px; height:60px; object-fit:cover;">
                            <?php endif; ?>
                            <div>
                                <h6 class="mb-0"><?= esc($row['nama']) ?></h6>
                                <small class="text-muted">User ID: <?= esc($row['user_id']) ?></small>
                            </div>
                        </div>
                        <p class="mb-1"><strong>Alamat:</strong> <?= esc($row['alamat']) ?></p>
                        <p class="mb-1"><strong>No HP:</strong> <?= esc($row['no_hp']) ?></p>
                        <p class="mb-1"><strong>Usia:</strong> <?= esc($row['usia']) ?></p>
                        <p class="mb-1"><strong>TTL:</strong> <?= esc($row['tempat_lahir'] . ', ' . $row['tanggal_lahir']) ?></p>

                        <div class="mt-2">
                            <?php if ($row['edit_status']): ?>
                                <button class="btn btn-warning btn-sm btn-edit-petani"
                                    data-toggle="modal" data-target="#modalEditPetani"
                                    data-id="<?= esc($row['id']) ?>"
                                    data-user_id="<?= esc($row['user_id']) ?>"
                                    data-nama="<?= esc($row['nama']) ?>"
                                    data-alamat="<?= esc($row['alamat']) ?>"
                                    data-no_hp="<?= esc($row['no_hp']) ?>"
                                    data-usia="<?= esc($row['usia']) ?>"
                                    data-tempat_lahir="<?= esc($row['tempat_lahir']) ?>"
                                    data-tanggal_lahir="<?= esc($row['tanggal_lahir']) ?>"
                                    data-foto="<?= esc($row['foto']) ?>"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn btn-outline-warning btn-sm btn-request-access"
                                    data-petani-id="<?= esc($row['id']) ?>"
                                    data-action-type="edit" title="Minta Izin Edit">
                                    <i class="fas fa-lock"></i>
                                </button>
                            <?php endif; ?>

                            <?php if ($row['delete_status']): ?>
                                <button class="btn btn-danger btn-sm btn-delete-petani"
                                    data-id="<?= esc($row['id']) ?>"
                                    data-nama="<?= esc($row['nama']) ?>"
                                    data-toggle="modal" data-target="#modalHapusPetani" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn btn-outline-danger btn-sm btn-request-access"
                                    data-petani-id="<?= esc($row['id']) ?>"
                                    data-action-type="delete" title="Minta Izin Hapus">
                                    <i class="fas fa-lock"></i>
                                </button>
                            <?php endif; ?>

                            <a href="<?= site_url('petanipohon/index/' . $row['user_id']) ?>"
                                class="btn btn-success btn-sm" title="Detail Pohon">
                                <i class="fas fa-seedling"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- =============================================================== -->
            <!-- PAGINATION UNTUK TAMPILAN MOBILE -->
            <!-- =============================================================== -->
            <?php if (isset($pager)): ?>
                <div class="card shadow">
                    <div class="card-footer">
                        <div class="pagination-wrapper">
                            <?= $pager->links('petani', 'custom_pagination_template') ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <!-- =============================================================== -->

        <?php else: ?>
            <p class="text-center">Tidak ada data petani</p>
        <?php endif; ?>
    </div>



    <div class="modal fade" id="modalEditPetani" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formEditPetani" method="post" action="<?= site_url('petani/update') ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editPetaniId">
                <div class="modal-content shadow">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Edit Data Petani</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>User ID</label>
                            <input type="text" id="editUserId" name="user_id" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Petani</label>
                            <input type="text" id="editNama" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea id="editAlamat" name="alamat" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" id="editNoHp" name="no_hp" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Usia</label>
                            <input type="number" id="editUsia" name="usia" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" id="editTempatLahir" name="tempat_lahir" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" id="editTanggalLahir" name="tanggal_lahir" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Foto Petani</label><br>
                            <img id="previewFotoEdit" src="https://via.placeholder.com/80" alt="Foto"
                                style="width:80px;height:80px;object-fit:cover;margin-bottom:5px;">
                            <input type="file" name="foto" id="edit_foto" class="form-control-file" accept="image/*">
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


    <div class="modal fade" id="modalHapusPetani" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formHapusPetani" method="post" action="<?= site_url('petani/delete') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="hapusPetaniId">
                <div class="modal-content shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Konfirmasi Hapus Data Petani</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data petani <strong id="hapusPetaniNama"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* CSS UNTUK PAGINATION KUSTOM */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 0.75rem 1.25rem;
    }

    .per-page-selector,
    .page-info,
    .pagination-nav {
        display: flex;
        align-items: center;
    }

    .per-page-selector {
        gap: 0.5rem;
        color: #6c757d;
    }

    .dropdown-container {
        position: relative;
    }

    .per-page-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .dropdown-icon {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6c757d;
    }

    .page-info {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .pagination-nav .pagination {
        margin: 0;
    }

    .pagination-nav .page-item .page-link {
        color: #007bff;
    }

    .pagination-nav .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    /* Responsif */
    @media (max-width: 991.98px) {

        /* target lg breakpoint */
        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
        }

        .pagination-nav {
            order: -1;
            /* Pindahkan navigasi ke atas di layar kecil */
        }

        .per-page-selector,
        .page-info {
            display: none;
            /* Sembunyikan per-page dan info di mobile */
        }

        .d-lg-none .pagination-wrapper {
            justify-content: center;
        }
    }
</style>

<script>
    $(function() {
        $('.btn-edit-petani').click(function() {
            let id = $(this).data('id');
            let user_id = $(this).data('user_id');
            let nama = $(this).data('nama');
            let alamat = $(this).data('alamat');
            let no_hp = $(this).data('no_hp');
            let usia = $(this).data('usia');
            let tempat_lahir = $(this).data('tempat_lahir');
            let tanggal_lahir = $(this).data('tanggal_lahir');
            let foto = $(this).data('foto');

            $('#editPetaniId').val(id);
            $('#editUserId').val(user_id);
            $('#editNama').val(nama);
            $('#editAlamat').val(alamat);
            $('#editNoHp').val(no_hp);
            $('#editUsia').val(usia);
            $('#editTempatLahir').val(tempat_lahir);
            $('#editTanggalLahir').val(tanggal_lahir);

            if (foto) {
                $('#previewFotoEdit').attr('src', '<?= base_url("uploads/foto_petani") ?>/' + foto);
            } else {
                $('#previewFotoEdit').attr('src', 'https://via.placeholder.com/80');
            }

            $('#edit_foto').val('');
        });


        // Preview foto baru sebelum submit
        $('#edit_foto').on('change', function() {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#previewFotoEdit').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });

        // Perubahan di sini: Mengatur nilai input tersembunyi
        $('.btn-delete-petani').click(function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#hapusPetaniNama').text(nama);
            $('#hapusPetaniId').val(id); // Mengisi nilai input tersembunyi 'id'
            $('#modalHapusPetani').modal('show');
        });
        // Saat user pilih file di input foto
        $('#foto').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewFotoInput').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {

        // -----------------------------------------------------------
        // FUNGSI UNTUK MEMBUKA MODAL EDIT PETANI (dari kode lama Anda)
        // -----------------------------------------------------------
        $('.btn-edit-petani').click(function() {
            let id = $(this).data('id');
            let user_id = $(this).data('user_id');
            let nama = $(this).data('nama');
            let alamat = $(this).data('alamat');
            let no_hp = $(this).data('no_hp');
            let usia = $(this).data('usia');
            let tempat_lahir = $(this).data('tempat_lahir');
            let tanggal_lahir = $(this).data('tanggal_lahir');
            let foto = $(this).data('foto');

            $('#editPetaniId').val(id);
            $('#editUserId').val(user_id);
            $('#editNama').val(nama);
            $('#editAlamat').val(alamat);
            $('#editNoHp').val(no_hp);
            $('#editUsia').val(usia);
            $('#editTempatLahir').val(tempat_lahir);
            $('#editTanggalLahir').val(tanggal_lahir);

            if (foto) {
                $('#previewFotoEdit').attr('src', '<?= base_url("uploads/foto_petani") ?>/' + foto);
            } else {
                $('#previewFotoEdit').attr('src', 'https://via.placeholder.com/80');
            }
        });

        // -----------------------------------------------------------
        // FUNGSI UNTUK MEMBUKA MODAL HAPUS PETANI (dari kode lama Anda)
        // -----------------------------------------------------------
        $('.btn-delete-petani').click(function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            $('#hapusPetaniNama').text(nama);
            $('#hapusPetaniId').val(id);
            $('#modalHapusPetani').modal('show');
        });


        // -----------------------------------------------------------
        // FUNGSI BARU: MINTA IZIN AKSES (Request Access)
        // -----------------------------------------------------------
        $('.btn-request-access').on('click', function() {
            const button = $(this);
            const petaniId = button.data('petani-id');
            const action = button.data('action-type');

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: "<?= site_url('petani/requestAccess') ?>",
                method: "POST",
                data: {
                    petani_id: petaniId,
                    action_type: action,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // ==========================================================
                        // INI BAGIAN PENTINGNYA: Mengubah tombol jadi ikon jam
                        // ==========================================================
                        button.removeClass('btn-outline-warning btn-outline-danger')
                            .addClass('btn-secondary disabled')
                            .html('<i class="fas fa-clock"></i>');
                        // ==========================================================

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                        });
                        button.prop('disabled', false).html('<i class="fas fa-lock"></i>');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
                    });
                    button.prop('disabled', false).html('<i class="fas fa-lock"></i>');
                }
            });
        });

    });
</script>
<?= $this->endSection() ?>