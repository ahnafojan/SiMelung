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

    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title">Laporan Petani</h1>
            <p class="mb-0 page-subtitle">Detail data petani yang terdaftar di sistem.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-3 filter-card shadow-sm">
                <div class="row align-items-end">
                    <div class="col-lg-4 col-md-6 mb-2">
                        <label for="filter-search" class="filter-label">Cari Nama Petani</label>
                        <input type="text" class="form-control form-control-sm" id="filter-search" placeholder="Ketik nama..." value="<?= esc($filters['search']) ?>">
                    </div>
                    <div class="col-lg-3 col-md-6 mb-2">
                        <label for="filter-jenis-kopi" class="filter-label">Filter Jenis Kopi</label>
                        <select class="form-control form-control-sm" id="filter-jenis-kopi">
                            <option value="">Semua Jenis</option>
                            <?php foreach ($daftarJenisKopi as $kopi): ?>
                                <option value="<?= esc($kopi['nama_jenis']) ?>" <?= ($filters['jenis_kopi'] === $kopi['nama_jenis']) ? 'selected' : '' ?>><?= esc($kopi['nama_jenis']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-2">
                        <label for="per_page_select" class="filter-label">Item/Halaman</label>
                        <select class="form-control form-control-sm" id="per_page_select">
                            <option value="10" <?= ($perPage == 10) ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= ($perPage == 100) ? 'selected' : '' ?>>100</option>
                        </select>
                    </div>
                    <!-- Tombol Ekspor Dihapus -->
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
        <div class="card-footer d-flex justify-content-end" id="pagination-container">
            <?= $petaniPager->links('petani', 'default_full') ?>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Definisi Elemen ---
        const searchInput = document.getElementById('filter-search');
        const jenisKopiSelect = document.getElementById('filter-jenis-kopi');
        const perPageSelect = document.getElementById('per_page_select');
        const resetButton = document.getElementById('reset-filter');
        const listContainer = document.getElementById('petani-list-container');
        const paginationContainer = document.getElementById('pagination-container');
        const resultCount = document.getElementById('result-count');
        let searchTimeout;
        // Variabel untuk ekspor dihapus

        // --- Definisi Fungsi ---
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

                listContainer.innerHTML = data.list_view;
                paginationContainer.innerHTML = data.pagination;
                resultCount.textContent = `Menampilkan total ${data.total} petani`;
                history.pushState(null, '', url);
                // Fungsi updateExportLinks() dihapus
            } catch (error) {
                console.error('Fetch error:', error);
                listContainer.innerHTML = '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle"></i><p>Gagal memuat data. Periksa Console (F12).</p></div>';
            }
        }

        function getFilterParams() {
            const params = new URLSearchParams();
            if (searchInput) params.set('search', searchInput.value);
            if (jenisKopiSelect) params.set('jenis_kopi', jenisKopiSelect.value);
            return params;
        }

        // Fungsi updateExportLinks() dihapus

        function handleFilterChange() {
            const params = getFilterParams();
            if (perPageSelect) params.set('per_page', perPageSelect.value);
            // URL sudah benar karena menggunakan window.location.pathname
            // Pastikan rute Anda adalah 'desa/laporan_komersial/petani'
            const newUrl = window.location.pathname + '?' + params.toString();
            fetchData(newUrl);
        }

        // --- Event Listeners ---
        if (searchInput) {
            searchInput.addEventListener('keyup', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(handleFilterChange, 500);
            });
        }

        if (jenisKopiSelect) {
            jenisKopiSelect.addEventListener('change', handleFilterChange);
        }

        if (perPageSelect) {
            perPageSelect.addEventListener('change', handleFilterChange);
        }

        if (resetButton) {
            resetButton.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (jenisKopiSelect) jenisKopiSelect.value = '';
                if (perPageSelect) perPageSelect.value = '10';
                handleFilterChange();
            });
        }

        if (paginationContainer) {
            paginationContainer.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href) {
                    e.preventDefault();
                    fetchData(link.href);
                }
            });
        }

        // --- Inisialisasi awal ---
        if (resultCount) {
            resultCount.textContent = `Menampilkan total <?= $petaniPager->getTotal('petani') ?> petani`;
        }
        // Panggilan updateExportLinks() dihapus
    });
</script>

<?= $this->endSection() ?>