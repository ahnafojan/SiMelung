<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid mt-4">
    <h3 class="mb-3">Manajemen Pariwisata</h3>
    <p>Mencatat data pariwisata desa untuk mendukung kegiatan wisata.</p>

    <form action="<?= base_url('pariwisata/save') ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <!-- Informasi Dasar Pariwisata -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-info-circle"></i> Informasi Dasar Pariwisata</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pariwisata</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" id="lokasi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
