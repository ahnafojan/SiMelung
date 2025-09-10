<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content'); ?>
<style>
    /* Style untuk tabel responsif, tidak perlu diubah */
    @media (max-width: 991.98px) {
        .table-responsive-stack thead {
            display: none;
        }

        .table-responsive-stack table,
        .table-responsive-stack tbody,
        .table-responsive-stack tr,
        .table-responsive-stack td {
            display: block;
            width: 100%;
        }

        .table-responsive-stack tr {
            margin-bottom: 1.5rem;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .table-responsive-stack td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.75rem 1.25rem;
            text-align: right;
            border-bottom: 1px solid #e3e6f0;
        }

        .table-responsive-stack tr td:last-child {
            border-bottom: none;
        }

        .table-responsive-stack td::before {
            content: attr(data-label);
            font-weight: bold;
            text-align: left;
            margin-right: 1rem;
            color: #858796;
        }

        .table-responsive-stack td[data-label="No"] {
            display: none;
        }

        .table-responsive-stack td[data-label="Periode"] {
            background-color: #f8f9fc;
            font-size: 1.1rem;
            padding: 1rem 1.25rem;
            justify-content: center;
        }

        .table-responsive-stack td[data-label="Periode"]::before {
            display: none;
        }

        .table-responsive-stack td[data-label="Aksi"] {
            justify-content: center;
            padding: 1rem;
        }

        .table-responsive-stack td[data-label="Aksi"] .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title"><?= esc($title) ?></h1>
            <p class="mb-0 page-subtitle">Daftar BKU Bulanan Desa Melung.</p>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th class="text-center">Periode</th>
                        <th class="text-right">Total Pendapatan</th>
                        <th class="text-right">Total Pengeluaran</th>
                        <th class="text-right">Saldo Akhir</th>
                        <th class="text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporan)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data laporan.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($laporan as $row): ?>
                            <tr>
                                <td data-label="No" class="text-center align-middle"><?= $no++; ?></td>
                                <td data-label="Periode" class="text-center align-middle">
                                    <strong><?= date('F', mktime(0, 0, 0, $row['bulan'], 10)); ?> <?= $row['tahun']; ?></strong>
                                </td>
                                <td data-label="Total Pendapatan" class="text-right align-middle"><?= 'Rp ' . number_format($row['total_pendapatan'], 0, ',', '.'); ?></td>
                                <td data-label="Total Pengeluaran" class="text-right align-middle"><?= 'Rp ' . number_format($row['total_pengeluaran'], 0, ',', '.'); ?></td>
                                <td data-label="Saldo Akhir" class="text-right font-weight-bold align-middle <?= ($row['saldo_akhir'] < 0) ? 'text-danger' : 'text-success'; ?>">
                                    <?= 'Rp ' . number_format($row['saldo_akhir'], 0, ',', '.'); ?>
                                </td>
                                <!-- DIUBAH: Kolom aksi hanya berisi tombol detail -->
                                <td data-label="Aksi" class="text-center align-middle">
                                    <a href="<?= site_url('/desa/laporan_keuangan/bku_detail/' . $row['id']); ?>" class="btn btn-info btn-icon-split btn-sm">

                                        <span class="text">Lihat Detail</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>