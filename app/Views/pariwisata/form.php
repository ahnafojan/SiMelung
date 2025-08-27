<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid mt-4">
  <h3 class="mb-3">Tambah Pariwisata</h3>

  <form action="<?= base_url('pariwisata/save') ?>" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-6">
        <div class="card shadow-sm mb-3">
          <div class="card-header bg-light">
            <strong>Informasi Dasar Pariwisata</strong>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Nama Pariwisata *</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Lokasi *</label>
              <input type="text" name="lokasi" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="deskripsi" class="form-control" rows="4"></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm mb-3">
          <div class="card-header bg-light">
            <strong>Detail & Foto</strong>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Harga Tiket (Rp)</label>
              <input type="number" name="harga_tiket" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Fasilitas</label>
              <input type="text" name="fasilitas" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Upload Foto *</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-primary px-4">Simpan</button>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
