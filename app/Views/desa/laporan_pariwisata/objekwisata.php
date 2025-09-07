<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<style>
    /* CSS untuk Pagination Kustom */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        padding: 1rem 0;
    }

    .per-page-selector,
    .page-info,
    .pagination-nav {
        margin: 0.5rem;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .per-page-label,
    .per-page-suffix {
        margin: 0 0.5rem;
    }

    .dropdown-container {
        position: relative;
    }

    .per-page-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        cursor: pointer;
    }

    .dropdown-icon {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6c757d;
    }

    .page-info {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .pagination-nav .pagination {
        margin-bottom: 0;
    }

    .pagination .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }
</style>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title">Laporan Objek Wisata Terdaftar</h1>
            <p class="mb-0 page-subtitle">Detail Objek Wisata Desa Melung.</p>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th>Nama Objek Wisata</th>
                        <th>Lokasi</th>
                        <th>Deskripsi Singkat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_wisata)) : ?>
                        <?php $no = 1 + (($currentPage - 1) * $perPage); ?>
                        <?php foreach ($list_wisata as $wisata) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= esc($wisata['nama_wisata']) ?></td>
                                <td><?= esc($wisata['lokasi']) ?></td>
                                <td><?= esc($wisata['deskripsi']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data objek wisata untuk dilaporkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <?php if (isset($pager) && $pager->getPageCount('wisata') > 1) : // PERBAIKAN 1: Menambahkan grup 'wisata' 
        ?>
            <div class="pagination-wrapper">
                <form method="get" class="per-page-selector">
                    <label class="per-page-label">
                        <i class="fas fa-list-ul mr-2"></i>
                        Tampilkan
                    </label>
                    <div class="dropdown-container">
                        <select name="per_page" class="per-page-select" onchange="this.form.submit()">
                            <option value="10" <?= ($perPage == 10 ? 'selected' : '') ?>>10</option>
                            <option value="25" <?= ($perPage == 25 ? 'selected' : '') ?>>25</option>
                            <option value="100" <?= ($perPage == 100 ? 'selected' : '') ?>>100</option>
                        </select>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <span class="per-page-suffix">data per halaman</span>
                </form>

                <nav class="pagination-nav" aria-label="Navigasi Halaman">
                    <?= $pager->links('wisata', 'custom_pagination_template') ?>
                </nav>

                <div class="page-info">
                    <span class="info-text">
                        <i class="fas fa-info-circle mr-2"></i>
                        <?php
                        $totalItems = $pager->getTotal('wisata'); // PERBAIKAN 2: Menambahkan grup 'wisata'
                        $startItem = (($currentPage - 1) * $perPage) + 1;
                        $endItem = min($currentPage * $perPage, $totalItems);
                        ?>
                        Menampilkan <?= $startItem ?>-<?= $endItem ?> dari <?= $totalItems ?> total data
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>