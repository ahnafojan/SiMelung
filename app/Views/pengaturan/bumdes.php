<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Pengaturan export laporan BUMDES</h1>
            <p class="mb-0 page-subtitle">Fungsi: Membuat template pengaturan export BUMDES.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Pengaturan Laporan BUMDES</h6>
        </div>
        <div class="card-body">

            <form action="<?= site_url('/pengaturan/bumdes/update'); ?>" method="post">
                <?= csrf_field(); ?>

                <div class="mb-3">
                    <label for="lokasi_laporan" class="form-label">Lokasi Laporan</label>
                    <input type="text" class="form-control" id="lokasi_laporan" name="lokasi_laporan" value="<?= esc($pengaturan['lokasi_laporan'] ?? ''); ?>" placeholder="Contoh: Melung">

                </div>

                <div class="mb-3">
                    <label for="ketua_bumdes" class="form-label">Nama Ketua BUMDES</label>
                    <input type="text" class="form-control" id="ketua_bumdes" name="ketua_bumdes" value="<?= esc($pengaturan['ketua_bumdes'] ?? ''); ?>" placeholder="Contoh: KARTIM">
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>