<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Log Aktivitas</h1>

<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Filter Log</h6>
    </div>
    <div class="card-body">
        <form class="row g-3">
            <div class="form-group col-md-4">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="jenis_aksi">Jenis Aktivitas</label>
                <select id="jenis_aksi" class="form-control">
                    <option value="">Semua</option>
                    <option value="tambah">Tambah Data</option>
                    <option value="edit">Edit Data</option>
                    <option value="hapus">Hapus Data</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                </select>
            </div>
            <div class="form-group col-md-4 d-flex align-items-end">
                <button class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>Nama Pengguna</th>
                    <th>Aksi</th>
                    <th>Detail</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>2025-07-31 14:05:12</td>
                    <td>Admin1</td>
                    <td>Edit Data</td>
                    <td>Perbarui data petani ID #5</td>
                    <td>192.168.1.2</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>2025-07-31 13:50:21</td>
                    <td>Admin2</td>
                    <td>Tambah Data</td>
                    <td>Input kopi masuk 25kg dari Bu Sari</td>
                    <td>192.168.1.3</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>2025-07-31 13:00:01</td>
                    <td>Admin1</td>
                    <td>Login</td>
                    <td>Berhasil login</td>
                    <td>192.168.1.2</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>