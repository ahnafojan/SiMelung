<?= $this->extend('admin_keuangan/layout/template'); ?>

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
            <h6 class="m-0 font-weight-bold text-primary">Formulir Pengaturan Tanda Tangan Laporan</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('/pengaturan/update') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="lokasi_laporan" class="form-label">Lokasi Laporan</label>
                    <input type="text" id="lokasi_laporan" name="lokasi_laporan" class="form-control"
                        placeholder="Contoh: Melung"
                        value="<?= esc($pengaturan['lokasi_laporan'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="nama_kepala_desa" class="form-label">Nama Kepala Desa</label>
                    <input type="text" id="nama_kepala_desa" name="nama_kepala_desa" class="form-control"
                        placeholder="Contoh: KHOERUDIN S. Sos"
                        value="<?= esc($pengaturan['nama_kepala_desa'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="nama_penasihat" class="form-label">Nama Penasihat</label>
                    <input type="text" id="nama_penasihat" name="nama_penasihat" class="form-control"
                        placeholder="Contoh: KHOERUDIN"
                        value="<?= esc($pengaturan['nama_penasihat'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="nama_pengawas" class="form-label">Nama Pengawas</label>
                    <input type="text" id="nama_pengawas" name="nama_pengawas" class="form-control"
                        placeholder="Contoh: SUDARSO"
                        value="<?= esc($pengaturan['nama_pengawas'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="ketua_bumdes" class="form-label">Nama Ketua BUMDES</label>
                    <input type="text" id="ketua_bumdes" name="ketua_bumdes" class="form-control"
                        placeholder="Contoh: KARTIM"
                        value="<?= esc($pengaturan['ketua_bumdes'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="bendahara_bumdes" class="form-label">Nama Bendahara BUMDES</label>
                    <input type="text" id="bendahara_bumdes" name="bendahara_bumdes" class="form-control"
                        placeholder="Contoh: RUSTIANI"
                        value="<?= esc($pengaturan['bendahara_bumdes'] ?? '') ?>">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>