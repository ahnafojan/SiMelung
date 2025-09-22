<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Kopi Masuk</h1>
            <p class="mb-0 page-subtitle">Fungsi: Mencatat kopi yang disetor oleh petani ke BUMDes.</p>
        </div>
    </div>

    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKopi">
            <i class="fas fa-plus"></i> Tambah Data Kopi Masuk
        </button>
    </div>


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
                        <div class="form-group">
                            <label>Nama Petani</label>
                            <select id="petani" name="petani_user_id" class="form-control" required>
                                <option value="">-- Pilih Petani --</option>
                                <?php foreach ($petani as $p) : ?>
                                    <option value="<?= $p['user_id'] ?>">
                                        <?= $p['user_id'] ?> - <?= $p['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenis Pohon</label>
                            <select id="jenis_pohon" name="petani_pohon_id" class="form-control" required>
                                <option value="">-- Pilih Jenis Pohon --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah (Kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan jumlah kopi Kg">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Setor</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukan Keterangan jika ada"></textarea>
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

    <div class="card shadow d-none d-lg-block">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Petani</th>
                        <th>Jenis Pohon Kopi</th>
                        <th>Tanggal</th>
                        <th>Stok Saat Ini (Kg)</th>
                        <th>Kopi Masuk (Kg)</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (!empty($kopiMasuk)) : ?>
                        <?php $nomor = ($currentPage - 1) * $perPage + 1; ?>
                        <?php foreach ($kopiMasuk as $k) : ?>
                            <tr>
                                <td><?= $nomor++ ?></td>
                                <td><?= esc($k['nama_petani']) ?></td>
                                <td><?= esc($k['nama_pohon']) ?></td>
                                <td><?= esc($k['tanggal']) ?></td>
                                <td><?= esc($k['stok'] ?? 0) ?> Kg</td>
                                <td><?= esc($k['jumlah']) ?> Kg</td>
                                <td><?= esc($k['keterangan']) ?></td>
                                <td>
                                    <!-- Untuk Desktop -->
                                    <?php if ($k['edit_status'] == 'approved') : ?>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditKopi<?= $k['id'] ?>"><i class="fas fa-edit"></i> Edit</button>
                                    <?php elseif ($k['edit_status'] == 'pending') : ?>
                                        <button class="btn btn-sm btn-secondary disabled" title="Permintaan sedang diproses"><i class="fas fa-clock"></i></button>
                                    <?php else : ?>
                                        <button class="btn btn-sm btn-outline-warning btn-request-access" data-kopimasuk-id="<?= $k['id'] ?>" data-action-type="edit" title="Minta Izin Edit"><i class="fas fa-lock"></i></button>
                                    <?php endif; ?>

                                    <?php if ($k['delete_status'] == 'approved') : ?>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopi<?= $k['id'] ?>"><i class="fas fa-trash"></i> Hapus</button>
                                    <?php elseif ($k['delete_status'] == 'pending') : ?>
                                        <button class="btn btn-sm btn-secondary disabled" title="Permintaan sedang diproses"><i class="fas fa-clock"></i></button>
                                    <?php else : ?>
                                        <button class="btn btn-sm btn-outline-danger btn-request-access" data-kopimasuk-id="<?= $k['id'] ?>" data-action-type="delete" title="Minta Izin Hapus"><i class="fas fa-lock"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
                <div class="pagination-wrapper">
                    <form method="get" class="per-page-selector">
                        <label class="per-page-label"><i class="fas fa-list-ul mr-2"></i> Tampilkan </label>
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
                    <nav class="pagination-nav" aria-label="Navigasi Halaman"><?= $pager->links('default', 'custom_pagination_template') ?></nav>
                    <div class="page-info">
                        <span class="info-text">
                            <i class="fas fa-info-circle mr-2"></i>
                            <?php
                            $totalItems = $pager->getTotal();
                            $startItem = (($currentPage - 1) * $perPage) + 1;
                            $endItem = min($currentPage * $perPage, $totalItems);
                            ?>
                            Menampilkan <?= $startItem ?>-<?= $endItem ?> dari <?= $totalItems ?> total data
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="d-block d-lg-none">
        <?php if (!empty($kopiMasuk)) : ?>
            <?php foreach ($kopiMasuk as $k) : ?>
                <div class="card shadow mb-3">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-primary"><?= esc($k['nama_petani']) ?></h6>
                        <p class="mb-1"><strong>Jenis Kopi:</strong> <?= esc($k['nama_pohon']) ?></p>
                        <p class="mb-1"><strong>Tanggal:</strong> <?= esc($k['tanggal']) ?></p>
                        <p class="mb-1"><strong>Stok Saat Ini:</strong> <?= esc($k['stok'] ?? 0) ?> Kg</p>
                        <p class="mb-1"><strong>Kopi Masuk:</strong> <?= esc($k['jumlah']) ?> Kg</p>
                        <?php if (!empty($k['keterangan'])) : ?>
                            <p class="mb-2"><strong>Ket:</strong> <?= esc($k['keterangan']) ?></p>
                        <?php endif; ?>

                        <div class="mt-3 border-top pt-3 text-right">
                            <?php if ($k['edit_status'] == 'approved') : ?>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditKopi<?= $k['id'] ?>"><i class="fas fa-edit"></i> Edit</button>
                            <?php elseif ($k['edit_status'] == 'pending') : ?>
                                <button class="btn btn-sm btn-secondary disabled" title="Permintaan sedang diproses"><i class="fas fa-clock"></i> Pending</button>
                            <?php else : ?>
                                <button class="btn btn-sm btn-outline-warning btn-request-access" data-kopimasuk-id="<?= $k['id'] ?>" data-action-type="edit" title="Minta Izin Edit"><i class="fas fa-lock"></i> Minta Edit</button>
                            <?php endif; ?>

                            <?php if ($k['delete_status'] == 'approved') : ?>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopi<?= $k['id'] ?>"><i class="fas fa-trash"></i> Hapus</button>
                            <?php elseif ($k['delete_status'] == 'pending') : ?>
                                <button class="btn btn-sm btn-secondary disabled" title="Permintaan sedang diproses"><i class="fas fa-clock"></i> Pending</button>
                            <?php else : ?>
                                <button class="btn btn-sm btn-outline-danger btn-request-access" data-kopimasuk-id="<?= $k['id'] ?>" data-action-type="delete" title="Minta Izin Hapus"><i class="fas fa-lock"></i> Minta Hapus</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
                <div class="d-flex justify-content-center">
                    <?= $pager->links('default', 'custom_pagination_template') ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <div class="alert alert-info text-center">Belum ada data</div>
        <?php endif; ?>
    </div>

    <?php if (!empty($kopiMasuk)) : ?>
        <?php foreach ($kopiMasuk as $k) : ?>
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
                                <div class="form-group">
                                    <label>Nama Petani</label>
                                    <select name="petani_user_id" class="form-control">
                                        <?php foreach ($petani as $p) : ?>
                                            <option value="<?= $p['user_id'] ?>" <?= $p['user_id'] == $k['petani_user_id'] ? 'selected' : '' ?>>
                                                <?= $p['user_id'] ?> - <?= $p['nama'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Pohon</label>
                                    <select name="petani_pohon_id" class="form-control jenis-pohon-dropdown" data-petani="<?= $k['petani_user_id'] ?>" data-selected="<?= $k['petani_pohon_id'] ?>" required>
                                        <option value="">-- Pilih Jenis Pohon --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah (Kg)</label>
                                    <input type="number" step="0.01" min="0" name="jumlah" class="form-control" value="<?= esc($k['jumlah']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?= esc($k['tanggal']) ?>" required>
                                </div>
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
                                <p>Apakah Anda yakin ingin menghapus data kopi masuk dari <strong><?= esc($k['nama_petani']) ?></strong> (<?= esc($k['nama_pohon']) ?>)?</p>
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
    <?php endif; ?>

</div>

<style>
    /* CSS Kustom untuk Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        padding: 0.5rem 1.25rem;
    }

    .per-page-selector,
    .page-info,
    .pagination-nav {
        margin: 0.5rem 0;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .per-page-label,
    .per-page-suffix {
        margin: 0 0.5rem;
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
        font-size: 0.875rem;
        color: #6c757d;
    }

    .pagination-nav .pagination {
        margin-bottom: 0;
    }
</style>

<script>
    $(document).ready(function() {
        // Fungsi untuk mengambil data jenis pohon berdasarkan petani
        function loadJenisPohon(petaniId, $dropdown, selectedId = null) {
            $dropdown.prop('disabled', true).html('<option>Loading...</option>');
            if (petaniId) {
                $.getJSON("<?= base_url('get-jenis-pohon') ?>/" + petaniId, function(data) {
                    let options = '<option value="">-- Pilih Jenis Pohon --</option>';
                    $.each(data, function(i, item) {
                        let isSelected = (item.id == selectedId) ? 'selected' : '';
                        options += `<option value="${item.id}" ${isSelected}>${item.nama_jenis}</option>`;
                    });
                    $dropdown.html(options).prop('disabled', false);
                });
            } else {
                $dropdown.html('<option value="">-- Pilih Jenis Pohon --</option>').prop('disabled', true);
            }
        }

        // Event handler untuk dropdown petani di form TAMBAH
        $('#petani').change(function() {
            let petaniId = $(this).val();
            let $jenisPohon = $('#jenis_pohon');
            loadJenisPohon(petaniId, $jenisPohon);
        });

        // Event handler saat modal EDIT ditampilkan
        $('.modal').on('shown.bs.modal', function() {
            let $jenisPohon = $(this).find('.jenis-pohon-dropdown');
            if (!$jenisPohon.length) return; // Keluar jika bukan modal edit

            // Hanya load jika dropdown masih kosong
            if ($jenisPohon.find('option').length <= 1) {
                let petaniId = $jenisPohon.data('petani');
                let selectedId = $jenisPohon.data('selected');
                loadJenisPohon(petaniId, $jenisPohon, selectedId);
            }
        });

        // Event handler jika petani diganti di dalam modal EDIT
        $(document).on('change', '.modal select[name="petani_user_id"]', function() {
            let $modal = $(this).closest('.modal');
            let petaniId = $(this).val();
            let $jenisPohon = $modal.find('.jenis-pohon-dropdown');
            loadJenisPohon(petaniId, $jenisPohon);
        });

        // AJAX untuk Minta Izin Akses (Request Access)
        $('.btn-request-access').on('click', function() {
            const button = $(this);
            const kopiMasukId = button.data('kopimasuk-id');
            const action = button.data('action-type');

            // Dapatkan CSRF dari meta tag (lebih aman)
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
                url: "<?= site_url('kopi-masuk/requestAccess') ?>",
                method: "POST",
                data: {
                    kopimasuk_id: kopiMasukId,
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
                            button.html('<i class="fas fa-lock"></i> Minta Edit');
                        } else {
                            button.html('<i class="fas fa-lock"></i> Minta Hapus');
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
                        button.html('<i class="fas fa-lock"></i> Minta Edit');
                    } else {
                        button.html('<i class="fas fa-lock"></i> Minta Hapus');
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>