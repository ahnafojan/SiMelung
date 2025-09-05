<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Pengaturan Laporan Pokdarwis</h6>
        </div>
        <div class="card-body">

            <form action="<?= site_url('/pengaturan/pariwisata/update'); ?>" method="post">
                <?= csrf_field(); ?>

                <div class="mb-3">
                    <label for="lokasi_laporan" class="form-label">Lokasi Laporan</label>
                    <input type="text" class="form-control" id="lokasi_laporan" name="lokasi_laporan" value="<?= esc($pengaturan['lokasi_laporan'] ?? ''); ?>" placeholder="Contoh: Melung">
                </div>

                <div class="mb-3">
                    <label for="nama_pokdarwis" class="form-label">Nama Kelompok (POKDARWIS)</label>
                    <input type="text" class="form-control" id="nama_pokdarwis" name="nama_pokdarwis" value="<?= esc($pengaturan['nama_pokdarwis'] ?? ''); ?>" placeholder="Contoh: PAGUBUGAN">
                </div>

                <div class="mb-3">
                    <label for="ketua_pokdarwis" class="form-label">Nama Ketua Pokdarwis</label>
                    <!-- DIUBAH: name, id, dan value disesuaikan menjadi 'ketua_pokdarwis' -->
                    <input type="text" class="form-control" id="ketua_pokdarwis" name="ketua_pokdarwis" value="<?= esc($pengaturan['ketua_pokdarwis'] ?? ''); ?>" placeholder="Contoh: Timbul Yulianto">
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>