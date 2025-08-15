<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Laporan Rekap Kopi</h1>

<!-- Filter Laporan -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin-komersial/laporan') ?>" method="get" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" value="<?= esc($filter['start_date']) ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" value="<?= esc($filter['end_date']) ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="petani" class="form-label">Petani</label>
                <select id="petani" name="petani" class="form-control">
                    <option value="">-- Semua Petani --</option>
                    <?php foreach ($petaniList as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ($filter['petani'] == $p['id']) ? 'selected' : '' ?>>
                            <?= esc($p['nama_petani']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="d-flex w-100">
                    <button type="submit" class="btn btn-primary mr-2" style="margin-right: 8px;">Tampilkan</button>
                    <a href="<?= base_url('admin-komersial/laporan') ?>" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Laporan Rekap -->
<div class="card shadow">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Rekap Data Kopi</h6>
        <a href="<?= base_url('admin-komersial/laporan/export?' . http_build_query($filter)) ?>"
            class="btn btn-success me-2">Export Excel</a>

        <a href="<?= base_url('admin-komersial/laporan/export-pdf?' . http_build_query($filter)) ?>"
            class="btn btn-danger">Export PDF</a>

    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Petani</th>
                    <th>Total Masuk (Kg)</th>
                    <th>Tanggal Masuk Terakhir</th>
                    <th>Jumlah Transaksi</th>
                    <th>Stok Akhir (Kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rekap)): ?>
                    <?php $no = 1 + ((($_GET['page'] ?? 1) - 1) * 10); ?>
                    <?php foreach ($rekap as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($r['nama_petani']) ?></td>
                            <td><?= number_format($r['total_masuk'], 2) ?></td>
                            <td><?= esc($r['tanggal_terakhir']) ?></td>
                            <td><?= $r['jumlah_transaksi'] ?></td>
                            <td><?= number_format($r['stok_akhir'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data untuk filter yang dipilih</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total Keluar (Global)</th>
                    <th><?= number_format($totalKeluarGlobal, 2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


<!-- LAPORAN PETANI TERDAFTAR -->
<h1 class="h3 mb-4 text-gray-800">Laporan Petani Terdaftar</h1>
<div class="card shadow mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Petani</h6>
        <div>
            <button class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Export Excel</button>
            <button class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> Export PDF</button>
        </div>
    </div>
    <div class="card-body table-responsive">
        <div class="mb-3">
            <label>Tampilkan</label>
            <select class="form-control d-inline-block w-auto">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
            <span>data per halaman</span>
        </div>
        <table class="table table-bordered table-striped text-center">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Pak Ahmad</td>
                    <td>Desa Melung</td>
                    <td>081234567890</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Bu Sari</td>
                    <td>Pagubugan</td>
                    <td>085612345678</td>
                </tr>
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link">Previous</a></li>
                <li class="page-item active"><a class="page-link">1</a></li>
                <li class="page-item"><a class="page-link">2</a></li>
                <li class="page-item"><a class="page-link">Next</a></li>
            </ul>
        </nav>
    </div>
</div>

<!-- LAPORAN ASET PRODUKSI -->
<h1 class="h3 mb-4 text-gray-800">Laporan Aset Produksi</h1>
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Aset</h6>
        <div>
            <button class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Export Excel</button>
            <button class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> Export PDF</button>
        </div>
    </div>
    <div class="card-body table-responsive">
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Filter Tahun Perolehan</label>
                <select class="form-control">
                    <option>Semua Tahun</option>
                    <option>2025</option>
                    <option>2024</option>
                    <option>2023</option>
                    <option>2022</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Tampilkan</label>
                <select class="form-control">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
            </div>
        </div>
        <table class="table table-bordered table-striped text-center">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Barang / Aset</th>
                    <th>Kode Aset</th>
                    <th>Nomor Urut Pendaftaran (NUP)</th>
                    <th>Tahun Perolehan</th>
                    <th>Merk / Tipe</th>
                    <th>Nilai Perolehan (Rp)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Mesin Penggiling Kopi</td>
                    <td>AST-001</td>
                    <td>001</td>
                    <td>2023</td>
                    <td>Philips X100</td>
                    <td>15.000.000</td>
                    <td>Baik</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Timbangan Digital</td>
                    <td>AST-002</td>
                    <td>002</td>
                    <td>2024</td>
                    <td>Tanita 5kg</td>
                    <td>2.500.000</td>
                    <td>Baik</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Mesin Sangrai</td>
                    <td>AST-003</td>
                    <td>003</td>
                    <td>2022</td>
                    <td>Hario SR50</td>
                    <td>20.000.000</td>
                    <td>Sangat Baik</td>
                </tr>
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link">Previous</a></li>
                <li class="page-item active"><a class="page-link">1</a></li>
                <li class="page-item"><a class="page-link">2</a></li>
                <li class="page-item"><a class="page-link">Next</a></li>
            </ul>
        </nav>
    </div>
</div>

<?= $this->endSection() ?>