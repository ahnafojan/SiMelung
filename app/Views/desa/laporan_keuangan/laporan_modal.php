<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800 text-center">Laporan Perubahan Modal Desa Melung</h1>

<div class="card shadow mb-5">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Jumlah (Rp)</th>
                        <th>Modal Akhir (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Modal Awal</td>
                        <td>20.000.000</td>
                        <td>20.000.000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Laba Bersih Tahun Berjalan</td>
                        <td>9.000.000</td>
                        <td>29.000.000</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Prive (Pengambilan Pemilik)</td>
                        <td>-2.000.000</td>
                        <td>27.000.000</td>
                    </tr>
                    <tr class="font-weight-bold bg-light">
                        <td colspan="3" class="text-right">Modal Akhir</td>
                        <td>27.000.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>