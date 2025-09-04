<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800 text-center">Laporan Laba Rugi Desa Melung</h1>

<div class="card shadow mb-5">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Pendapatan (Rp)</th>
                        <th>Beban (Rp)</th>
                        <th>Laba/Rugi (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Penjualan Kopi</td>
                        <td>15.000.000</td>
                        <td>-</td>
                        <td>15.000.000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Pembelian Bibit Kopi</td>
                        <td>-</td>
                        <td>2.000.000</td>
                        <td>-2.000.000</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Pembayaran Upah Petani</td>
                        <td>-</td>
                        <td>3.000.000</td>
                        <td>-3.000.000</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Biaya Operasional</td>
                        <td>-</td>
                        <td>1.000.000</td>
                        <td>-1.000.000</td>
                    </tr>
                    <tr class="font-weight-bold bg-light">
                        <td colspan="4" class="text-right">Total Laba/Rugi</td>
                        <td>9.000.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>