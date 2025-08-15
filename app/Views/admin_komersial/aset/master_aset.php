<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h4 class="mb-4">Form Master Aset</h4>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <div class="card shadow">
        <div class="card-body">
            <form action="<?= base_url('aset-komersial') ?>" method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang / Aset</label>
                        <input type="text" name="nama_aset" class="form-control" placeholder="Contoh: Mesin Penggiling Kopi" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kode Aset</label>
                        <input type="text" name="kode_aset" class="form-control" placeholder="Contoh: AST-001" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nomor Urut Pendaftaran (NUP)</label>
                        <input type="text" name="nup" class="form-control" placeholder="Contoh: 001">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan" class="form-control" placeholder="2024">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Merk / Tipe</label>
                        <input type="text" name="merk_type" class="form-control" placeholder="Contoh: Philips X100">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nilai Perolehan (Rp)</label>
                        <input type="number" name="nilai_perolehan" class="form-control" placeholder="Contoh: 15000000">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Kondisi Baik">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>