<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Edit Data Pariwisata</h2>

    <form action="<?= base_url('admin_pariwisata/update/' . $pariwisata['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Pariwisata</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= esc($pariwisata['nama']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= esc($pariwisata['lokasi']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= esc($pariwisata['deskripsi']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Lama</label><br>
            <?php if (!empty($pariwisata['gambar'])): ?>
                <img src="<?= base_url('uploads/' . $pariwisata['gambar']) ?>" alt="Gambar Pariwisata" width="150" class="img-thumbnail">
            <?php else: ?>
                <p><i>Tidak ada gambar</i></p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Ganti Gambar (Opsional)</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="<?= base_url('admin_pariwisata') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>
