<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title"><?= esc($title) ?></h1>
            <p class="mb-0 page-subtitle">Silahkan pilih Tahun untuk melihat Neraca Desa Melung.</p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Pilih Periode Laporan</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('LaporanNeraca'); ?>" method="get">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="tahun">Pilih Tahun:</label>
                        <select name="tahun" id="tahun" class="form-control" required>
                            <option value="">-- Pilih Tahun --</option>
                            <?php foreach ($daftar_tahun as $item): ?>
                                <option value="<?= $item['tahun']; ?>" <?= (isset($tahunDipilih) && $tahunDipilih == $item['tahun']) ? 'selected' : ''; ?>>
                                    <?= $item['tahun']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-eye fa-sm mr-2"></i>Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($tahunDipilih)): ?>
        <div class="row d-none d-lg-flex">
            <div class="col-lg-6">
                <div class="card shadow mb-4 border-left-primary">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar mr-2"></i>AKTIVA</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold text-gray-800 mb-3">Aktiva Lancar</h6>
                        <?php foreach ($komponen['aktiva_lancar'] as $item): ?>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex justify-content-between py-2 font-weight-bold border-left-primary bg-gray-100 mt-2 px-3 rounded">
                            <span class="text-primary">JUMLAH AKTIVA LANCAR</span>
                            <span class="text-primary">Rp <?= number_format($total_aktiva_lancar, 0, ',', '.'); ?></span>
                        </div>
                        <hr class="my-4">
                        <h6 class="mt-3 font-weight-bold text-gray-800 mb-3">Aktiva Tetap</h6>
                        <?php foreach ($komponen['aktiva_tetap'] as $item): ?>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex justify-content-between py-2 font-weight-bold border-left-primary bg-gray-100 mt-2 px-3 rounded">
                            <span class="text-primary">JUMLAH AKTIVA TETAP</span>
                            <span class="text-primary">Rp <?= number_format($total_aktiva_tetap, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-calculator mr-2"></i>TOTAL AKTIVA</h6>
                        <h6 class="m-0 font-weight-bold">Rp <?= number_format($total_aktiva, 0, ',', '.'); ?></h6>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow mb-4 border-left-info">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-credit-card mr-2"></i>PASIVA</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold text-gray-800 mb-3">Hutang Lancar</h6>
                        <?php foreach ($komponen['hutang_lancar'] as $item): ?>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex justify-content-between py-2 font-weight-bold border-left-info bg-gray-100 mt-2 px-3 rounded">
                            <span class="text-info">JUMLAH HUTANG LANCAR</span>
                            <span class="text-info">Rp <?= number_format($total_hutang_lancar, 0, ',', '.'); ?></span>
                        </div>
                        <hr class="my-4">
                        <h6 class="mt-3 font-weight-bold text-gray-800 mb-3">Hutang Jangka Panjang</h6>
                        <?php foreach ($komponen['hutang_jangka_panjang'] as $item): ?>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex justify-content-between py-2 font-weight-bold border-left-info bg-gray-100 mt-2 px-3 rounded">
                            <span class="text-info">JUMLAH HUTANG JANGKA PANJANG</span>
                            <span class="text-info">Rp <?= number_format($total_hutang_jangka_panjang, 0, ',', '.'); ?></span>
                        </div>
                        <hr class="my-4">
                        <h6 class="mt-3 font-weight-bold text-gray-800 mb-3">Modal</h6>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-gray-700">Surplus/Defisit Ditahan</span>
                            <span class="text-gray-800 font-weight-bold">Rp <?= number_format($surplusDefisitDitahan, 0, ',', '.'); ?></span>
                        </div>
                        <?php foreach ($komponen['modal'] as $item): ?>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex justify-content-between py-2 font-weight-bold border-left-info bg-gray-100 mt-2 px-3 rounded">
                            <span class="text-info">JUMLAH MODAL</span>
                            <span class="text-info">Rp <?= number_format($total_modal, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-info text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-calculator mr-2"></i>TOTAL PASIVA</h6>
                        <h6 class="m-0 font-weight-bold">Rp <?= number_format($total_pasiva, 0, ',', '.'); ?></h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-lg-none">
            <ul class="nav nav-tabs nav-fill" id="neracaTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="aktiva-tab-mobile" data-toggle="tab" href="#aktiva-pane-mobile" role="tab">
                        <i class="fas fa-chart-bar mr-2"></i>AKTIVA
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pasiva-tab-mobile" data-toggle="tab" href="#pasiva-pane-mobile" role="tab">
                        <i class="fas fa-credit-card mr-2"></i>PASIVA
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="neracaTabContent">
                <div class="tab-pane fade show active" id="aktiva-pane-mobile" role="tabpanel">
                    <div class="card shadow mb-4 border-top-0 border-left-primary" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-gray-800 mb-3">Aktiva Lancar</h6>
                            <?php foreach ($komponen['aktiva_lancar'] as $item): ?>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                    <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="d-flex justify-content-between py-2 font-weight-bold border-left-primary bg-gray-100 mt-2 px-3 rounded">
                                <span class="text-primary">JUMLAH AKTIVA LANCAR</span>
                                <span class="text-primary">Rp <?= number_format($total_aktiva_lancar, 0, ',', '.'); ?></span>
                            </div>
                            <hr class="my-4">
                            <h6 class="mt-3 font-weight-bold text-gray-800 mb-3">Aktiva Tetap</h6>
                            <?php foreach ($komponen['aktiva_tetap'] as $item): ?>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                    <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="d-flex justify-content-between py-2 font-weight-bold border-left-primary bg-gray-100 mt-2 px-3 rounded">
                                <span class="text-primary">JUMLAH AKTIVA TETAP</span>
                                <span class="text-primary">Rp <?= number_format($total_aktiva_tetap, 0, ',', '.'); ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-primary text-white d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-calculator mr-2"></i>TOTAL AKTIVA</h6>
                            <h6 class="m-0 font-weight-bold">Rp <?= number_format($total_aktiva, 0, ',', '.'); ?></h6>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pasiva-pane-mobile" role="tabpanel">
                    <div class="card shadow mb-4 border-top-0 border-left-info" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-gray-800 mb-3">Hutang Lancar</h6>
                            <?php foreach ($komponen['hutang_lancar'] as $item): ?>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                    <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="d-flex justify-content-between py-2 font-weight-bold border-left-info bg-gray-100 mt-2 px-3 rounded">
                                <span class="text-info">JUMLAH HUTANG LANCAR</span>
                                <span class="text-info">Rp <?= number_format($total_hutang_lancar, 0, ',', '.'); ?></span>
                            </div>
                            <hr class="my-4">
                            <h6 class="mt-3 font-weight-bold text-gray-800 mb-3">Hutang Jangka Panjang</h6>
                            <?php foreach ($komponen['hutang_jangka_panjang'] as $item): ?>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                    <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="d-flex justify-content-between py-2 font-weight-bold border-left-info bg-gray-100 mt-2 px-3 rounded">
                                <span class="text-info">JUMLAH HUTANG JANGKA PANJANG</span>
                                <span class="text-info">Rp <?= number_format($total_hutang_jangka_panjang, 0, ',', '.'); ?></span>
                            </div>
                            <hr class="my-4">
                            <h6 class="mt-3 font-weight-bold text-gray-800 mb-3">Modal</h6>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-gray-700">Surplus/Defisit Ditahan</span>
                                <span class="text-gray-800 font-weight-bold">Rp <?= number_format($surplusDefisitDitahan, 0, ',', '.'); ?></span>
                            </div>
                            <?php foreach ($komponen['modal'] as $item): ?>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                    <span class="text-gray-800 font-weight-bold">Rp <?= number_format($item['jumlah'], 0, ',', '.'); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="d-flex justify-content-between py-2 font-weight-bold border-left-info bg-gray-100 mt-2 px-3 rounded">
                                <span class="text-info">JUMLAH MODAL</span>
                                <span class="text-info">Rp <?= number_format($total_modal, 0, ',', '.'); ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-info text-white d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-calculator mr-2"></i>TOTAL PASIVA</h6>
                            <h6 class="m-0 font-weight-bold">Rp <?= number_format($total_pasiva, 0, ',', '.'); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-body text-center py-4">
                <?php if ($total_aktiva == $total_pasiva) : ?>
                    <div class="alert alert-success border-left-success" role="alert">
                        <i class="fas fa-check-circle fa-lg mr-3"></i>
                        <strong>SEIMBANG (BALANCE)</strong>
                        <div class="text-success mt-2">
                            <small>Total Aktiva = Total Pasiva</small>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="alert alert-danger border-left-danger" role="alert">
                        <i class="fas fa-exclamation-triangle fa-lg mr-3"></i>
                        <strong>TIDAK SEIMBANG</strong>
                        <div class="text-danger mt-2">
                            <small>Selisih: Rp <?= number_format($total_aktiva - $total_pasiva, 0, ',', '.'); ?></small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection(); ?>