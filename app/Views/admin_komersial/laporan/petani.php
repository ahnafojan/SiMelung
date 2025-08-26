<?php
// Hapus parameter paginasi lama agar tidak menumpuk di URL
$queryParams = $_GET;
unset($queryParams['page_masuk'], $queryParams['page_keluar'], $queryParams['page_stok'], $queryParams['page_petani']);
?>
<div class="card shadow border-0 mb-4 animated--grow-in">
    <div class="card-header border-0 py-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center bg-white">
        <h6 class="m-0 font-weight-bold text-warning mb-2 mb-sm-0">
            <i class="fas fa-user-friends fa-fw mr-2"></i> Laporan Petani Terdaftar
        </h6>
        <div class="d-flex flex-column flex-sm-row mt-2 mt-sm-0">
            <?php
            $currentFilterParams = http_build_query($filter);
            ?>
            <a href="<?= site_url('admin-komersial/export/petani/excel?' . $currentFilterParams) ?>" class="btn btn-success rounded-pill mb-2 mb-sm-0 mr-sm-2">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
            <a href="<?= site_url('admin-komersial/export/petani/pdf?' . $currentFilterParams) ?>" class="btn btn-danger rounded-pill">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-start mb-3">
            <form action="<?= current_url() ?>" method="GET" class="form-inline">
                <?php foreach ($queryParams as $key => $val): ?>
                    <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($val) ?>">
                <?php endforeach; ?>
                <label class="text-muted fw-bold mr-2">Tampilkan</label>
                <select name="per_page_petani" class="form-control d-inline-block w-auto form-control-sm rounded-pill" onchange="this.form.submit()">
                    <option value="10" <?= ($perPage == 10) ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($perPage == 100) ? 'selected' : '' ?>>100</option>
                </select>
                <span class="ml-2">entri</span>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTablePetani" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Jenis Kopi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rekapPetaniTerdaftar)) : ?>
                        <?php
                        $page = (int) (service('request')->getGet('page_petani') ?? 1);
                        $perPageVal = $petaniPager->getPerPage('petani');
                        $no = 1 + (($page - 1) * $perPageVal);
                        ?>
                        <?php foreach ($rekapPetaniTerdaftar as $petani) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="font-weight-bold text-primary"><?= esc($petani['nama']) ?></td>
                                <td><?= esc($petani['alamat']) ?></td>
                                <td><?= esc($petani['no_hp']) ?></td>
                                <td><?= esc($petani['jenis_kopi']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="alert alert-info mt-3" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i> Tidak ada data petani yang terdaftar.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($rekapPetaniTerdaftar) && isset($petaniPager)): ?>
            <div class="d-flex justify-content-end mt-3">
                <?= $petaniPager->links('petani', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>
</div>