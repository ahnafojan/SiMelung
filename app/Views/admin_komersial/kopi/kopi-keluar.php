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
            <span class="badge badge-info p-2">Sisa Stok: <b><?= number_format($stok, 2, ',', '.') ?> Kg</b></span>
        </div>
    </div>

    <!-- Tombol Tambah Data -->
    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKopiKeluar">
            <i class="fas fa-plus"></i> Tambah Data Kopi Keluar
        </button>
    </div>

    <!-- Modal Form Tambah Data -->
    <div class="modal fade" id="modalTambahKopiKeluar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url('kopikeluar/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Form Tambah Kopi Keluar</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <!-- Dropdown Jenis Kopi -->
                        <div class="form-group">
                            <label for="stok_kopi_id">Pilih Jenis Kopi</label>
                            <select id="stok_kopi_id" name="stok_kopi_id" class="form-control" required>
                                <option value="">-- Pilih Jenis Kopi --</option>
                                <?php foreach ($stokKopi as $s): ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= esc($s['nama_pohon']) ?> -
                                        Stok Global: <?= isset($s['total_stok']) ? number_format($s['total_stok'], 2, ',', '.') : '0,00' ?> Kg
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Input Tujuan -->
                        <div class="form-group">
                            <label>Tujuan</label>
                            <input type="text" name="tujuan" class="form-control" required>
                        </div>

                        <!-- Input Jumlah -->
                        <div class="form-group">
                            <label>Jumlah (Kg)</label>
                            <input type="number" name="jumlah" step="0.01" class="form-control" min="0.01" required>
                        </div>

                        <!-- Input Tanggal -->
                        <div class="form-group">
                            <label>Tanggal Keluar</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Input Keterangan -->
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
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
    <!-- Tabel Data Kopi Keluar -->
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Jenis Kopi</th>
                        <th>Tujuan</th>
                        <th>Jumlah (Kg)</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Total Stok (Saat Input)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (!empty($kopikeluar)): ?>
                        <?php foreach ($kopikeluar as $index => $k): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <!-- Jenis Kopi -->
                                <td><?= esc($k['nama_pohon'] ?? '-') ?></td>

                                <td><?= esc($k['tujuan']) ?></td>
                                <td><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</td>
                                <td><?= esc($k['tanggal']) ?></td>
                                <td><?= esc($k['keterangan']) ?></td>
                                <td>
                                    <?= isset($k['total_stok'])
                                        ? number_format($k['total_stok'], 2, ',', '.') . ' Kg'
                                        : '-' ?>
                                </td>

                                <td>
                                    <!-- Tombol Hapus -->
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopiKeluar<?= $k['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                            <!-- Modal Hapus Kopi Keluar -->
                            <div class="modal fade" id="modalHapusKopiKeluar<?= $k['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="<?= base_url('kopikeluar/delete/' . $k['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus data <b>kopi keluar</b> untuk jenis kopi
                                                <strong><?= esc($k['nama_pohon']) ?></strong> pada tanggal
                                                <strong><?= date('d-m-Y', strtotime($k['tanggal'])) ?></strong>
                                                sebanyak <strong><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</strong>?
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
                            <td colspan="8">Belum ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>