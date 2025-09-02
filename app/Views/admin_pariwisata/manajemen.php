<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Manajemen Aset Pariwisata</h1>
            <p class="mb-0 page-subtitle text-muted">Data aset pariwisata hanya dapat ditambahkan. Admin tidak bisa mengubah atau menghapus.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAset">
            <i class="fas fa-plus-circle me-1"></i> Tambah Aset Baru
        </button>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Pariwisata</th>
                            <th>Foto Aset</th>
                            <th>Nama Aset</th>
                            <th>Kode & NUP</th>
                            <th>Tahun</th>
                            <th>Nilai Perolehan</th>
                            <th>Pengadaan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($asets)) : ?>
                            <?php foreach ($asets as $index => $a) : ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $index + 1 ?></td>
                                    <td><?= esc($a['nama_pariwisata']) ?></td>
                                    <td>
                                        <?php if ($a['foto_aset']) : ?>
                                            <a href="<?= base_url('uploads/aset_pariwisata/' . $a['foto_aset']) ?>" data-lightbox="aset-images" data-title="<?= esc($a['nama_aset']) ?>">
                                                <img src="<?= base_url('uploads/aset_pariwisata/' . $a['foto_aset']) ?>" alt="<?= esc($a['nama_aset']) ?>" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted fst-italic">Tidak ada foto</span>
                                        <?php endif ?>
                                    </td>
                                    <td><?= esc($a['nama_aset']) ?></td>
                                    <td>
                                        <span class="fw-bold d-block"><?= esc($a['kode_aset']) ?></span>
                                        <small class="text-muted">NUP: <?= esc($a['nup']) ?: '-' ?></small>
                                    </td>
                                    <td><?= esc($a['tahun_perolehan']) ?></td>
                                    <td>Rp <?= number_format($a['nilai_perolehan'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="d-block"><?= esc($a['metode_pengadaan']) ?></span>
                                        <small class="text-muted"><?= esc($a['sumber_pengadaan']) ?></small>
                                    </td>
                                    <td class="text-muted fst-italic"><?= esc($a['keterangan']) ?: 'Tidak ada keterangan' ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <p class="fw-bold mb-0">Belum ada data aset yang dicatat.</p>
                                        <p>Silakan tambahkan data aset baru melalui tombol di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Aset -->
<div class="modal fade" id="modalTambahAset" tabindex="-1" aria-labelledby="modalTambahAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('asetpariwisata/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahAsetLabel">Tambah Aset Pariwisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pariwisata</label>
                            <input type="text" name="nama_pariwisata" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Aset</label>
                            <input type="text" name="nama_aset" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kode Aset</label>
                            <input type="text" name="kode_aset" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor Urut Perolehan (NUP)</label>
                            <input type="text" name="nup" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun Perolehan</label>
                            <input type="number" name="tahun_perolehan" class="form-control" min="1900" max="2100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nilai Perolehan (Rp)</label>
                            <input type="number" name="nilai_perolehan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Metode Pengadaan</label>
                            <select name="metode_pengadaan" class="form-select" required>
                                <option value="">-- Pilih Jenis Pengadaan --</option>
                                <option value="Hibah">Hibah</option>
                                <option value="Pembelian">Pembelian</option>
                                <option value="Penyewaan">Penyewaan</option>
                                <option value="Peminjaman">Peminjaman</option>
                                <option value="Penukaran">Penukaran</option>
                                <option value="Pembuatan sendiri">Pembuatan sendiri</option>
                                <option value="Perbaikan/rekondisi">Perbaikan/rekondisi</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Sumber Pengadaan</label>
                            <input type="text" name="sumber_pengadaan" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Foto Aset</label>
                            <input type="file" name="foto_aset" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>