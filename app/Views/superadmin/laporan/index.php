<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Laporan Rekap Kopi</h1>

<!-- Filter Laporan Kopi -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form class="row g-3">
            <div class="form-group col-md-4">
                <label for="periode" class="form-label">Periode</label>
                <select id="periode" class="form-control">
                    <option value="bulan">Bulanan</option>
                    <option value="tahun">Tahunan</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="bulan" class="form-label">Bulan</label>
                <input type="month" id="bulan" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="petani" class="form-label">Petani</label>
                <select id="petani" class="form-control">
                    <option value="">Semua Petani</option>
                    <option value="1">Pak Ahmad</option>
                    <option value="2">Bu Sari</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Rekap Kopi -->
<div class="card shadow mb-5">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Rekap Data Kopi</h6>
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
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter UMKM</h6>
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
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar UMKM Terdaftar</h6>
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
<!-- Judul Aset Wisata -->
<h1 class="h3 mb-4 text-gray-800">Laporan Aset Wisata</h1>

<!-- Filter Aset Wisata -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Filter Aset Wisata</h6>
    </div>
    <div class="card-body">
        <form class="row g-3">
            <div class="form-group col-md-4">
                <label for="kategoriAset" class="form-label">Kategori Aset</label>
                <select id="kategoriAset" class="form-control">
                    <option value="">Semua Kategori</option>
                    <option value="alat">Alat</option>
                    <option value="bangunan">Bangunan</option>
                    <option value="fasilitas">Fasilitas Umum</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="lokasiAset" class="form-label">Lokasi Aset</label>
                <select id="lokasiAset" class="form-control">
                    <option value="">Semua Lokasi</option>
                    <option value="curug">Curug Song</option>
                    <option value="kebun">Kebun Kopi Melung</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Aset Wisata -->
<div class="card shadow mb-5">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Aset Wisata Desa</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Tahun Perolehan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Gazebo Wisata</td>
                        <td>Fasilitas Umum</td>
                        <td>Curug Song</td>
                        <td>Baik</td>
                        <td>2021</td>
                        <td>Digunakan sebagai tempat istirahat pengunjung</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Mesin Roasting</td>
                        <td>Alat</td>
                        <td>Kebun Kopi Melung</td>
                        <td>Baik</td>
                        <td>2022</td>
                        <td>Digunakan untuk produksi kopi desa</td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>Papan Informasi</td>
                        <td>Fasilitas Umum</td>
                        <td>Curug Song</td>
                        <td>Cukup</td>
                        <td>2020</td>
                        <td>Perlu pengecatan ulang</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?= $this->endSection() ?>