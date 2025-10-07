<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>

    <!-- Breadcrumbs -->
    <?php if (!empty($breadcrumbs)): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $crumb): ?>
                    <li class="breadcrumb-item <?= $crumb['url'] == '#' ? 'active' : '' ?>" <?= $crumb['url'] == '#' ? 'aria-current="page"' : '' ?>>
                        <?php if ($crumb['url'] != '#'): ?>
                            <a href="<?= esc($crumb['url']) ?>">
                                <i class="<?= esc($crumb['icon']) ?>"></i> <?= esc($crumb['title']) ?>
                            </a>
                        <?php else: ?>
                            <i class="<?= esc($crumb['icon']) ?>"></i> <?= esc($crumb['title']) ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>
    <?php endif; ?>

    <!-- Tombol Export -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Opsi Export Data UMKM</h6>
        </div>
        <div class="card-body">
            <p>Silakan gunakan tombol di bawah ini untuk mengeksport seluruh data UMKM yang terdaftar di sistem:</p>

            <a href="<?= site_url('laporanumkm/exportExcel') ?>" class="btn btn-success btn-lg mb-2">
                <i class="fas fa-file-excel"></i> Export ke Excel (.xlsx)
            </a>
            <a href="<?= site_url('laporanumkm/exportPDF') ?>" class="btn btn-danger btn-lg mb-2">
                <i class="fas fa-file-pdf"></i> Export ke PDF (.pdf)
            </a>
        </div>
    </div>

    <!-- Bagian Preview Data (Sudah direvisi untuk menampilkan data real) -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview Data UMKM</h6>
        </div>
        <div class="card-body">
            <p class="small text-muted">Berikut adalah daftar data UMKM yang akan di-export:</p>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama UMKM</th>
                            <th>Pemilik</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($umkmData)): // DIREVISI: Menggunakan $umkmData 
                        ?>
                            <tr>
                                <td colspan="5" class="text-center text-danger">Tidak ada data UMKM ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach ($umkmData as $data): // DIREVISI: Menggunakan $umkmData 
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= esc($data['nama_umkm']) ?></td>
                                    <td><?= esc($data['pemilik']) ?></td>
                                    <td><?= esc($data['alamat']) ?></td>
                                    <td><?= esc($data['kontak']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>