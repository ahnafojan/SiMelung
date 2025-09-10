<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title"><?= esc($title) ?></h1>
            <p class="mb-0 page-subtitle">Silahkan pilih Tahun untuk melihat Perubahan Modal Desa Melung.</p>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('LaporanModal') ?>" method="get" class="row align-items-end">
                <div class="col-md-3">
                    <label for="tahun" class="form-label">Tahun Periode</label>
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Pilih Tahun</option>
                        <?php if (!empty($daftar_tahun)): ?>
                            <?php foreach ($daftar_tahun as $item): ?>
                                <option value="<?= esc($item['tahun']) ?>" <?= ($item['tahun'] == $tahun_terpilih) ? 'selected' : '' ?>>
                                    <?= esc($item['tahun']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-eye fa-sm mr-2"></i>Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Periode Tahun: <?= esc($tahun_terpilih) ?></h5>
        </div>

        <div class="card-body p-sm-4">

            <div class="mb-4">
                <h6 class="font-weight-bold text-primary border-bottom pb-2 mb-3">Penambahan</h6>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Laba/Rugi Bersih</span>
                    <span class="font-weight-bold text-dark">Rp <?= number_format($laba_rugi_bersih, 0, ',', '.') ?></span>
                </div>
                <?php foreach ($komponen as $item): if ($item['kategori'] == 'penambahan'): ?>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span><?= esc($item['nama_komponen']) ?></span>
                            <span class="font-weight-bold text-dark">Rp <?= number_format($detail_map[$item['id']] ?? 0, 0, ',', '.') ?></span>
                        </div>
                <?php endif;
                endforeach; ?>
            </div>

            <div class="mb-4">
                <h6 class="font-weight-bold text-danger border-bottom pb-2 mb-3">Pengurangan</h6>
                <?php foreach ($komponen as $item): if ($item['kategori'] == 'pengurangan'): ?>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span><?= esc($item['nama_komponen']) ?></span>
                            <span class="font-weight-bold text-dark">Rp <?= number_format($detail_map[$item['id']] ?? 0, 0, ',', '.') ?></span>
                        </div>
                <?php endif;
                endforeach; ?>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <span class="font-weight-bold" style="font-size: 1.1rem;">Modal Akhir (per 31 Desember <?= esc($tahun_terpilih) ?>)</span>
                <span class="font-weight-bold text-success" style="font-size: 1.25rem;">
                    Rp <?= number_format($modal_akhir, 0, ',', '.') ?>
                </span>
            </div>
        </div>
    </div>

    <?= $this->endSection(); ?>