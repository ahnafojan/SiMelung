<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4 bg-light min-vh-100">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 text-dark font-weight-bolder">Dashboard Kepala Desa</h1>
            <p class="text-secondary medium">Ringkasan Operasional Data Komersial, Pariwisata, dan Umkm Desa Melung.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-lg">
        <div class="card-body py-3">
            <form method="get" action="<?= base_url('dashboard/dashboard_desa') ?>">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="bulan" class="form-label text-muted small mb-1">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control form-control-sm rounded-pill">
                            <?php
                            $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                            foreach ($namaBulan as $num => $nama): ?>
                                <option value="<?= $num ?>" <?= ($bulan == $num) ? 'selected' : '' ?>>
                                    <?= $nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun" class="form-label text-muted small mb-1">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control form-control-sm rounded-pill">
                            <?php foreach ($years as $y): ?>
                                <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill shadow-sm mt-3 mt-md-0">
                            <i class="fas fa-filter me-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kopi Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($totalMasuk ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-box fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Kopi Keluar</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($totalKeluar ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-truck-loading fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Stok Bersih</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= number_format($stokBersih ?? 0, 0, ',', '.') ?> <span class="text-muted small">Kg</span></div>
                        </div>
                        <i class="fas fa-balance-scale fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Petani Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalPetani ?? 0 ?> <span class="text-muted small">Orang</span></div>
                        </div>
                        <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Aset Komersial</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalAset ?? 0 ?> <span class="text-muted small">Unit</span></div>
                        </div>
                        <i class="fas fa-cubes fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Objek Wisata</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalObjekWisata ?? 0 ?> <span class="text-muted small">Lokasi</span></div>
                        </div>
                        <i class="fas fa-map-marked-alt fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Aset Pariwisata</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalAsetPariwisata ?? 0 ?> <span class="text-muted small">Unit</span></div>
                        </div>
                        <i class="fas fa-umbrella-beach fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3 card-hover-light rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Nilai Aset Pariwisata</div>
                            <div class="h5 mb-0 font-weight-bold text-dark">Rp <?= number_format($totalNilaiAsetPariwisata ?? 0, 0, ',', '.') ?></div>
                        </div>
                        <i class="fas fa-wallet fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100 rounded-lg">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Tren Kopi Masuk & Keluar</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height:300px;">
                        <canvas id="kopiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100 rounded-lg">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi per Jenis Kopi</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="chart-pie pt-4 pb-2" style="position: relative; height:250px;">
                        <canvas id="jenisKopiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card shadow-sm border-0 rounded-lg mt-4">
        <div class="card-header bg-white py-3 border-0 rounded-top-lg">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Aset Pariwisata Desa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Nama Aset</th>
                            <th>Lokasi Wisata</th>
                            <th>Kode Aset</th>
                            <th>Tahun</th>
                            <th>Nilai (Rp)</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($asetsPariwisata)) : ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data aset pariwisata yang terdaftar.</td>
                            </tr>
                        <?php else : ?>
                            <?php
                            // Menggunakan variabel yang sudah dikirim dari simple controller
                            $no = (($currentPage - 1) * $perPage) + 1;
                            ?>
                            <?php foreach ($asetsPariwisata as $aset) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= esc($aset['nama_aset']) ?></td>
                                    <td>
                                        <span class="badge bg-info text-white">
                                            <?= esc($aset['nama_wisata'] ?? 'Tidak Terkait') ?>
                                        </span>
                                    </td>
                                    <td><?= esc($aset['kode_aset']) ?></td>
                                    <td><?= esc($aset['tahun_perolehan']) ?></td>
                                    <td class="text-end">Rp <?= number_format($aset['nilai_perolehan'], 0, ',', '.') ?></td>
                                    <td><?= esc($aset['keterangan']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination yang disesuaikan dengan simple controller -->
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <!-- Info data -->
                <div class="small text-muted">
                    <?php if (!empty($asetsPariwisata)) : ?>
                        <?php
                        $startData = (($currentPage - 1) * $perPage) + 1;
                        $endData = min($currentPage * $perPage, $totalData);
                        ?>
                        Menampilkan <?= $startData ?> sampai <?= $endData ?> dari <?= $totalData ?> data
                    <?php else: ?>
                        Tidak ada data untuk ditampilkan
                    <?php endif; ?>
                </div>

                <!-- Pagination links -->
                <div>
                    <?php if ($totalPages > 1) : ?>
                        <nav aria-label="Pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <!-- Previous -->
                                <?php if ($currentPage > 1) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= http_build_query(array_merge(service('request')->getGet(), ['page_pariwisata' => $currentPage - 1])) ?>">
                                            <i class="fas fa-chevron-left"></i> Previous
                                        </a>
                                    </li>
                                <?php else : ?>
                                    <li class="page-item disabled">
                                        <span class="page-link text-muted">
                                            <i class="fas fa-chevron-left"></i> Previous
                                        </span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers dengan logic untuk banyak halaman -->
                                <?php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);
                                ?>

                                <!-- First page jika tidak terlihat -->
                                <?php if ($startPage > 1) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= http_build_query(array_merge(service('request')->getGet(), ['page_pariwisata' => 1])) ?>">1</a>
                                    </li>
                                    <?php if ($startPage > 2) : ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- Page numbers -->
                                <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                        <a class="page-link" href="?<?= http_build_query(array_merge(service('request')->getGet(), ['page_pariwisata' => $i])) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Last page jika tidak terlihat -->
                                <?php if ($endPage < $totalPages) : ?>
                                    <?php if ($endPage < $totalPages - 1) : ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= http_build_query(array_merge(service('request')->getGet(), ['page_pariwisata' => $totalPages])) ?>">
                                            <?= $totalPages ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Next -->
                                <?php if ($currentPage < $totalPages) : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= http_build_query(array_merge(service('request')->getGet(), ['page_pariwisata' => $currentPage + 1])) ?>">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php else : ?>
                                    <li class="page-item disabled">
                                        <span class="page-link text-muted">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>