<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h1 class="h3 mb-1 page-title">Kopi Keluar</h1>
            <p class="mb-0 page-subtitle">Fungsi: Mencatat kopi yang Distribusikan (penjualan/penyerahan).</p>
        </div>
        <div class="mt-2 mt-md-0">
            <span class="badge badge-info p-2">Sisa Semua Kopi Petani: <b><?= number_format($stok, 2, ',', '.') ?> Kg</b></span>
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
                        <th>Nama Petani</th> <!-- ✅ UBAH DARI "Jenis Kopi" -->
                        <th>Jenis Kopi</th>
                        <th>Tujuan</th>
                        <th class="text-center">Jumlah (Kg)</th>
                        <th class="text-right">Harga Jual/Kg</th>
                        <th class="text-right">Total Harga</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kopikeluar)): ?>
                        <?php $nomor = ($currentPage - 1) * $perPage + 1; ?>
                        <?php foreach ($kopikeluar as $k): ?>
                            <tr>
                                <td><?= $nomor++ ?></td>
                                <td><strong><?= esc($k['nama_petani'] ?? '-') ?></strong></td> <!-- ✅ TAMBAHAN -->
                                <td><?= esc($k['nama_pohon'] ?? '-') ?></td>
                                <td><?= esc($k['tujuan']) ?></td>
                                <td class="text-right"><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</td>
                                <td class="text-right">
                                    <?= isset($k['harga_saat_transaksi']) ? 'Rp&nbsp;' . number_format($k['harga_saat_transaksi'], 0, ',', '.') : '<span class="text-muted">–</span>' ?>
                                </td>
                                <td class="text-right">
                                    <?= isset($k['total_harga_jual']) ? 'Rp&nbsp;' . number_format($k['total_harga_jual'], 0, ',', '.') : '<span class="text-muted">–</span>' ?>
                                </td>
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
                    <?php else : ?>
                        <tr>
                            <td colspan="11" class="text-center">Belum ada data</td>
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
    <!-- Mobile View -->
    <div class="d-block d-lg-none">
        <?php if (!empty($kopikeluar)): ?>
            <?php foreach ($kopikeluar as $k): ?>
                <div class="card shadow mb-3">
                    <div class="card-body">
                        <!-- Header: Nama Petani & Jenis Kopi -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="font-weight-bold text-primary mb-1">
                                    <?= esc($k['nama_petani'] ?? '-') ?> <!-- ✅ UBAH -->
                                </h6>
                                <p class="mb-0">
                                    <strong>Jenis Kopi:</strong> <?= esc($k['nama_pohon'] ?? '-') ?> <!-- ✅ TAMBAHAN -->
                                </p>
                                <p class="mb-0"><strong>Tujuan:</strong> <?= esc($k['tujuan']) ?></p>
                            </div>
                            <span class="badge badge-pill badge-secondary"><?= date('d M Y', strtotime($k['tanggal'])) ?></span>
                        </div>

                        <!-- Blok Informasi Vertikal (Centered) -->
                        <div class="text-center mb-3">

                            <!-- Jumlah Keluar -->
                            <div class="mb-2 p-2 rounded border" style="background-color: #f8f9fa; border-left: 4px solid #007bff;">
                                <small class="text-muted d-block">JUMLAH KELUAR</small>
                                <p class="font-weight-bold mb-0" style="color: #007bff; font-size: 1.1rem;">
                                    <?= number_format($k['jumlah'], 2, ',', '.') ?> Kg
                                </p>
                            </div>

                            <!-- Sisa Stok Jenis Ini -->
                            <div class="mb-2 p-2 rounded border" style="background-color: #f8f9fa; border-left: 4px solid #28a745;">
                                <small class="text-muted d-block">SISA STOK JENIS INI</small>
                                <p class="font-weight-bold mb-0" style="color: #dc3545; font-size: 1.1rem;">
                                    <?= isset($k['sisa_stok_jenis']) ? number_format($k['sisa_stok_jenis'], 2, ',', '.') . ' Kg' : '-' ?>
                                </p>
                            </div>

                            <!-- Harga/Kg -->
                            <div class="mb-2 p-2 rounded border" style="background-color: #f8f9fa; border-left: 4px solid #ffc107;">
                                <small class="text-muted d-block">HARGA/KG</small>
                                <p class="font-weight-bold mb-0" style="color: #ffc107; font-size: 1.1rem;">
                                    <?= isset($k['harga_saat_transaksi']) ? 'Rp&nbsp;' . number_format($k['harga_saat_transaksi'], 0, ',', '.') : '–' ?>
                                </p>
                            </div>

                            <!-- Total -->
                            <div class="mb-2 p-2 rounded border" style="background-color: #f8f9fa; border-left: 4px solid #dc3545;">
                                <small class="text-muted d-block">TOTAL</small>
                                <p class="font-weight-bold mb-0" style="color: #32bf37ff; font-size: 1.1rem;">
                                    <?= isset($k['total_harga_jual']) ? 'Rp&nbsp;' . number_format($k['total_harga_jual'], 0, ',', '.') : '–' ?>
                                </p>
                            </div>

                        </div>

                        <!-- Keterangan -->
                        <?php if (!empty($k['keterangan'])): ?>
                            <div class="border-top pt-2 mt-2">
                                <small><strong>Ket:</strong> <?= esc($k['keterangan']) ?></small>
                            </div>
                        <?php endif; ?>

                        <!-- Aksi (Edit & Hapus) -->
                        <div class="mt-3 border-top pt-3 d-flex justify-content-end gap-2">
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

