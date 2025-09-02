<!DOCTYPE html>
<html>

<head>
    <title>Tambah Aset Pariwisata</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">
    <div class="container">
        <h3>Tambah Aset Pariwisata</h3>

        <?php if (session()->getFlashdata('validation')): ?>
            <div class="alert alert-warning">
                <?= session()->getFlashdata('validation')->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('asetpariwisata/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label>Nama Pariwisata</label>
                <input type="text" name="nama_pariwisata" class="form-control" value="<?= old('nama_pariwisata') ?>" required>
            </div>
            <div class="mb-3">
                <label>Nama Aset</label>
                <input type="text" name="nama_aset" class="form-control" value="<?= old('nama_aset') ?>" required>
            </div>
            <div class="mb-3">
                <label>Kode Aset</label>
                <input type="text" name="kode_aset" class="form-control" value="<?= old('kode_aset') ?>" required>
            </div>
            <div class="mb-3">
                <label>NUP</label>
                <input type="text" name="nup" class="form-control" value="<?= old('nup') ?>" required>
            </div>
            <div class="mb-3">
                <label>Tahun Perolehan</label>
                <input type="number" name="tahun_perolehan" class="form-control" value="<?= old('tahun_perolehan') ?>" required>
            </div>
            <div class="mb-3">
                <label>Nilai Perolehan</label>
                <input type="number" name="nilai_perolehan" class="form-control" value="<?= old('nilai_perolehan') ?>" required>
            </div>
            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" required><?= old('keterangan') ?></textarea>
            </div>
            <div class="mb-3">
                <label>Metode Pengadaan</label>
                <input type="text" name="metode_pengadaan" class="form-control" value="<?= old('metode_pengadaan') ?>" required>
            </div>
            <div class="mb-3">
                <label>Sumber Pengadaan</label>
                <input type="text" name="sumber_pengadaan" class="form-control" value="<?= old('sumber_pengadaan') ?>" required>
            </div>
            <div class="mb-3">
                <label>Upload Foto Aset</label>
                <input type="file" name="foto_aset" class="form-control" accept="image/*" required>
            </div>

            <!-- Tombol diubah warna -->
            <button type="submit" class="btn btn-success">Simpan Data Aset</button>
            <a href="<?= base_url('asetpariwisata') ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

</html>