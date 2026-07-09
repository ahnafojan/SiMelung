<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<?php
$kopiMasuk = $kopiMasuk ?? [];
$petani = $petani ?? [];
$pager = $pager ?? null;
$currentPage = $currentPage ?? 1;
$perPage = $perPage ?? 10;
?>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambahKopi" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Pastikan form memiliki ID untuk JS -->
            <form id="formTambahKopi" action="<?= base_url('kopi-masuk/create') ?>" method="post">
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
                            <input type="number" step="0.01" min="0" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan jumlah kopi Kg" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Setor</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="harga_saat_transaksi">Harga Kopi (Rp/Kg) - Otomatis</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="harga_saat_transaksi" name="harga_saat_transaksi" placeholder="Harga akan muncul otomatis" readonly>
                            <small id="hargaHelp" class="form-text text-muted">Harga ini di ambil dari daftar harga kopi yang berlaku dari tanggal data master Jenis Kopi</small>
                        </div>
                        <div class="form-group">
                            <label for="total_harga">Total Harga (Rp)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="total_harga" name="total_harga" placeholder="Total harga akan dihitung otomatis" readonly>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukan Keterangan jika ada"></textarea>
                        </div>
                        <!-- Pesan Error Dinamis -->
                        <div class="alert alert-danger d-none mt-3" id="errorHargaMessage">
                            <strong>Perhatian!</strong> Tidak ada harga beli yang berlaku untuk jenis pohon ini pada tanggal yang dipilih. Silakan atur harga terlebih dahulu di menu "Jenis Pohon".
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <!-- Tambahkan ID ke tombol submit -->
                        <button type="submit" class="btn btn-primary" id="btnSimpanKopiMasuk">Simpan</button>
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
                        <!-- Kolom Harga Ditambahkan -->
                        <th>Harga/Kg (Rp)</th>
                        <th>Total Harga (Rp)</th>
                        <!-- /Kolom Harga Ditambahkan -->
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
                                <td>
                                    <?php
                                    $tgl = $k['tanggal'] ?? '';
                                    echo $tgl ? date('d/m/Y', strtotime($tgl)) : '-';
                                    ?>
                                </td>
                                <td><?= esc($k['stok'] ?? 0) ?> Kg</td>
                                <td><?= esc($k['jumlah']) ?> Kg</td>
                                <!-- Data Harga Ditampilkan -->
                                <td>Rp <?= number_format($k['harga_saat_transaksi'] ?? 0, 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($k['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                <!-- /Data Harga Ditampilkan -->
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
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopi<?= $k['id'] ?>"><i class="fas fa-trash"></i></button>
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
                            <td colspan="10" class="text-center">Belum ada data</td>
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
                        <!-- Header: Nama Petani & Tanggal -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="font-weight-bold text-primary mb-1"><?= esc($k['nama_petani']) ?></h6>
                                <p class="mb-0"><strong>Jenis Kopi:</strong> <?= esc($k['nama_pohon']) ?></p>
                            </div>
                            <span class="badge badge-pill badge-secondary"><?= date('d M Y', strtotime($k['tanggal'])) ?></span>
                        </div>

                        <!-- Blok Informasi Vertikal (Centered) -->
                        <div class="text-center mb-3">

                            <!-- Kopi Masuk -->
                            <div class="mb-2 p-2 rounded border" style="background-color: #f8f9fa; border-left: 4px solid #007bff;">
                                <small class="text-muted d-block">KOPI MASUK</small>
                                <p class="font-weight-bold mb-0" style="color: #007bff; font-size: 1.1rem;">
                                    <?= number_format($k['jumlah'], 2, ',', '.') ?> Kg
                                </p>
                            </div>

                            <!-- Stok Saat Ini -->
                            <div class="mb-2 p-2 rounded border" style="background-color: #f8f9fa; border-left: 4px solid #28a745;">
                                <small class="text-muted d-block">STOK SAAT INI</small>
                                <p class="font-weight-bold mb-0" style="color: #28a745; font-size: 1.1rem;">
                                    <?= number_format($k['stok'] ?? 0, 2, ',', '.') ?> Kg
                                </p>
                            </div>

                            <!-- Harga/Kg -->
                            <div class="mb-2 p-2 rounded border" style="background-color: #f8f9fa; border-left: 4px solid #ffc107;">
                                <small class="text-muted d-block">HARGA/KG</small>
                                <p class="font-weight-bold mb-0" style="color: #ffc107; font-size: 1.1rem;">
                                    <?= isset($k['harga_saat_transaksi']) ? 'Rp&nbsp;' . number_format($k['harga_saat_transaksi'], 0, ',', '.') : '–' ?>
                                </p>
                            </div>

                            <!-- Total Harga -->
                            <div class="mb-2 p-2 rounded border" style="background-color: #f8f9fa; border-left: 4px solid #dc3545;">
                                <small class="text-muted d-block">TOTAL HARGA</small>
                                <p class="font-weight-bold mb-0" style="color: #dc3545; font-size: 1.1rem;">
                                    <?= isset($k['total_harga']) ? 'Rp&nbsp;' . number_format($k['total_harga'], 0, ',', '.') : '–' ?>
                                </p>
                            </div>

                        </div>

                        <!-- Keterangan -->
                        <?php if (!empty($k['keterangan'])) : ?>
                            <div class="border-top pt-2 mt-2">
                                <small><strong>Ket:</strong> <?= esc($k['keterangan']) ?></small>
                            </div>
                        <?php endif; ?>

                        <!-- Aksi (Edit & Hapus) -->
                        <div class="mt-3 border-top pt-3 d-flex justify-content-end gap-2">
                            <?php if ($k['edit_status'] == 'approved') : ?>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditKopi<?= $k['id'] ?>" title="Edit Data">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            <?php elseif ($k['edit_status'] == 'pending') : ?>
                                <button class="btn btn-sm btn-secondary disabled" title="Permintaan edit sedang diproses">
                                    <i class="fas fa-clock"></i> Pending
                                </button>
                            <?php else : ?>
                                <button class="btn btn-sm btn-outline-warning btn-request-access" data-kopimasuk-id="<?= $k['id'] ?>" data-action-type="edit" title="Minta Izin Edit">
                                    <i class="fas fa-lock"></i> Minta Edit
                                </button>
                            <?php endif; ?>

                            <?php if ($k['delete_status'] == 'approved') : ?>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopi<?= $k['id'] ?>" title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php elseif ($k['delete_status'] == 'pending') : ?>
                                <button class="btn btn-sm btn-secondary disabled" title="Permintaan hapus sedang diproses">
                                    <i class="fas fa-clock"></i> Pending
                                </button>
                            <?php else : ?>
                                <button class="btn btn-sm btn-outline-danger btn-request-access" data-kopimasuk-id="<?= $k['id'] ?>" data-action-type="delete" title="Minta Izin Hapus">
                                    <i class="fas fa-lock"></i> Minta Hapus
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
                <div class="d-flex justify-content-center mt-3">
                    <?= $pager->links('default', 'custom_pagination_template') ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <div class="alert alert-info text-center">Belum ada data kopi masuk.</div>
        <?php endif; ?>
    </div>

    <?php if (!empty($kopiMasuk)) : ?>
        <?php foreach ($kopiMasuk as $k) : ?>
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
                                    <input type="number" step="0.01" min="0" name="jumlah" class="form-control" id="jumlah" value="<?= esc($k['jumlah']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" id="tanggal" value="<?= esc($k['tanggal']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_saat_transaksi">Harga Kopi (Rp/Kg) - Otomatis</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="harga_saat_transaksi" name="harga_saat_transaksi" value="<?= esc($k['harga_saat_transaksi']) ?>" readonly>
                                    <small id="hargaHelp" class="form-text text-muted">Harga ini di ambil dari daftar harga kopi yang berlaku dari tanggal data master Jenis Kopi</small>
                                </div>
                                <div class="form-group">
                                    <label for="total_harga">Total Harga (Rp)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="total_harga" name="total_harga" value="<?= esc($k['total_harga']) ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2"><?= esc($k['keterangan']) ?></textarea>
                                </div>
                                <!-- Pesan Error Dinamis (Modal Edit) -->
                                <div class="alert alert-danger d-none mt-3" id="errorHargaMessage">
                                    <strong>Perhatian!</strong> Tidak ada harga beli yang berlaku untuk jenis pohon ini pada tanggal yang dipilih. Silakan atur harga terlebih dahulu di menu "Jenis Pohon".
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <!-- Tambahkan ID ke tombol submit -->
                                <button type="submit" class="btn btn-warning" id="btnSimpanKopiMasuk">Simpan</button>
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

        // Fungsi untuk mendapatkan harga beli terbaru berdasarkan petani_pohon_id dan tanggal
        function getHargaTerbaru(petaniPohonId, tanggal, callback) {
            if (!petaniPohonId || !tanggal) {
                callback(null, null);
                return;
            }

            $.ajax({
                url: "<?= base_url('api-harga/getHargaBeliterbaru') ?>",
                method: "POST",
                data: {
                    petani_pohon_id: petaniPohonId,
                    tanggal: tanggal,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>' // Gunakan token CSRF Anda
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        callback(response.data.harga_beli_per_kg, response.data.tanggal_berlaku);
                    } else {
                        callback(null, null);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    callback(null, null);
                }
            });
        }

        // Fungsi untuk menghitung total harga (FORM TAMBAH - Global)
        function calculateTotalHargaFormTambah() {
            const jumlah = parseFloat($('#jumlah').val()) || 0;
            const harga = parseFloat($('#harga_saat_transaksi').val()) || 0;
            const total = jumlah * harga;
            $('#total_harga').val(total.toFixed(2));
        }

        // Fungsi untuk menghitung total harga (FORM EDIT - Scoped ke Modal)
        function calculateTotalHargaFormEdit($modal) {
            const jumlah = parseFloat($modal.find('#jumlah').val()) || 0;
            const harga = parseFloat($modal.find('#harga_saat_transaksi').val()) || 0;
            const total = jumlah * harga;
            $modal.find('#total_harga').val(total.toFixed(2));
        }

        // Fungsi untuk memperbarui harga saat transaksi dan total (bisa digunakan di form tambah atau edit)
        function updateHargaSaatTransaksi($form, petaniPohonId, tanggal) {
            getHargaTerbaru(petaniPohonId, tanggal, function(harga, tanggalBerlaku) {
                const $hargaField = $form.find('#harga_saat_transaksi');
                const $totalField = $form.find('#total_harga');
                const $errorDiv = $form.find('#errorHargaMessage');
                const $btnSimpan = $form.find('#btnSimpanKopiMasuk');
                const $hargaHelp = $form.find('#hargaHelp');

                if (harga !== null) {
                    $hargaField.val(harga);
                    // Panggil fungsi kalkulasi yang benar berdasarkan $form
                    if ($form.attr('id') === 'formTambahKopi') {
                        calculateTotalHargaFormTambah(); // Panggil fungsi untuk form tambah
                    } else {
                        calculateTotalHargaFormEdit($form); // Panggil fungsi untuk form edit (modal)
                    }
                    $errorDiv.addClass('d-none');
                    $btnSimpan.prop('disabled', false);
                    $hargaHelp.text(`Harga ini diambil dari daftar harga beli terbaru yang berlaku pada tanggal ${tanggalBerlaku}.`);
                } else {
                    $hargaField.val('');
                    $totalField.val('');
                    $errorDiv.removeClass('d-none');
                    $btnSimpan.prop('disabled', true);
                    $hargaHelp.text('Tidak ada harga beli yang berlaku untuk jenis pohon ini pada tanggal yang dipilih.');
                }
            });
        }


        // Event handler untuk dropdown petani di form TAMBAH
        $('#petani').change(function() {
            let petaniId = $(this).val();
            let $jenisPohon = $('#jenis_pohon');
            loadJenisPohon(petaniId, $jenisPohon);

            // Reset field harga dan total saat petani diubah
            $('#harga_saat_transaksi').val('');
            $('#total_harga').val('');
            $('#errorHargaMessage').addClass('d-none');
            $('#btnSimpanKopiMasuk').prop('disabled', false);
            $('#hargaHelp').text('Harga ini di ambil dari daftar harga kopi yang berlaku dari tanggal data master Jenis Kopi');
        });

        // Event handler untuk dropdown jenis pohon (TAMBAH)
        $('#jenis_pohon').change(function() {
            let petaniPohonId = $(this).val();
            let tanggal = $('#tanggal').val(); // Ambil tanggal saat ini
            let $form = $('#formTambahKopi'); // Ganti dengan ID form Anda jika berbeda

            if (petaniPohonId && tanggal) {
                updateHargaSaatTransaksi($form, petaniPohonId, tanggal);
            } else {
                $('#harga_saat_transaksi').val('');
                $('#total_harga').val('');
                $('#errorHargaMessage').addClass('d-none');
                $('#btnSimpanKopiMasuk').prop('disabled', false);
            }
        });

        // Event handler untuk input tanggal (TAMBAH)
        $('#tanggal').change(function() {
            let petaniPohonId = $('#jenis_pohon').val();
            let tanggal = $(this).val();
            let $form = $('#formTambahKopi'); // Pastikan $form didefinisikan

            if (petaniPohonId && tanggal) {
                updateHargaSaatTransaksi($form, petaniPohonId, tanggal);
            } else {
                $('#harga_saat_transaksi').val('');
                $('#total_harga').val('');
                $('#errorHargaMessage').addClass('d-none');
                $('#btnSimpanKopiMasuk').prop('disabled', false);
            }
        });

        // Tambahkan ini untuk menghitung ulang total saat jumlah diubah (TAMBAH)
        $('#jumlah').on('input', function() {
            calculateTotalHargaFormTambah(); // Gunakan fungsi yang benar
        });

        // Event handler saat modal EDIT ditampilkan
        $('.modal').on('shown.bs.modal', function() {
            let $modal = $(this);
            let $jenisPohon = $modal.find('.jenis-pohon-dropdown');
            if (!$jenisPohon.length) return; // Keluar jika bukan modal edit

            let $tanggal = $modal.find('input[name="tanggal"]');
            let $form = $modal.find('form'); // Ambil form di dalam modal

            // Hanya load jika dropdown masih kosong
            if ($jenisPohon.find('option').length <= 1) {
                let petaniId = $jenisPohon.data('petani');
                let selectedId = $jenisPohon.data('selected');
                loadJenisPohon(petaniId, $jenisPohon, selectedId);
            }

            // Event handler untuk dropdown jenis pohon di modal EDIT
            $jenisPohon.off('change.updateHarga').on('change.updateHarga', function() {
                let petaniPohonId = $(this).val();
                let tanggal = $tanggal.val();

                if (petaniPohonId && tanggal) {
                    updateHargaSaatTransaksi($form, petaniPohonId, tanggal);
                } else {
                    $form.find('#harga_saat_transaksi').val('');
                    $form.find('#total_harga').val('');
                    $form.find('#errorHargaMessage').addClass('d-none');
                    $form.find('#btnSimpanKopiMasuk').prop('disabled', false);
                }
            });

            // Event handler untuk input tanggal di modal EDIT
            $tanggal.off('change.updateHarga').on('change.updateHarga', function() {
                let petaniPohonId = $jenisPohon.val();
                let tanggal = $(this).val();

                if (petaniPohonId && tanggal) {
                    updateHargaSaatTransaksi($form, petaniPohonId, tanggal);
                } else {
                    $form.find('#harga_saat_transaksi').val('');
                    $form.find('#total_harga').val('');
                    $form.find('#errorHargaMessage').addClass('d-none');
                    $form.find('#btnSimpanKopiMasuk').prop('disabled', false);
                }
            });

            // Tambahkan ini untuk menghitung ulang total saat jumlah diubah di modal edit
            $form.find('#jumlah').off('input.calculateTotal').on('input.calculateTotal', function() {
                calculateTotalHargaFormEdit($form); // Gunakan fungsi yang benar
            });

            // Inisialisasi nilai awal saat modal muncul
            let petaniPohonIdAwal = $jenisPohon.val();
            let tanggalAwal = $tanggal.val();
            if (petaniPohonIdAwal && tanggalAwal) {
                updateHargaSaatTransaksi($form, petaniPohonIdAwal, tanggalAwal);
            }
        });

        // Event handler jika petani diganti di dalam modal EDIT
        $(document).on('change', '.modal select[name="petani_user_id"]', function() {
            let $modal = $(this).closest('.modal');
            let $jenisPohon = $modal.find('.jenis-pohon-dropdown');
            let petaniId = $(this).val();

            loadJenisPohon(petaniId, $jenisPohon);

            // Reset field harga dan total saat petani diubah di modal
            $modal.find('#harga_saat_transaksi').val('');
            $modal.find('#total_harga').val('');
            $modal.find('#errorHargaMessage').addClass('d-none');
            $modal.find('#btnSimpanKopiMasuk').prop('disabled', false);
            $modal.find('#hargaHelp').text('Harga ini di ambil dari daftar harga kopi yang berlaku dari tanggal data master Jenis Kopi');
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