<!-- MODAL TAMBAH KOPI KELUAR - UPDATED VERSION -->
<div class="modal fade" id="modalTambahKopiKeluar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('kopikeluar/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2"></i>Form Tambah Kopi Keluar
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- ✅ DROPDOWN PETANI (BARU) -->
                    <div class="form-group">
                        <label for="petani_id">
                            Pilih Petani <span class="text-danger">*</span>
                        </label>
                        <select name="petani_id" id="petani_id" class="form-control" required>
                            <option value="">-- Pilih Petani --</option>
                            <?php foreach ($petaniList as $p): ?>
                                <option value="<?= $p['user_id'] ?>">
                                    <?= esc($p['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- ✅ DROPDOWN JENIS KOPI (DINAMIS BERDASARKAN PETANI) -->
                    <div class="form-group">
                        <label for="stok_kopi_id">
                            Pilih Jenis Kopi <span class="text-danger">*</span>
                        </label>
                        <select name="stok_kopi_id" id="stok_kopi_id" class="form-control" required disabled>
                            <option value="">-- Pilih Petani Dulu --</option>
                        </select>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Pilih petani terlebih dahulu
                        </small>
                    </div>

                    <!-- Tujuan -->
                    <div class="form-group">
                        <label for="tujuan">
                            Tujuan <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            name="tujuan"
                            id="tujuan"
                            class="form-control"
                            placeholder="Contoh: Ekspor Jakarta, Pasar Lokal, dll"
                            required>
                    </div>

                    <!-- Jumlah -->
                    <div class="form-group">
                        <label for="preview_jumlah">
                            Jumlah (Kg) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            name="jumlah"
                            id="preview_jumlah"
                            class="form-control"
                            placeholder="Contoh: 15 atau 15.5"
                            autocomplete="off"
                            required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Gunakan titik (.) atau koma (,) untuk desimal
                        </small>
                    </div>

                    <!-- Tanggal Keluar -->
                    <div class="form-group">
                        <label for="tanggal">
                            Tanggal Keluar <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                            name="tanggal"
                            id="tanggal"
                            class="form-control"
                            value="<?= date('Y-m-d') ?>"
                            required>
                    </div>

                    <!-- Harga Jual/Kg (Otomatis) -->
                    <div class="form-group">
                        <label for="preview_harga">
                            Harga Jual/Kg <span class="badge badge-info">Otomatis</span>
                        </label>
                        <input type="text"
                            id="preview_harga"
                            class="form-control bg-light"
                            readonly
                            placeholder="Harga akan muncul otomatis">
                        <small class="form-text text-muted">
                            <i class="fas fa-lightbulb"></i> Harga ini diambil dari daftar harga jual Bumdes terbaru yang berlaku pada tanggal keluar.
                        </small>
                    </div>

                    <!-- Total Harga (Otomatis) -->
                    <div class="form-group">
                        <label for="preview_total">
                            Total Harga <span class="badge badge-info">Otomatis</span>
                        </label>
                        <input type="text"
                            id="preview_total"
                            class="form-control bg-light"
                            readonly
                            placeholder="Total harga akan dihitung otomatis">
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                        <label for="keterangan">Keterangan <span class="text-muted">(Opsional)</span></label>
                        <textarea name="keterangan"
                            id="keterangan"
                            class="form-control"
                            rows="3"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <!-- Info Alert -->
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Catatan:</strong> Pastikan semua data terisi dengan benar sebelum menyimpan.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit" id="btnSimpan" class="btn btn-primary" disabled>
                        <i class="fas fa-save mr-1"></i>Simpan Data
                    </button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                    <div class="form-group">
                        <label>Tujuan</label>
                        <input type="text" id="edit_tujuan" name="tujuan" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah (Kg)</label>
                        <input type="text" id="edit_jumlah" name="jumlah" class="form-control" placeholder="Contoh: 15 atau 1000.5" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Keluar</label>
                        <input type="date" id="edit_tanggal" name="tanggal" class="form-control" required>
                    </div>
                    <!-- Harga Jual/Kg -->
                    <div class="form-group">
                        <label for="edit_harga">Harga Jual/Kg <span class="text-muted">(otomatis)</span></label>
                        <input type="text"
                            id="edit_harga"
                            class="form-control bg-light"
                            readonly
                            placeholder="Pilih jenis & tanggal dulu">
                        <small class="form-text text-muted">
                            <i class="fas fa-lightbulb"></i> Harga akan muncul otomatis setelah memilih jenis kopi dan tanggal
                        </small>
                    </div>

                    <!-- Total Harga -->
                    <div class="form-group">
                        <label for="edit_total">Total Harga <span class="text-muted">(otomatis)</span></label>
                        <input type="text"
                            id="edit_total"
                            class="form-control bg-light"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea id="edit_keterangan" name="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpanEdit" class="btn btn-warning" disabled>Update</button>
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


<script>
    $(document).ready(function() {

        // ───────────────────────────────────────────────────────────
        // STATE MANAGEMENT - Simpan data harga untuk menghindari race condition
        // ───────────────────────────────────────────────────────────
        let hargaDataTambah = {
            hargaPerKg: 0,
            isLoading: false,
            isValid: false
        };

        let hargaDataEdit = {
            hargaPerKg: 0,
            isLoading: false,
            isValid: false
        };

        // ───────────────────────────────────────────────────────────
        // FUNGSI: Format Angka ke Format Indonesia (1.000.000)
        // ───────────────────────────────────────────────────────────
        function formatRupiah(angka) {
            if (!angka || isNaN(angka)) return '';
            return parseFloat(angka).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }
        // Fungsi untuk update display harga dengan format Rupiah
        function updateHargaDisplay(modalType, harga) {
            const prefix = modalType === 'tambah' ? 'preview' : 'edit';
            const formattedHarga = formatRupiah(harga);
            $(`#${prefix}_harga`).val(formattedHarga ? `Rp ${formattedHarga}` : '');
        }

        // Fungsi untuk update display total dengan format Rupiah
        function updateTotalDisplay(modalType) {
            const prefix = modalType === 'tambah' ? 'preview' : 'edit';
            const jumlahInput = $(`#${prefix}_jumlah`).val();
            const hargaData = modalType === 'tambah' ? hargaDataTambah : hargaDataEdit;

            if (hargaData.isValid && hargaData.hargaPerKg > 0) {
                const total = hitungTotal(jumlahInput, hargaData.hargaPerKg);
                $(`#${prefix}_total`).val(`Rp ${formatRupiah(total)}`);
            } else {
                $(`#${prefix}_total`).val('');
            }
        }

        // ───────────────────────────────────────────────────────────
        // FUNGSI: Parse Input Jumlah (Support format: 15, 15.5, 15,5)
        // ───────────────────────────────────────────────────────────
        function parseJumlah(value) {
            if (!value) return 0;
            // Hapus semua karakter selain angka, titik, dan koma
            let cleaned = value.toString().replace(/[^0-9.,]/g, '');
            // Ganti koma dengan titik untuk parsing
            cleaned = cleaned.replace(',', '.');
            // Ambil hanya satu titik desimal
            const parts = cleaned.split('.');
            if (parts.length > 2) {
                cleaned = parts[0] + '.' + parts.slice(1).join('');
            }
            return parseFloat(cleaned) || 0;
        }

        // ───────────────────────────────────────────────────────────
        // FUNGSI: Hitung Total Harga
        // ───────────────────────────────────────────────────────────
        function hitungTotal(jumlah, hargaPerKg) {
            const jml = parseJumlah(jumlah);
            const hrg = parseFloat(hargaPerKg) || 0;
            return jml * hrg;
        }

        // ───────────────────────────────────────────────────────────
        // FUNGSI: Update Total Display
        // ───────────────────────────────────────────────────────────
        function updateTotalDisplay(modalType) {
            // ✅ PERBAIKAN: Gunakan 'preview' sebagai prefix untuk modal tambah
            const prefix = modalType === 'tambah' ? 'preview' : 'edit';
            const jumlahInput = $(`#${prefix}_jumlah`).val(); // Perhatikan juga perubahan di sini untuk konsistensi
            const hargaData = modalType === 'tambah' ? hargaDataTambah : hargaDataEdit;
            if (hargaData.isValid && hargaData.hargaPerKg > 0) {
                const total = hitungTotal(jumlahInput, hargaData.hargaPerKg);
                $(`#${prefix}_total`).val(formatRupiah(total));
            } else {
                $(`#${prefix}_total`).val('');
            }
        }

        // ───────────────────────────────────────────────────────────
        // FUNGSI: Validasi & Enable/Disable Tombol Simpan
        // ───────────────────────────────────────────────────────────
        function validateForm(modalType) {
            const btnId = modalType === 'tambah' ? '#btnSimpan' : '#btnSimpanEdit';
            const hargaData = modalType === 'tambah' ? hargaDataTambah : hargaDataEdit;

            // ✅ PERBAIKI SELECTOR
            const stokKopiId = modalType === 'tambah' ?
                $('select[name="stok_kopi_id"]').val() :
                $('#edit_stok_kopi_id').val();

            const tujuan = modalType === 'tambah' ?
                $('#tujuan').val() :
                $('#edit_tujuan').val();

            const jumlah = modalType === 'tambah' ?
                parseJumlah($('#preview_jumlah').val()) // ✅ UBAH DI SINI
                :
                parseJumlah($('#edit_jumlah').val());

            const tanggal = modalType === 'tambah' ?
                $('input[name="tanggal"]').val() :
                $('#edit_tanggal').val();

            // Validasi lengkap
            const isValid = stokKopiId &&
                tujuan &&
                jumlah > 0 &&
                tanggal &&
                hargaData.isValid &&
                !hargaData.isLoading;

            $(btnId).prop('disabled', !isValid);

            return isValid;
        }

        // ───────────────────────────────────────────────────────────
        // FUNGSI: Ambil Harga dari Server (ROBUST - dengan retry)
        // ───────────────────────────────────────────────────────────
        function fetchHarga(stokKopiId, tanggal, modalType, retryCount = 0) {
            const prefix = modalType === 'tambah' ? 'preview' : 'edit';
            const hargaData = modalType === 'tambah' ? hargaDataTambah : hargaDataEdit;
            const MAX_RETRY = 2;

            // Reset state
            hargaData.isValid = false;
            hargaData.isLoading = true;
            hargaData.hargaPerKg = 0;

            // Validasi input
            if (!stokKopiId || !tanggal) {
                $(`#${prefix}_harga`).val('');
                $(`#${prefix}_total`).val('');
                hargaData.isLoading = false;
                validateForm(modalType);
                return;
            }

            // Format tanggal (pastikan YYYY-MM-DD)
            let formattedDate = tanggal;
            if (tanggal.includes('/')) {
                const [day, month, year] = tanggal.split('/');
                if (!day || !month || !year) {
                    $(`#${prefix}_harga`).val('Format tanggal salah');
                    hargaData.isLoading = false;
                    validateForm(modalType);
                    return;
                }
                formattedDate = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            }

            // Tampilkan loading
            $(`#${prefix}_harga`).val('Mengambil harga...').prop('readonly', true);
            $(`#${prefix}_total`).val('');

            // Ambil CSRF token
            const csrfTokenMeta = document.head.querySelector('meta[name="csrf_token"]');
            if (!csrfTokenMeta) {
                Swal.fire('Error', 'CSRF Token tidak ditemukan.', 'error');
                hargaData.isLoading = false;
                validateForm(modalType);
                return;
            }

            const csrfTokenName = csrfTokenMeta.getAttribute('name');
            const csrfTokenValue = csrfTokenMeta.content;

            // Prepare data
            let dataToSend = {
                stok_kopi_id: stokKopiId,
                tanggal: formattedDate
            };
            dataToSend[csrfTokenName] = csrfTokenValue;

            // AJAX Request dengan timeout
            $.ajax({
                url: "<?= site_url('api-harga/get-harga-jual') ?>",
                method: "POST",
                data: dataToSend,
                dataType: "json",
                timeout: 10000, // 10 detik timeout
                success: function(res) {
                    hargaData.isLoading = false;

                    if (res.status === 'success' && res.data && res.data.harga_jual_per_kg) {
                        const harga = parseFloat(res.data.harga_jual_per_kg);

                        // Simpan ke state
                        hargaData.hargaPerKg = harga;
                        hargaData.isValid = true;

                        // ✅ Update display dengan format Rupiah
                        updateHargaDisplay(modalType, harga);

                        // Hitung dan update total
                        updateTotalDisplay(modalType);

                        // Validasi form
                        validateForm(modalType);
                    } else {
                        // Harga tidak tersedia
                        hargaData.isValid = false;
                        $(`#${prefix}_harga`).val('Harga tidak tersedia');
                        $(`#${prefix}_total`).val('');
                        validateForm(modalType);

                        if (res.message) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Harga Tidak Tersedia',
                                text: res.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    hargaData.isLoading = false;

                    // Retry logic
                    if (retryCount < MAX_RETRY && status === 'timeout') {
                        console.warn(`Timeout, mencoba lagi (${retryCount + 1}/${MAX_RETRY})...`);
                        setTimeout(() => {
                            fetchHarga(stokKopiId, tanggal, modalType, retryCount + 1);
                        }, 1000);
                        return;
                    }

                    // Error handling
                    hargaData.isValid = false;
                    $(`#${prefix}_harga`).val('Error mengambil harga');
                    $(`#${prefix}_total`).val('');
                    validateForm(modalType);

                    console.error('AJAX Error:', error, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengambil Harga',
                        text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // ───────────────────────────────────────────────────────────
        // FUNGSI: Reset Modal State
        // ───────────────────────────────────────────────────────────
        function resetModalState(modalType) {
            const prefix = modalType === 'tambah' ? 'preview' : 'edit';
            const hargaData = modalType === 'tambah' ? hargaDataTambah : hargaDataEdit;

            hargaData.hargaPerKg = 0;
            hargaData.isLoading = false;
            hargaData.isValid = false;

            $(`#${prefix}_harga`).val('');
            $(`#${prefix}_total`).val('');

            validateForm(modalType);
        }

        // ═══════════════════════════════════════════════════════════
        // EVENT HANDLERS - MODAL TAMBAH
        // ═══════════════════════════════════════════════════════════

        // Reset state saat modal dibuka
        $('#modalTambahKopiKeluar').on('show.bs.modal', function() {
            resetModalState('tambah');
        });

        // Event: Perubahan Jenis Kopi atau Tanggal
        $('#modalTambahKopiKeluar').on('change', 'select[name="stok_kopi_id"], input[name="tanggal"]', function() {
            const petaniId = $('#petani_id').val(); // ✅ TAMBAHAN validasi petani
            const stokId = $('select[name="stok_kopi_id"]').val();
            const tgl = $('input[name="tanggal"]').val();

            if (petaniId && stokId && tgl) { // ✅ TAMBAHAN kondisi petaniId
                fetchHarga(stokId, tgl, 'tambah');
            }
        });
        $('#modalTambahKopiKeluar').on('change', '#petani_id', function() {
            const petaniId = $(this).val();
            const $stokKopiSelect = $('#stok_kopi_id');

            // Reset dropdown jenis kopi
            $stokKopiSelect.html('<option value="">-- Loading... --</option>').prop('disabled', true);
            $('#preview_harga, #preview_total').val('');
            resetModalState('tambah');

            if (!petaniId) {
                $stokKopiSelect.html('<option value="">-- Pilih Petani Dulu --</option>');
                return;
            }

            // Ambil CSRF token
            const csrfTokenMeta = document.head.querySelector('meta[name="csrf_token"]');
            if (!csrfTokenMeta) {
                Swal.fire('Error', 'CSRF Token tidak ditemukan.', 'error');
                return;
            }
            const csrfToken = csrfTokenMeta.getAttribute('name');
            const csrfHash = csrfTokenMeta.content;

            // AJAX request
            $.ajax({
                url: "<?= site_url('kopikeluar/getJenisPohonByPetani') ?>",
                method: "POST",
                data: {
                    petani_id: petaniId,
                    [csrfToken]: csrfHash
                },
                dataType: "json",
                success: function(data) {
                    if (data.length > 0) {
                        let options = '<option value="">-- Pilih Jenis Kopi --</option>';
                        data.forEach(item => {
                            options += `<option value="${item.id}">
                        ${item.nama_jenis} - Stok: ${parseFloat(item.stok).toLocaleString('id-ID', {minimumFractionDigits: 2})} Kg
                    </option>`;
                        });
                        $stokKopiSelect.html(options).prop('disabled', false);
                    } else {
                        $stokKopiSelect.html('<option value="">-- Tidak Ada Stok Kopi --</option>');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Ada Stok',
                            text: 'Petani ini belum memiliki stok kopi.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    $stokKopiSelect.html('<option value="">-- Error Memuat Data --</option>');
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat mengambil data jenis kopi.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });


        // Event: Input Jumlah (dengan debounce untuk performa)
        let jumlahTimeout;
        $('#modalTambahKopiKeluar').on('input', '#preview_jumlah', function() { // ✅ UBAH SELECTOR
            // Format input
            let value = $(this).val();
            value = value.replace(/[^0-9.,]/g, '');
            const parts = value.split(/[.,]/);
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            $(this).val(value);

            // Debounce untuk update total
            clearTimeout(jumlahTimeout);
            jumlahTimeout = setTimeout(() => {
                updateTotalDisplay('tambah');
                validateForm('tambah');
            }, 300);
        });

        // Event: Input Tujuan
        $('#modalTambahKopiKeluar').on('input', 'input[name="tujuan"]', function() {
            validateForm('tambah');
        });

        // ═══════════════════════════════════════════════════════════
        // EVENT HANDLERS - MODAL EDIT
        // ═══════════════════════════════════════════════════════════

        // Reset state saat modal edit dibuka
        $('#modalEditKopiKeluar').on('show.bs.modal', function() {
            resetModalState('edit');
        });

        // Event: Perubahan Jenis Kopi atau Tanggal
        $('#modalEditKopiKeluar').on('change', '#edit_stok_kopi_id, #edit_tanggal', function() {
            const stokId = $('#edit_stok_kopi_id').val();
            const tgl = $('#edit_tanggal').val();
            fetchHarga(stokId, tgl, 'edit');
        });

        // Event: Input Jumlah
        let editJumlahTimeout;
        $('#modalEditKopiKeluar').on('input', '#edit_jumlah', function() {
            // Format input
            let value = $(this).val();
            value = value.replace(/[^0-9.,]/g, '');
            const parts = value.split(/[.,]/);
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            $(this).val(value);

            // Debounce untuk update total
            clearTimeout(editJumlahTimeout);
            editJumlahTimeout = setTimeout(() => {
                updateTotalDisplay('edit');
                validateForm('edit');
            }, 300);
        });

        // Event: Input Tujuan
        $('#modalEditKopiKeluar').on('input', '#edit_tujuan', function() {
            validateForm('edit');
        });

        // ───────────────────────────────────────────────────────────
        // FUNGSI: Load Data untuk Edit
        // ───────────────────────────────────────────────────────────
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

                        // Update action URL
                        $('#formEditKopiKeluar').attr('action', `<?= base_url('kopikeluar/update') ?>/${id}`);

                        // Fetch harga berdasarkan data yang ada
                        fetchHarga(data.stok_kopi_id, data.tanggal, 'edit');

                        // Show modal
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

        // Event: Klik tombol edit
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            showEditModal(id);
        });

        // ───────────────────────────────────────────────────────────
        // REQUEST ACCESS (Minta Izin)
        // ───────────────────────────────────────────────────────────
        $(document).on('click', '.btn-request-access', function() {
            const button = $(this);
            const kopiKeluarId = button.data('kopikeluar-id');
            const action = button.data('action-type');

            const csrfTokenMeta = document.head.querySelector('meta[name="csrf_token"]');
            if (!csrfTokenMeta) {
                Swal.fire('Error', 'CSRF Token tidak ditemukan.', 'error');
                return;
            }
            const csrfToken = csrfTokenMeta.getAttribute('name');
            const csrfHash = csrfTokenMeta.content;

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: "<?= site_url('kopikeluar/requestAccess') ?>",
                method: "POST",
                data: {
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
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                        button.prop('disabled', false).html('<i class="fas fa-lock"></i>');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan koneksi. Coba lagi.'
                    });
                    console.error('AJAX Error:', error);
                    button.prop('disabled', false).html('<i class="fas fa-lock"></i>');
                }
            });
        });

        // ───────────────────────────────────────────────────────────
        // Force Close Modal (Bootstrap 4 Fallback)
        // ───────────────────────────────────────────────────────────
        $(document).on('click', '[data-dismiss="modal"]', function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>
<?= $this->endSection() ?>