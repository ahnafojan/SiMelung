<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <h3>Tambah Petani</h3>
    <form action="<?= site_url('petani/store') ?>" method="post">
        <div class="form-group">
            <label>User ID</label>
            <input type="number" name="user_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
<?= $this->endSection() ?>