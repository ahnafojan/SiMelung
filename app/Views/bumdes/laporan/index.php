<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Laporan Rekap Kopi</h1>

<!-- Daftar Petani Terdaftar -->
<div class="card shadow mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-success">Daftar Petani Terdaftar</h6>
        <div>
            <button class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Petani</th>
                        <th>Alamat</th>
                        <th>Jenis Komoditas</th>
                        <th>Luas Lahan (Ha)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Pak Ahmad</td>
                        <td>RT 01/RW 01</td>
                        <td>Kopi Robusta</td>
                        <td>2.5</td>
                        <td><span class="badge badge-success">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Bu Sari</td>
                        <td>RT 02/RW 01</td>
                        <td>Kopi Arabika</td>
                        <td>1.8</td>
                        <td><span class="badge badge-success">Aktif</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Daftar Aset Pariwisata Terdaftar -->
<div class="card shadow mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-info">Daftar Aset Pariwisata Terdaftar</h6>
        <div>
            <button class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Aset</th>
                        <th>Lokasi</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Gazebo Wisata</td>
                        <td>Curug Song</td>
                        <td>5 unit</td>
                        <td>Baik</td>
                        <td>Digunakan untuk istirahat pengunjung</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Perahu Wisata</td>
                        <td>Embung Melung</td>
                        <td>3 unit</td>
                        <td>Cukup</td>
                        <td>Perlu perawatan berkala</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tabel Rekap Kopi -->
<div class="card shadow mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Rekap Data Kopi</h6>
        <div>
            <button class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Petani</th>
                        <th>Kopi Masuk (kg)</th>
                        <th>Kopi Keluar (kg)</th>
                        <th>Stok Akhir (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Pak Ahmad</td>
                        <td class="text-center">120</td>
                        <td class="text-center">30</td>
                        <td class="text-center">90</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Bu Sari</td>
                        <td class="text-center">80</td>
                        <td class="text-center">50</td>
                        <td class="text-center">30</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Judul UMKM -->
<h1 class="h3 mb-4 text-gray-800">Laporan UMKM Desa</h1>

<!-- Filter UMKM -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Filter UMKM</h6>
        <div>
            <button class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        <form class="row g-3">
            <div class="form-group col-md-4">
                <label for="kategori" class="form-label">Kategori</label>
                <select id="kategori" class="form-control">
                    <option value="">Semua</option>
                    <option value="makanan">Makanan</option>
                    <option value="kerajinan">Kerajinan</option>
                    <option value="kopi">Kopi</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="lokasi" class="form-label">Lokasi</label>
                <select id="lokasi" class="form-control">
                    <option value="">Semua Lokasi</option>
                    <option value="balai">Balai Desa</option>
                    <option value="pagubugan">Pagubugan</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Tabel UMKM -->
<div class="card shadow mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar UMKM Terdaftar</h6>
        <div>
            <button class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama UMKM</th>
                        <th>Kategori</th>
                        <th>Pemilik</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Lung Coffe</td>
                        <td>Kopi</td>
                        <td>Bu Siti</td>
                        <td>Balai Desa</td>
                        <td>081234567890</td>
                        <td>Produksi kopi kemasan sachet lokal desa</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Kriya Melung</td>
                        <td>Kerajinan</td>
                        <td>Siti Aminah</td>
                        <td>Pagubugan</td>
                        <td>085612345678</td>
                        <td>Kerajinan tangan dari bambu dan rotan</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>