<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800 text-center">Laporan Neraca Desa Melung</h1>

<div class="card shadow mb-5">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th rowspan="2">No</th>
                        <th colspan="2">Aktiva</th>
                        <th colspan="2">Pasiva</th>
                    </tr>
                    <tr>
                        <th>Nama Akun</th>
                        <th>Jumlah (Rp)</th>
                        <th>Nama Akun</th>
                        <th>Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Kas</td>
                        <td>10.000.000</td>
                        <td>Modal</td>
                        <td>27.000.000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Piutang</td>
                        <td>5.000.000</td>
                        <td>Utang</td>
                        <td>3.000.000</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Persediaan Kopi</td>
                        <td>12.000.000</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="font-weight-bold bg-light">
                        <td colspan="2" class="text-right">Total Aktiva</td>
                        <td>27.000.000</td>
                        <td class="text-right">Total Pasiva</td>
                        <td>30.000.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>