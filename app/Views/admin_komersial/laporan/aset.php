<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title">Laporan Aset Produksi</h1>
            <p class="mb-0 page-subtitle">Detail Aset Produksi Bumdes melung.</p>
        </div>
        <a href="<?= base_url('admin-komersial/laporan') ?>" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Aset</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-aset-count">
                                <?= number_format($pagerAset->getTotal('aset'), 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Nilai (Rp)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-nilai-aset">
                                <?php
                                $totalNilai = 0;
                                // Note: This initial calculation might differ from the filtered total.
                                // The AJAX response will provide the accurate filtered total.
                                foreach ($aset as $item) {
                                    $totalNilai += $item['nilai_perolehan'];
                                }
                                echo number_format($totalNilai, 0, ',', '.');
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Filter Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="filter-aktif-display">
                                <?= $filterTahun == 'semua' ? 'Semua Tahun' : 'Tahun ' . $filterTahun ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-filter fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Per Halaman</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="per-page-display">
                                <?= $perPageAset ?> Item
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list-ul fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-database mr-2"></i>
                Data Aset Produksi
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle btn btn-outline-primary btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download mr-1"></i> Export
                    <i class="fas fa-chevron-down ml-1"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" id="export-excel-btn" href="#">
                        <i class="fas fa-file-excel text-success mr-2"></i>
                        Export Excel
                    </a>
                    <a class="dropdown-item" id="export-pdf-btn" href="#">
                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                        Export PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="card border-0 bg-light mb-4">
                <div class="card-body">
                    <h6 class="card-title font-weight-bold text-gray-800 mb-3">
                        <i class="fas fa-sliders-h mr-2"></i>Filter & Pengaturan
                    </h6>
                    <form id="filterAsetForm">
                        <div class="row align-items-end">
                            <div class="col-lg-5 col-md-6 mb-3">
                                <label for="tahun_aset" class="form-label font-weight-bold text-gray-700">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Tahun Perolehan
                                </label>
                                <select name="tahun_aset" id="tahun_aset" class="form-control">
                                    <option value="semua" <?= ($filterTahun == 'semua') ? 'selected' : '' ?>>
                                        Semua Tahun
                                    </option>
                                    <?php foreach ($daftarTahun as $th): ?>
                                        <option value="<?= $th['tahun_perolehan'] ?>" <?= ($filterTahun == $th['tahun_perolehan']) ? 'selected' : '' ?>>
                                            <?= esc($th['tahun_perolehan']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-5 col-md-6 mb-3">
                                <label for="per_page_aset" class="form-label font-weight-bold text-gray-700">
                                    <i class="fas fa-list-ol mr-1"></i>
                                    Items per Halaman
                                </label>
                                <select name="per_page_aset" id="per_page_aset" class="form-control">
                                    <option value="10" <?= ($perPageAset == 10) ? 'selected' : '' ?>>10 Items</option>
                                    <option value="25" <?= ($perPageAset == 25) ? 'selected' : '' ?>>25 Items</option>
                                    <option value="50" <?= ($perPageAset == 50) ? 'selected' : '' ?>>50 Items</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-12 mb-3">
                                <a href="<?= current_url() ?>" class="btn btn-outline-secondary btn-sm d-block">
                                    <i class="fas fa-redo mr-1"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="aset-data-container">
                <?= $this->include('admin_komersial/laporan/_aset_table_partial', ['aset' => $aset, 'pagerAset' => $pagerAset]) ?>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Preview Foto Aset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Preview" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    .hover-row:hover {
        background-color: #f8f9fc;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .badge-outline-primary {
        color: #4e73df;
        border: 1px solid #4e73df;
        background-color: transparent;
    }

    .badge-outline-info {
        color: #36b9cc;
        border: 1px solid #36b9cc;
        background-color: transparent;
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    @media (max-width: 768px) {
        .card-header h6 {
            font-size: 0.9rem;
        }
    }
</style>

<!-- JavaScript -->
<script>
    function showImage(src, title) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModalLabel').textContent = 'Preview: ' + title;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const filterTahunSelect = document.getElementById('tahun_aset');
        const perPageSelect = document.getElementById('per_page_aset');
        const dataContainer = document.getElementById('aset-data-container');
        const exportExcelBtn = document.getElementById('export-excel-btn');
        const exportPdfBtn = document.getElementById('export-pdf-btn');

        const baseUrl = "<?= site_url('admin-komersial/laporan/aset') ?>";
        const baseExportUrl = "<?= site_url('admin-komersial/export/aset') ?>";

        async function fetchData(url = null) {
            // Show loading effect
            dataContainer.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data aset...</p>
                </div>`;

            let fetchUrl = url;
            if (!fetchUrl) {
                const params = new URLSearchParams({
                    tahun_aset: filterTahunSelect.value,
                    per_page_aset: perPageSelect.value
                });
                fetchUrl = `${baseUrl}?${params.toString()}`;
            }

            try {
                const response = await fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();

                // Update content with new data
                dataContainer.innerHTML = data.table_partial;

                // Update stats in the header
                document.getElementById('total-aset-count').textContent = data.stats.total_aset;
                document.getElementById('total-nilai-aset').textContent = data.stats.total_nilai;
                document.getElementById('filter-aktif-display').textContent = data.stats.filter_aktif;
                document.getElementById('per-page-display').textContent = data.stats.per_page;

                // Update URL in browser without refresh
                history.pushState(null, '', fetchUrl);
                updateExportLinks();

            } catch (error) {
                console.error('Fetch error:', error);
                dataContainer.innerHTML = `
                    <div class="text-center py-5 text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                        <p class="mt-2">Gagal memuat data.</p>
                    </div>`;
            }
        }

        function updateExportLinks() {
            const params = new URLSearchParams({
                tahun_aset: filterTahunSelect.value
            });
            exportExcelBtn.href = `${baseExportUrl}/excel?${params.toString()}`;
            exportPdfBtn.href = `${baseExportUrl}/pdf?${params.toString()}`;
        }

        // Event listeners for filters
        filterTahunSelect.addEventListener('change', () => fetchData());
        perPageSelect.addEventListener('change', () => fetchData());

        // Event listener for pagination (event delegation)
        dataContainer.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination a');
            if (link) {
                e.preventDefault();
                fetchData(link.href);
            }
        });

        // Initialize export links on page load
        updateExportLinks();
    });
</script>

<?= $this->endSection() ?>