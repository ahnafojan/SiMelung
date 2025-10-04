<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h1 class="h3 mb-1 page-title">Kopi Keluar</h1>
            <p class="mb-0 page-subtitle">Fungsi: Mencatat kopi keluar (penjualan/penyerahan).</p>
        </div>
        <div class="mt-2 mt-md-0">
            <span class="badge badge-info p-2">Sisa Stok Semua Kopi: <b><?= number_format($stok, 2, ',', '.') ?> Kg</b></span>
        </div>
    </div>

    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKopiKeluar">
            <i class="fas fa-plus"></i> Tambah Data Kopi Keluar
        </button>
    </div>

    <div class="card shadow d-none d-lg-block">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Jenis Kopi</th>
                        <th>Tujuan</th>
                        <th class="text-center">Jumlah (Kg)</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Sisa Stok Jenis Kopi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kopikeluar)): ?>
                        <?php $nomor = ($currentPage - 1) * $perPage + 1; ?>
                        <?php foreach ($kopikeluar as $k): ?>
                            <tr>
                                <td><?= $nomor++ ?></td>
                                <td><?= esc($k['nama_pohon'] ?? '-') ?></td>
                                <td><?= esc($k['tujuan']) ?></td>
                                <td class="text-right"><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</td>
                                <td><?= date('d-m-Y', strtotime($k['tanggal'])) ?></td>
                                <td><?= esc($k['keterangan']) ?></td>
                                <td class="text-right">
                                    <?= isset($k['sisa_stok_jenis']) ? number_format($k['sisa_stok_jenis'], 2, ',', '.') . ' Kg' : '-' ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <?php if ($k['edit_status'] == 'approved') : ?>
                                            <button class="btn btn-sm btn-warning btn-edit" data-id="<?= $k['id'] ?>" title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php elseif ($k['edit_status'] == 'pending') : ?>
                                            <button class="btn btn-sm btn-secondary disabled" title="Permintaan edit sedang diproses">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                        <?php else : ?>
                                            <button class="btn btn-sm btn-outline-warning btn-request-access" data-kopikeluar-id="<?= $k['id'] ?>" data-action-type="edit" title="Minta Izin Edit">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($k['delete_status'] == 'approved') : ?>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopiKeluar<?= $k['id'] ?>" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php elseif ($k['delete_status'] == 'pending') : ?>
                                            <button class="btn btn-sm btn-secondary disabled" title="Permintaan hapus sedang diproses">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                        <?php else : ?>
                                            <button class="btn btn-sm btn-outline-danger btn-request-access" data-kopikeluar-id="<?= $k['id'] ?>" data-action-type="delete" title="Minta Izin Hapus">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data kopi keluar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($pager) && $pager->getPageCount('kopikeluar') > 1): ?>
            <div class="card-footer">
                <div class="pagination-wrapper">
                    <form method="get" class="per-page-selector">
                        <label class="per-page-label"><i class="fas fa-list-ul mr-2"></i> Tampilkan</label>
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
                        <?= $pager->links('kopikeluar', 'custom_pagination_template') ?>
                    </nav>
                    <div class="page-info">
                        <span class="info-text">
                            <i class="fas fa-info-circle mr-2"></i>
                            <?php
                            $totalItems = $pager->getTotal('kopikeluar');
                            $startItem  = ($currentPage - 1) * $perPage + 1;
                            $endItem    = min($currentPage * $perPage, $totalItems);
                            ?>
                            Menampilkan <?= $startItem ?>-<?= $endItem ?> dari <?= $totalItems ?> total data
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-block d-lg-none">
        <?php if (!empty($kopikeluar)): ?>
            <?php foreach ($kopikeluar as $k): ?>
                <div class="card shadow mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="font-weight-bold text-primary mb-1"><?= esc($k['nama_pohon'] ?? '-') ?></h6>
                                <p class="mb-2"><strong>Tujuan:</strong> <?= esc($k['tujuan']) ?></p>
                            </div>
                            <span class="badge badge-pill badge-secondary"><?= date('d M Y', strtotime($k['tanggal'])) ?></span>
                        </div>

                        <div class="row text-center mt-2">
                            <div class="col-6">
                                <small class="text-muted">Jumlah Keluar</small>
                                <p class="font-weight-bold mb-0"><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Sisa Stok Jenis Ini</small>
                                <p class="font-weight-bold mb-0"><?= isset($k['sisa_stok_jenis']) ? number_format($k['sisa_stok_jenis'], 2, ',', '.') . ' Kg' : '-' ?></p>
                            </div>
                        </div>

                        <?php if (!empty($k['keterangan'])): ?>
                            <p class="mb-2 mt-2"><small><strong>Ket:</strong> <?= esc($k['keterangan']) ?></small></p>
                        <?php endif; ?>

                        <div class="mt-3 border-top pt-3 text-right">
                            <?php if ($k['edit_status'] == 'approved') : ?>
                                <button class="btn btn-sm btn-warning btn-edit" data-id="<?= $k['id'] ?>" title="Edit Data">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            <?php elseif ($k['edit_status'] == 'pending') : ?>
                                <button class="btn btn-sm btn-secondary disabled" title="Permintaan edit sedang diproses">
                                    <i class="fas fa-clock"></i> Pending
                                </button>
                            <?php else : ?>
                                <button class="btn btn-sm btn-outline-warning btn-request-access" data-kopikeluar-id="<?= $k['id'] ?>" data-action-type="edit" title="Minta Izin Edit">
                                    <i class="fas fa-lock"></i> Minta Edit
                                </button>
                            <?php endif; ?>

                            <?php if ($k['delete_status'] == 'approved') : ?>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopiKeluar<?= $k['id'] ?>" title="Hapus Data">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            <?php elseif ($k['delete_status'] == 'pending') : ?>
                                <button class="btn btn-sm btn-secondary disabled" title="Permintaan hapus sedang diproses">
                                    <i class="fas fa-clock"></i> Pending
                                </button>
                            <?php else : ?>
                                <button class="btn btn-sm btn-outline-danger btn-request-access" data-kopikeluar-id="<?= $k['id'] ?>" data-action-type="delete" title="Minta Izin Hapus">
                                    <i class="fas fa-lock"></i> Minta Hapus
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (isset($pager) && $pager->getPageCount('kopikeluar') > 1): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?= $pager->links('kopikeluar', 'custom_pagination_template') ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-info text-center">Belum ada data kopi keluar.</div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalTambahKopiKeluar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('kopikeluar/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Form Tambah Kopi Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Jenis Kopi</label>
                        <select name="stok_kopi_id" class="form-control" required>
                            <option value="">-- Pilih Jenis Kopi --</option>
                            <?php foreach ($stokKopi as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= esc($s['nama_pohon']) ?> - Stok: <?= number_format($s['total_stok'] ?? 0, 2, ',', '.') ?> Kg</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Tujuan</label><input type="text" name="tujuan" class="form-control" required></div>
                    <div class="form-group"><label>Jumlah (Kg)</label><input type="text" name="jumlah" class="form-control" placeholder="Contoh: 15 atau 1000.5" required></div>
                    <div class="form-group"><label>Tanggal Keluar</label><input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                    <div class="form-group"><label>Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditKopiKeluar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formEditKopiKeluar" action="" method="post">
            <?= csrf_field() ?>
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Form Edit Kopi Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Jenis Kopi</label>
                        <select id="edit_stok_kopi_id" name="stok_kopi_id" class="form-control" required>
                            <option value="">-- Pilih Jenis Kopi --</option>
                            <?php foreach ($stokKopi as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= esc($s['nama_pohon']) ?> - Stok: <?= number_format($s['total_stok'] ?? 0, 2, ',', '.') ?> Kg</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Tujuan</label><input type="text" id="edit_tujuan" name="tujuan" class="form-control" required></div>
                    <div class="form-group"><label>Jumlah (Kg)</label><input type="number" id="edit_jumlah" name="jumlah" step="0.01" class="form-control" min="0.01" required></div>
                    <div class="form-group"><label>Tanggal Keluar</label><input type="date" id="edit_tanggal" name="tanggal" class="form-control" required></div>
                    <div class="form-group"><label>Keterangan</label><textarea id="edit_keterangan" name="keterangan" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($kopikeluar)): ?>
    <?php foreach ($kopikeluar as $k): ?>
        <div class="modal fade" id="modalHapusKopiKeluar<?= $k['id'] ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form action="<?= base_url('kopikeluar/delete/' . $k['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus data ini?</p>
                            <p>Jenis Kopi: <strong><?= esc($k['nama_pohon']) ?></strong><br>Jumlah: <strong><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</strong></p>
                            <p class="text-danger font-weight-bold">Tindakan ini akan mengembalikan jumlah stok kopi.</p>
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
<?php endif; ?>

<style>
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

    @media (max-width: 991.98px) {
        .d-lg-none .pagination-wrapper {
            justify-content: center;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
        }

        .pagination-nav {
            order: -1;
        }

        .per-page-selector,
        .page-info {
            display: none;
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Fungsi untuk mengisi modal edit dan menampilkannya
        function showEditModal(id) {
            $.ajax({
                url: `<?= base_url('kopikeluar/edit') ?>/${id}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        $('#edit_id').val(data.id);
                        $('#edit_stok_kopi_id').val(data.stok_kopi_id);
                        $('#edit_tujuan').val(data.tujuan);
                        $('#edit_jumlah').val(data.jumlah);
                        $('#edit_tanggal').val(data.tanggal);
                        $('#edit_keterangan').val(data.keterangan);
                        $('#formEditKopiKeluar').attr('action', `<?= base_url('kopikeluar/update') ?>/${id}`);
                        $('#modalEditKopiKeluar').modal('show');
                    } else {
                        Swal.fire('Error', 'Data tidak ditemukan.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Gagal mengambil data untuk diedit.', 'error');
                }
            });
        }

        // Menggunakan event delegation agar event terpasang di elemen desktop dan mobile
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            showEditModal(id);
        });

        // AJAX untuk Minta Izin Akses
        $(document).on('click', '.btn-request-access', function() {
            const button = $(this);
            const kopiKeluarId = button.data('kopikeluar-id');
            const action = button.data('action-type');

            // Ambil CSRF dari meta tag (lebih stabil)
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

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: "<?= site_url('kopikeluar/requestAccess') ?>",
                method: "POST",
                data: { // ✅ Perbaikan: tambahkan 'data:'
                    kopikeluar_id: kopiKeluarId,
                    action_type: action,
                    [csrfToken]: csrfHash
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
                        }).then(() => {
                            location.reload(); // Reload agar PHP update tombol
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                        button.prop('disabled', false).empty().html('<i class="fas fa-lock"></i>');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan koneksi. Coba lagi.'
                    });
                    console.error('AJAX Error:', error);
                    button.prop('disabled', false).empty().html('<i class="fas fa-lock"></i>');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>