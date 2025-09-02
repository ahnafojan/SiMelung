<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?php if (session()->getFlashdata('success')): ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Pengaturan Tanda Tangan Laporan</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('/pengaturan/komersial/update'); ?>" method="post">
                <?= csrf_field(); ?>

                <div class="mb-3">
                    <label for="lokasi_komersial" class="form-label">Lokasi Laporan</label>
                    <input type="text" class="form-control" id="lokasi_komersial" name="lokasi_komersial" value="<?= esc($pengaturan['lokasi_komersial'] ?? ''); ?>" placeholder="Contoh: Melung">
                </div>

                <div class="mb-3">
                    <label for="ketua_komersial" class="form-label">
                        Nama Ketua BUMDES
                    </label>
                    <input type="text" class="form-control" id="ketua_komersial" name="ketua_komersial" value="<?= esc($pengaturan['ketua_komersial'] ?? ''); ?>" placeholder="Contoh: Sarif">
                </div>


                <div class="mb-3">
                    <label for="jabatan_kanan_komersial" class="form-label">
                        Jabatan
                    </label>
                    <input type="text" class="form-control" id="jabatan_kanan_komersial" name="jabatan_kanan_komersial" value="<?= esc($pengaturan['jabatan_kanan_komersial'] ?? 'Admin Komersial'); ?>" placeholder="Contoh: Admin Komersial">
                </div>

                <div class="mb-3">
                    <label for="nama_kanan_komersial" class="form-label">
                        Nama Admin Komersial
                    </label>
                    <input type="text" class="form-control" id="nama_kanan_komersial" name="nama_kanan_komersial" value="<?= esc($pengaturan['nama_kanan_komersial'] ?? ''); ?>" placeholder="Contoh: Budi Santoso">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>