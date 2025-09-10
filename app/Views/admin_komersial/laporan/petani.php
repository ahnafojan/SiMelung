<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<style>
    :root {
        --primary-color: #4e73df;
        --secondary-text: #858796;
        --body-bg: #f0f2f5;
        --card-bg: #ffffff;
        --border-color: #e3e6f0;
    }

    /* ... (CSS Anda yang sudah ada tidak saya hapus) ... */
    .page-title {
        color: #3a3b45;
        font-weight: 700;
    }

    .page-subtitle {
        color: var(--secondary-text);
        font-size: 0.9rem;
    }

    .filter-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
    }

    .filter-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.25rem;
    }

    #result-count {
        font-style: italic;
        color: var(--secondary-text);
    }

    .main-content-card {
        border-radius: 0.75rem;
        border: none;
    }

    .main-content-card .card-header {
        background-color: #f8f9fc;
        border-bottom: 2px solid var(--border-color);
        color: #3a3b45;
        font-weight: 600;
    }

    .view-desktop {
        display: none;
    }

    .view-mobile {
        background-color: var(--body-bg);
        padding: 1rem 0.5rem;
    }

    #petani-list-mobile {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .request-card {
        background-color: var(--card-bg);
        border-radius: 0.75rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 1rem;
    }

    .card-header-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .requester-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .requester-info .icon-circle {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--primary-color);
        color: white;
    }

    .requester-name {
        font-weight: 700;
        color: var(--primary-color);
    }

    .request-time {
        font-size: 0.8rem;
        color: var(--secondary-text);
    }

    .card-body-details {
        font-size: 0.9rem;
        color: #5a5c69;
        padding-left: 0.5rem;
        border-left: 3px solid var(--border-color);
    }

    .card-body-details strong {
        color: #3a3b45;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #e0e0e0;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #5a5c69;
        font-weight: bold;
    }

    .empty-state p {
        color: var(--secondary-text);
    }

    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (min-width: 992px) {
        .view-mobile {
            display: none;
        }

        .view-desktop {
            display: block;
        }

        .table-custom thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            background-color: #f8f9fc;
        }

        .table-custom tbody tr:hover {
            background-color: #f1f3f8;
        }

        .table-custom td {
            vertical-align: middle;
        }
    }

    /* === [BARU] CSS UNTUK PAGINATION === */
    .pagination-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        padding: 0.5rem 0;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #5a5c69;
    }

    .per-page-label i {
        color: #858796;
    }

    .dropdown-container {
        position: relative;
        display: inline-block;
    }

    .per-page-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        cursor: pointer;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .per-page-select:focus {
        border-color: #4e73df;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .dropdown-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #858796;
    }

    .page-info {
        font-size: 0.875rem;
        color: #5a5c69;
    }

    .info-text .fas {
        color: #858796;
    }

    .pagination-nav .pagination {
        margin-bottom: 0;
    }

    @media (max-width: 991.98px) {
        .pagination-wrapper {
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }

        .pagination-nav {
            order: -1;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title">Laporan Petani</h1>
            <p class="mb-0 page-subtitle">Detail data petani yang terdaftar di sistem.</p>
        </div>
        <a href="<?= base_url('admin-komersial/laporan') ?>" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-3 filter-card shadow-sm">
                <div class="row align-items-end">
                    <div class="col-lg-4 col-md-6 mb-2">
                        <label for="filter-search" class="filter-label">Cari Nama Petani</label>
                        <input type="text" class="form-control form-control-sm" id="filter-search" placeholder="Ketik nama..." value="<?= esc($filters['search']) ?>">
                    </div>
                    <div class="col-lg-4 col-md-6 mb-2">
                        <label for="filter-jenis-kopi" class="filter-label">Filter Jenis Kopi</label>
                        <select class="form-control form-control-sm" id="filter-jenis-kopi">
                            <option value="">Semua Jenis</option>
                            <?php foreach ($daftarJenisKopi as $kopi): ?>
                                <option value="<?= esc($kopi['nama_jenis']) ?>" <?= ($filters['jenis_kopi'] === $kopi['nama_jenis']) ? 'selected' : '' ?>><?= esc($kopi['nama_jenis']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-2">
                        <label class="filter-label">&nbsp;</label>
                        <div class="btn-group w-100">
                            <a id="export-excel" href="#" class="btn btn-sm btn-success" title="Export ke Excel"><i class="fas fa-file-excel"></i></a>
                            <a id="export-pdf" href="#" class="btn btn-sm btn-danger" title="Export ke PDF"><i class="fas fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-2">
                        <label class="filter-label">&nbsp;</label>
                        <button class="btn btn-sm btn-outline-secondary w-100" id="reset-filter">
                            <i class="fas fa-eraser mr-1"></i> Reset
                        </button>
                    </div>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-end align-items-center">
                    <span class="small" id="result-count">Memuat...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow main-content-card">
        <div class="card-header py-3"><i class="fas fa-users mr-2"></i> Daftar Petani</div>
        <div id="petani-list-container">
            <?= $petaniListView ?>
        </div>

        <div class="card-footer" id="pagination-container">
            <div class="pagination-wrapper">

                <div class="per-page-selector">
                    <label class="per-page-label"><i class="fas fa-list-ul me-2"></i> Tampilkan</label>
                    <div class="dropdown-container">
                        <select id="per_page_select" class="per-page-select">
                            <option value="10" <?= ($perPage == 10) ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= ($perPage == 100) ? 'selected' : '' ?>>100</option>
                        </select>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <span class="per-page-suffix">data per halaman</span>
                </div>

                <nav class="pagination-nav" id="pagination-nav-links" aria-label="Navigasi Halaman Petani">
                    <?= $petaniPager->links('petani', 'custom_pagination_template') ?>
                </nav>

                <div class="page-info">
                    <span class="info-text" id="page-info-text">
                        <i class="fas fa-info-circle me-2"></i> Memuat info...
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Definisi Elemen
        const searchInput = document.getElementById('filter-search');
        const jenisKopiSelect = document.getElementById('filter-jenis-kopi');
        const perPageSelect = document.getElementById('per_page_select');
        const resetButton = document.getElementById('reset-filter');
        const listContainer = document.getElementById('petani-list-container');
        const paginationNavContainer = document.getElementById('pagination-nav-links');
        const pageInfoText = document.getElementById('page-info-text');
        const resultCount = document.getElementById('result-count');
        const exportExcelBtn = document.getElementById('export-excel');
        const exportPdfBtn = document.getElementById('export-pdf');
        let searchTimeout;

        const baseExportUrl = "<?= site_url('admin-komersial/laporan-petani/export') ?>";

        // Fungsi untuk memperbarui teks info pagination
        function updatePageInfo(totalItems, perPage, currentPage) {
            if (totalItems == 0) {
                pageInfoText.innerHTML = `<i class="fas fa-info-circle me-2"></i> Tidak ada data`;
                return;
            }
            const startItem = (currentPage - 1) * perPage + 1;
            const endItem = Math.min(currentPage * perPage, totalItems);
            pageInfoText.innerHTML = `<i class="fas fa-info-circle me-2"></i> Menampilkan ${startItem}-${endItem} dari ${totalItems} data`;
        }

        // [PERBAIKAN] Fungsi untuk generate custom pagination HTML
        function generateCustomPagination(paginationData) {
            if (!paginationData || !paginationData.links) return '';

            let paginationHTML = '<ul class="pagination">';

            // Previous button
            if (paginationData.has_previous) {
                paginationHTML += `<li class="page-item">
                    <a class="page-link" href="${paginationData.previous_url}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>`;
            } else {
                paginationHTML += `<li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>`;
            }

            // Page numbers
            paginationData.links.forEach(link => {
                if (link.active) {
                    paginationHTML += `<li class="page-item active">
                        <span class="page-link">${link.title}</span>
                    </li>`;
                } else if (link.url) {
                    paginationHTML += `<li class="page-item">
                        <a class="page-link" href="${link.url}">${link.title}</a>
                    </li>`;
                } else {
                    paginationHTML += `<li class="page-item disabled">
                        <span class="page-link">${link.title}</span>
                    </li>`;
                }
            });

            // Next button
            if (paginationData.has_next) {
                paginationHTML += `<li class="page-item">
                    <a class="page-link" href="${paginationData.next_url}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>`;
            } else {
                paginationHTML += `<li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>`;
            }

            paginationHTML += '</ul>';
            return paginationHTML;
        }

        // Fungsi Fetch Data Utama
        async function fetchData(url) {
            listContainer.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Memuat...</p></div>';
            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();

                // Update content
                listContainer.innerHTML = data.list_view;

                // [PERBAIKAN] Menggunakan custom pagination template jika tersedia
                if (data.custom_pagination) {
                    paginationNavContainer.innerHTML = generateCustomPagination(data.custom_pagination);
                } else if (data.pagination) {
                    paginationNavContainer.innerHTML = data.pagination;
                }

                resultCount.textContent = `Menampilkan total ${data.total} petani`;

                // Update info pagination
                const currentUrl = new URL(url, window.location.origin);
                const currentPage = parseInt(currentUrl.searchParams.get('page_petani') || '1', 10);
                updatePageInfo(data.total, perPageSelect.value, currentPage);

                history.pushState(null, '', url);
                updateExportLinks();
            } catch (error) {
                console.error('Fetch error:', error);
                listContainer.innerHTML = '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle"></i><p>Gagal memuat data.</p></div>';
            }
        }

        function getFilterParams() {
            const params = new URLSearchParams();
            params.set('search', searchInput.value);
            params.set('jenis_kopi', jenisKopiSelect.value);
            return params;
        }

        function updateExportLinks() {
            const params = getFilterParams();
            exportExcelBtn.href = `${baseExportUrl}/excel?${params.toString()}`;
            exportPdfBtn.href = `${baseExportUrl}/pdf?${params.toString()}`;
        }

        function handleFilterChange() {
            const params = getFilterParams();
            params.set('per_page', perPageSelect.value);
            const newUrl = window.location.pathname + '?' + params.toString();
            fetchData(newUrl);
        }

        // --- Event Listeners ---
        searchInput.addEventListener('keyup', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(handleFilterChange, 500);
        });

        jenisKopiSelect.addEventListener('change', handleFilterChange);
        perPageSelect.addEventListener('change', handleFilterChange);

        resetButton.addEventListener('click', () => {
            searchInput.value = '';
            jenisKopiSelect.value = '';
            perPageSelect.value = '10';
            handleFilterChange();
        });

        // Event delegation untuk pagination
        const paginationContainer = document.getElementById('pagination-container');
        paginationContainer.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && link.closest('#pagination-nav-links')) {
                e.preventDefault();
                fetchData(link.href);
            }
        });

        // Inisialisasi awal
        resultCount.textContent = `Menampilkan total <?= $petaniPager->getTotal('petani') ?> petani`;
        updatePageInfo(<?= $petaniPager->getTotal('petani') ?>, <?= $perPage ?>, <?= $petaniPager->getCurrentPage('petani') ?>);
        updateExportLinks();
    });
</script>

<?= $this->endSection() ?>