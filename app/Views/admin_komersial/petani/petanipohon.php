<?php
// Tentukan path foto petani
$fotoPath = FCPATH . 'uploads/foto_petani/' . $petani['foto'];
$fotoUrl  = base_url('uploads/foto_petani/' . $petani['foto']);

// Default avatar kalau file tidak ada
if (empty($petani['foto']) || !file_exists($fotoPath)) {
    $fotoUrl = base_url('uploads/foto_petani/default.png');
}
?>

<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>



<div class="container-fluid py-4">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Detail Data Petani</h1>
            <p class="mb-0 page-subtitle">Manajemen data pohon untuk petani terpilih.</p>
        </div>
        <a href="<?= site_url('petani') ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Kolom Profil Petani -->
        <div class="col-xl-3 col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <!-- Foto Profil -->
                    <img src="<?= $fotoUrl ?>"
                        alt="Foto <?= esc($petani['nama']) ?>"
                        class="rounded-circle mb-3"
                        style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e9ecef;">

                    <!-- Info Petani -->
                    <h5 class="font-weight-bold mb-1"><?= esc($petani['nama']) ?></h5>
                    <p class="text-muted small mb-3">ID Petani: <?= esc($petani['user_id']) ?></p>

                    <hr class="my-3">

                    <div class="text-left">
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt fa-fw mr-2 text-secondary"></i>
                            <?= esc($petani['alamat']) ?>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-phone fa-fw mr-2 text-secondary"></i>
                            <?= esc($petani['no_hp']) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Form & Tabel Pohon -->
        <div class="col-xl-9 col-lg-8 mb-4">

            <!-- Card Form Tambah Pohon -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Data Pohon
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('petanipohon/store') ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="user_id" value="<?= $petani['user_id'] ?>">

                        <div class="form-group mb-3">
                            <label for="jenis_pohon_id" class="form-label font-weight-bold">Jenis Pohon</label>
                            <select id="jenis_pohon_id" name="jenis_pohon_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Jenis Pohon --</option>
                                <?php foreach ($jenisPohon as $jp): ?>
                                    <option value="<?= $jp['id'] ?>"><?= esc($jp['nama_jenis']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Mohon pilih jenis pohon.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="luas_lahan" class="form-label font-weight-bold">Luas Lahan (m²)</label>
                                    <input type="number" step="0.01" id="luas_lahan" name="luas_lahan" class="form-control" placeholder="Contoh: 150.5" required>
                                    <div class="invalid-feedback">Mohon isi luas lahan.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jumlah_pohon" class="form-label font-weight-bold">Jumlah Pohon</label>
                                    <input type="number" id="jumlah_pohon" name="jumlah_pohon" class="form-control" placeholder="Contoh: 50" required>
                                    <div class="invalid-feedback">Mohon isi jumlah pohon.</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save mr-2"></i>Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Tabel Daftar Pohon -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Daftar Pohon Milik Petani
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Jenis Pohon</th>
                                    <th>Luas Lahan (m²)</th>
                                    <th>Jumlah Pohon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($detailPohon)): ?>
                                    <?php foreach ($detailPohon as $i => $row): ?>
                                        <tr class="text-center">
                                            <td><?= $i + 1 ?></td>
                                            <td><?= esc($row['nama_jenis']) ?></td>
                                            <td><?= esc(number_format($row['luas_lahan'], 2, ',', '.')) ?></td>
                                            <td><?= esc(number_format($row['jumlah_pohon'], 0, ',', '.')) ?></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm btn-delete-pohon"
                                                    data-id="<?= esc($row['id']) ?>"
                                                    data-nama="<?= esc($row['nama_jenis']) ?>"
                                                    data-toggle="modal" data-target="#modalHapusPohon">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <em>Belum ada data pohon yang ditambahkan.</em>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Hapus Pohon -->
            <div class="modal fade" id="modalHapusPohon" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form id="formHapusPohon" method="post" action="<?= site_url('petanipohon/delete') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" id="hapusPohonId">
                        <input type="hidden" name="user_id" id="hapusPohonUserId">
                        <div class="modal-content shadow">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Konfirmasi Hapus Data Pohon</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin menghapus data pohon jenis
                                <strong id="hapusPohonNama"></strong>?
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
    </div>
</div>
<script>
    $(function() {
        // Event klik tombol hapus pohon
        $('.btn-delete-pohon').click(function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#hapusPohonId').val(id);
            $('#hapusPohonNama').text(nama);
            $('#modalHapusPohon').modal('show');
        });
    });
</script>
<?= $this->endSection() ?>