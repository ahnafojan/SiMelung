<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content'); ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title"><?= esc($title) ?></h1>
            <p class="mb-0 page-subtitle">Silakan pilih tahun untuk menampilkan Laporan Laba Rugi Desa Melung.</p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter fa-fw mr-2"></i>Pilih Periode Laporan</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('LaporanLabaRugi'); ?>" method="get">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="tahun" class="form-label font-weight-bold text-gray-700">Pilih Tahun:</label>
                        <select name="tahun" id="tahun" class="form-control" required>
                            <option value="">-- Pilih Tahun --</option>
                            <?php foreach ($daftar_tahun as $th) : ?>
                                <option value="<?= $th['tahun']; ?>" <?= (isset($tahunDipilih) && $tahunDipilih == $th['tahun']) ? 'selected' : ''; ?>>
                                    <?= $th['tahun']; ?>
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

    <?php if (isset($tahunDipilih)) : ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-invoice-dollar fa-fw mr-2"></i>Laporan Laba Rugi Tahun <?= esc($tahunDipilih); ?>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-4">
                    <h6 class="text-uppercase text-primary font-weight-bold small mb-3 border-bottom pb-2">Pendapatan</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-gray-700">Pendapatan Usaha (dari BKU)</span>
                            <span class="font-weight-bold text-dark"><?= number_to_currency($pendapatanUsaha, 'IDR', 'id_ID', 2); ?></span>
                        </li>
                        <?php foreach ($komponenPendapatan as $item) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                <span class="font-weight-bold text-dark"><?= number_to_currency($item['jumlah'], 'IDR', 'id_ID', 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-light">
                            <span class="font-weight-bold text-primary">TOTAL PENDAPATAN</span>
                            <span class="font-weight-bold text-primary"><?= number_to_currency($totalPendapatan, 'IDR', 'id_ID', 2); ?></span>
                        </li>
                    </ul>
                </div>

                <hr class="m-0">

                <div class="p-4">
                    <h6 class="text-uppercase text-danger font-weight-bold small mb-3 border-bottom pb-2">Biaya-Biaya</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-gray-700">Biaya Bahan Baku (Pengembangan)</span>
                            <span class="font-weight-bold text-dark"><?= number_to_currency($biayaBahanBaku, 'IDR', 'id_ID', 2); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-gray-700">Biaya Gaji (Honor)</span>
                            <span class="font-weight-bold text-dark"><?= number_to_currency($biayaGaji, 'IDR', 'id_ID', 2); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-gray-700">Pendapatan Asli Desa (PAD)</span>
                            <span class="font-weight-bold text-dark"><?= number_to_currency($pad, 'IDR', 'id_ID', 2); ?></span>
                        </li>
                        <?php foreach ($komponenBiaya as $item) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-gray-700"><?= esc($item['nama_komponen']); ?></span>
                                <span class="font-weight-bold text-dark"><?= number_to_currency($item['jumlah'], 'IDR', 'id_ID', 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-light">
                            <span class="font-weight-bold text-danger">TOTAL BIAYA</span>
                            <span class="font-weight-bold text-danger"><?= number_to_currency($totalBiaya, 'IDR', 'id_ID', 2); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
            $labaRugiBersih = $totalPendapatan - $totalBiaya;
            $footerClass = ($labaRugiBersih >= 0) ? 'bg-success text-white' : 'bg-danger text-white';
            $footerIcon = ($labaRugiBersih >= 0) ? 'fa-arrow-up' : 'fa-arrow-down';
            $footerText = ($labaRugiBersih >= 0) ? 'LABA BERSIH' : 'RUGI BERSIH';
            ?>
            <div class="card-footer <?= $footerClass; ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold">
                        <i class="fas <?= $footerIcon; ?> mr-2"></i><?= $footerText; ?>
                    </h5>
                    <h5 class="m-0 font-weight-bold"><?= number_to_currency($labaRugiBersih, 'IDR', 'id_ID', 2); ?></h5>
                </div>
            </div>
        </div>

    <?php else : ?>
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-info-circle text-primary fa-3x mb-3"></i>
                <h5 class="text-gray-700">Silakan pilih tahun pada form di atas.</h5>
                <p class="text-gray-600 mb-0">Data laporan laba rugi akan ditampilkan setelah Anda memilih periode.</p>
            </div>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection(); ?>