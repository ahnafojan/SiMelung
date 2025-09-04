<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800 text-center">Laporan Arus Kas Desa Melung</h1>

<div class="card shadow mb-5">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Pemasukan (Rp)</th>
                        <th>Pengeluaran (Rp)</th>
                        <th>Saldo Akhir (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>01/08/2025</td>
                        <td>Penerimaan Dana Desa</td>
                        <td>10.000.000</td>
                        <td>-</td>
                        <td>10.000.000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>05/08/2025</td>
                        <td>Pembelian Bibit Kopi</td>
                        <td>-</td>
                        <td>2.000.000</td>
                        <td>8.000.000</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>10/08/2025</td>
                        <td>Pembayaran Upah Petani</td>
                        <td>-</td>
                        <td>3.000.000</td>
                        <td>5.000.000</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>15/08/2025</td>
                        <td>Penjualan Kopi</td>
                        <td>5.000.000</td>
                        <td>-</td>
                        <td>10.000.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>