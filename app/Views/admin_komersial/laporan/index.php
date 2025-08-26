<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
    Laporan Komersial Bumdes Melung
</h1>
<p class="mb-4 text-gray-600">Ringkasan data rekapitulasi kopi, petani, dan aset produksi.</p>
<!-- Filter Laporan Rekap Kopi -->
<?= $this->include('admin_komersial/laporan/filter_kopi') ?>

<!-- Rekap Kopi -->
<?= $this->include('admin_komersial/laporan/rekap_kopi') ?>

<!-- Petani -->
<div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 animated--grow-in card-hover-shadow rounded-lg">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Petani Terdaftar
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count($petaniList) ?> Petani
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 animated--grow-in card-hover-shadow rounded-lg">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Aset Terdaftar
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?= $totalAset ?? 0 ?> <span class="text-muted small">Unit</span></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Laporan Petani -->
<?= $this->include('admin_komersial/laporan/petani') ?>

<!-- Laporan Aset -->
<?= $this->include('admin_komersial/laporan/aset') ?>



<?= $this->endSection() ?>