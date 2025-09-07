<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Kopi Keluar</h1>
            <p class="mb-0 page-subtitle">Fungsi: Mencatat kopi keluar (penjualan/penyerahan).</p>
        </div>
        <div>
            <span class="badge badge-info p-2">Sisa Stok Semua Kopi: <b><?= number_format($stok, 2, ',', '.') ?> Kg</b></span>
        </div>
    </div>

    <!-- Tombol Tambah Data -->
    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKopiKeluar">
            <i class="fas fa-plus"></i> Tambah Data Kopi Keluar
        </button>
    </div>

    <!-- Modal Form Tambah Data -->
    <div class="modal fade" id="modalTambahKopiKeluar" tabindex="-1" role="dialog" aria-labelledby="modalTambahKopiKeluarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url('kopikeluar/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTambahKopiKeluarLabel">Form Tambah Kopi Keluar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="stok_kopi_id">Pilih Jenis Kopi</label>
                            <select id="stok_kopi_id" name="stok_kopi_id" class="form-control" required>
                                <option value="">-- Pilih Jenis Kopi --</option>
                                <?php foreach ($stokKopi as $s): ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= esc($s['nama_pohon']) ?> -
                                        Stok: <?= isset($s['total_stok']) ? number_format($s['total_stok'], 2, ',', '.') : '0,00' ?> Kg
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tujuan">Tujuan</label>
                            <input type="text" id="tujuan" name="tujuan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah (Kg)</label>
                            <input type="number" id="jumlah" name="jumlah" step="0.01" class="form-control" min="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal Keluar</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" class="form-control" rows="2"></textarea>
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

    <!-- Modal Form Edit Data -->
    <div class="modal fade" id="modalEditKopiKeluar" tabindex="-1" role="dialog" aria-labelledby="modalEditKopiKeluarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditKopiKeluar" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-content shadow">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalEditKopiKeluarLabel">Form Edit Kopi Keluar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_stok_kopi_id">Pilih Jenis Kopi</label>
                            <select id="edit_stok_kopi_id" name="stok_kopi_id" class="form-control" required>
                                <option value="">-- Pilih Jenis Kopi --</option>
                                <?php foreach ($stokKopi as $s): ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= esc($s['nama_pohon']) ?> -
                                        Stok: <?= isset($s['total_stok']) ? number_format($s['total_stok'], 2, ',', '.') : '0,00' ?> Kg
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_tujuan">Tujuan</label>
                            <input type="text" id="edit_tujuan" name="tujuan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jumlah">Jumlah (Kg)</label>
                            <input type="number" id="edit_jumlah" name="jumlah" step="0.01" class="form-control" min="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_tanggal">Tanggal Keluar</label>
                            <input type="date" id="edit_tanggal" name="tanggal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_keterangan">Keterangan</label>
                            <textarea id="edit_keterangan" name="keterangan" class="form-control" rows="2"></textarea>
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


    <!-- Tabel Data Kopi Keluar -->
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Jenis Kopi</th>
                        <th>Tujuan</th>
                        <th>Jumlah (Kg)</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Sisa Stok Jenis Kopi</th>
                        <th>Aksi</th>
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
                                            <button class="btn btn-sm btn-outline-warning btn-request-access"
                                                data-kopikeluar-id="<?= $k['id'] ?>"
                                                data-action-type="edit" title="Minta Izin Edit">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>


                                        <?php if ($k['delete_status'] == 'approved') : ?>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#modalHapusKopiKeluar<?= $k['id'] ?>" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php elseif ($k['delete_status'] == 'pending') : ?>
                                            <button class="btn btn-sm btn-secondary disabled" title="Permintaan hapus sedang diproses">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                        <?php else : ?>
                                            <button class="btn btn-sm btn-outline-danger btn-request-access"
                                                data-kopikeluar-id="<?= $k['id'] ?>"
                                                data-action-type="delete" title="Minta Izin Hapus">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Hapus Kopi Keluar -->
                            <div class="modal fade" id="modalHapusKopiKeluar<?= $k['id'] ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('kopikeluar/delete/' . $k['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus data ini?</p>
                                                <p>
                                                    Jenis Kopi: <strong><?= esc($k['nama_pohon']) ?></strong><br>
                                                    Jumlah: <strong><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</strong>
                                                </p>
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
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data kopi keluar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- =============================================================== -->
        <!-- BLOK PAGINATION (DEBUG MODE DIAKTIFKAN) -->
        <!-- =============================================================== -->
        <?php if (isset($pager)): ?>
            <div class="card-footer">
                <!-- DEBUG INFO (Lihat di source code halaman): Total Data: <?= $pager->getTotal('kopikeluar') ?>, Jumlah Halaman: <?= $pager->getPageCount('kopikeluar') ?> -->
                <div class="pagination-wrapper">
                    <!-- Per Page Selector -->
                    <form method="get" class="per-page-selector">
                        <label class="per-page-label">
                            <i class="fas fa-list-ul mr-2"></i>
                            Tampilkan
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

                    <!-- Pagination Navigation -->
                    <nav class="pagination-nav" aria-label="Navigasi Halaman">
                        <?= $pager->links('kopikeluar', 'custom_pagination_template') ?>
                    </nav>

                    <!-- Page Info -->
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
        <!-- =============================================================== -->

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
    @media (max-width: 768px) {
        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
        }

        .pagination-nav {
            order: -1;
            /* Pindahkan navigasi ke atas di layar kecil */
        }
    }
</style>

<!-- =============================================================================== -->
<!-- SCRIPT LAMA ANDA -->
<!-- =============================================================================== -->
<script>
    $(document).ready(function() {

        // --- BLOK BARU UNTUK MEMPERBAIKI TOMBOL BATAL & X ---
        // Kita targetkan semua elemen yang punya atribut data-dismiss="modal"
        // di dalam modal edit.
        $('#modalEditKopiKeluar').on('click', '[data-dismiss="modal"]', function() {
            // Ketika salah satunya diklik, kita panggil fungsi 'hide' secara manual.
            $('#modalEditKopiKeluar').modal('hide');
        });
        // ----------------------------------------------------


        // Event handler untuk tombol edit (kode ini tetap sama)
        $('.btn-edit').on('click', function() {
            const id = $(this).data('id'); // Ambil ID dari tombol yang diklik

            // Lakukan request AJAX untuk mendapatkan data
            $.ajax({
                url: `<?= base_url('kopikeluar/edit') ?>/${id}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        // Isi nilai-nilai form di dalam modal edit
                        $('#edit_id').val(data.id);
                        $('#edit_stok_kopi_id').val(data.stok_kopi_id);
                        $('#edit_tujuan').val(data.tujuan);
                        $('#edit_jumlah').val(data.jumlah);
                        $('#edit_tanggal').val(data.tanggal);
                        $('#edit_keterangan').val(data.keterangan);

                        // Atur action form agar mengarah ke URL update yang benar
                        $('#formEditKopiKeluar').attr('action', `<?= base_url('kopikeluar/update') ?>/${id}`);

                        // Tampilkan modal edit
                        $('#modalEditKopiKeluar').modal('show');
                    } else {
                        alert('Data tidak ditemukan.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Response:", xhr.responseText);
                    alert('Gagal mengambil data untuk diedit. Silakan periksa konsol browser untuk detail.');
                }
            });
        });
    });
    $('.btn-request-access').on('click', function() {
        const button = $(this);
        const kopiKeluarId = button.data('kopikeluar-id');
        const action = button.data('action-type');

        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "<?= site_url('kopikeluar/requestAccess') ?>",
            method: "POST",
            data: {
                kopikeluar_id: kopiKeluarId,
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