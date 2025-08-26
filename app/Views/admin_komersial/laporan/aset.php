<div class="card shadow border-0 animated--grow-in mb-4">
    <div class="card-header border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center bg-white">
        <h6 class="m-0 font-weight-bold text-warning mb-2 mb-sm-0">
            <i class="fas fa-cogs mr-1"></i> Laporan Aset Produksi
        </h6>
        <div class="d-flex flex-column flex-sm-row mt-2 mt-sm-0">
            <?php
            // Siapkan filter untuk URL export agar tahun yang dipilih ikut terbawa
            $exportFilterAset = ['tahun_aset' => $filterTahun];
            ?>
            <a href="<?= base_url('admin-komersial/export/aset/excel?' . http_build_query($exportFilterAset)) ?>" class="btn btn-success btn-sm mb-2 mb-sm-0 mr-sm-2 shadow-sm rounded-pill">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
            <a href="<?= base_url('admin-komersial/export/aset/pdf?' . http_build_query($exportFilterAset)) ?>" class="btn btn-danger btn-sm shadow-sm rounded-pill">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= current_url() ?>" method="get" id="filterAsetForm">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 mb-2">
                    <label for="tahun_aset" class="text-muted">Filter Tahun Perolehan</label>
                    <select name="tahun_aset" id="tahun_aset" class="form-control form-control-sm rounded-pill" onchange="this.form.submit()">
                        <option value="semua" <?= ($filterTahun == 'semua') ? 'selected' : '' ?>>Semua Tahun</option>
                        <?php foreach ($daftarTahun as $th): ?>
                            <option value="<?= $th['tahun_perolehan'] ?>" <?= ($filterTahun == $th['tahun_perolehan']) ? 'selected' : '' ?>>
                                <?= esc($th['tahun_perolehan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="per_page_aset" class="text-muted">Tampilkan</label>
                    <select name="per_page_aset" id="per_page_aset" class="form-control form-control-sm rounded-pill" onchange="this.form.submit()">
                        <option value="10" <?= ($perPageAset == 10) ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($perPageAset == 25) ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($perPageAset == 50) ? 'selected' : '' ?>>50</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang / Aset</th>
                        <th>Kode Aset</th>
                        <th>NUP</th>
                        <th>Tahun</th>
                        <th>Merk / Tipe</th>
                        <th class="text-right">Nilai Perolehan (Rp)</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($aset)): ?>
                        <?php
                        // Menghitung nomor urut berdasarkan halaman aktif
                        $no = 1 + ($pagerAset->getCurrentPage('aset') - 1) * $pagerAset->getPerPage('aset');
                        ?>
                        <?php foreach ($aset as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="text-left font-weight-bold"><?= esc($item['nama_aset']) ?></td>
                                <td><?= esc($item['kode_aset']) ?></td>
                                <td><?= esc($item['nup']) ?></td>
                                <td><?= esc($item['tahun_perolehan']) ?></td>
                                <td class="text-left"><?= esc($item['merk_type']) ?></td>
                                <td class="text-right"><?= number_format($item['nilai_perolehan'], 0, ',', '.') ?></td>
                                <td class="text-left"><?= esc($item['keterangan']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Tidak ada data aset untuk filter ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            <?php if ($pagerAset) echo $pagerAset->links('aset', 'default_full') ?>
        </div>
    </div>
</div>