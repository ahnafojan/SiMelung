<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<style>
    /* Style untuk tampilan mobile (card view) */
    @media (max-width: 767.98px) {
        .table-responsive .table thead {
            display: none;
        }

        .table-responsive .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: .5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-responsive .table tbody tr td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
        }

        .table-responsive .table tbody tr td:last-child {
            border-bottom: none;
            justify-content: flex-end;
        }

        .table-responsive .table tbody tr td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 1rem;
            flex-shrink: 0;
        }
    }

    /* CSS Pagination yang sama dengan kopi masuk */
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
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Manajemen Objek Wisata</h1>
            <p class="mb-0 page-subtitle text-muted">Kelola data master untuk semua lokasi objek wisata.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalObjekWisata" onclick="resetForm()">
            <i class="fas fa-plus-circle me-1"></i> Tambah Lokasi Baru
        </button>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Wisata</th>
                            <th>Lokasi</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list_wisata)) : ?>
                            <?php $nomor = ($currentPage - 1) * $perPage + 1; ?>
                            <?php foreach ($list_wisata as $wisata) : ?>
                                <tr>
                                    <td data-label="No"><?= $nomor++ ?></td>
                                    <td data-label="Nama Wisata"><?= esc($wisata['nama_wisata']) ?></td>
                                    <td data-label="Lokasi"><?= esc($wisata['lokasi']) ?></td>
                                    <td data-label="Deskripsi"><?= esc($wisata['deskripsi']) ?></td>
                                    <td data-label="Aksi" class="text-center">
                                        <div class="btn-group">

                                            <?php if ($wisata['edit_status'] == 'approved') : ?>
                                                <button class="btn btn-sm btn-outline-warning mx-1" title="Edit Data" onclick="editData(<?= htmlspecialchars(json_encode($wisata), ENT_QUOTES, 'UTF-8') ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php elseif ($wisata['edit_status'] == 'pending') : ?>
                                                <button class="btn btn-sm btn-secondary mx-1 disabled" title="Permintaan edit sedang diproses">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            <?php else : ?>
                                                <button class="btn btn-sm btn-outline-warning mx-1 btn-request-access"
                                                    data-wisata-id="<?= $wisata['id'] ?>"
                                                    data-action-type="edit"
                                                    title="Minta Izin Edit">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>


                                            <?php if ($wisata['delete_status'] == 'approved') : ?>
                                                <a href="<?= base_url('objekwisata/delete/' . $wisata['id']) ?>"
                                                    class="btn btn-sm btn-outline-danger mx-1"
                                                    title="Hapus Data"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php elseif ($wisata['delete_status'] == 'pending') : ?>
                                                <button class="btn btn-sm btn-secondary mx-1 disabled" title="Permintaan hapus sedang diproses">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            <?php else : ?>
                                                <button class="btn btn-sm btn-outline-danger mx-1 btn-request-access"
                                                    data-wisata-id="<?= $wisata['id'] ?>"
                                                    data-action-type="delete"
                                                    title="Minta Izin Hapus">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada data objek wisata.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
                <div class="pagination-wrapper">
                    <form method="get" class="per-page-selector">
                        <label class="per-page-label">Tampilkan</label>
                        <div class="dropdown-container">
                            <select name="per_page" class="per-page-select" onchange="this.form.submit()">
                                <option value="10" <?= ($perPage == 10 ? 'selected' : '') ?>>10</option>
                                <option value="25" <?= ($perPage == 25 ? 'selected' : '') ?>>25</option>
                                <option value="100" <?= ($perPage == 100 ? 'selected' : '') ?>>100</option>
                            </select>
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </div>
                        <span class="per-page-suffix">data</span>
                    </form>

                    <nav class="pagination-nav" aria-label="Navigasi Halaman">
                        <?= $pager->links('default', 'custom_pagination_template') ?>
                    </nav>

                    <div class="page-info">
                        <?php
                        $totalItems = $pager->getTotal();
                        $startItem = (($currentPage - 1) * $perPage) + 1;
                        $endItem = min($currentPage * $perPage, $totalItems);
                        ?>
                        Menampilkan <?= $startItem ?>-<?= $endItem ?> dari <?= $totalItems ?> data
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalObjekWisata" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formObjekWisata" action="<?= base_url('objekwisata/store') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="wisata_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Lokasi Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_wisata" class="form-label">Nama Wisata</label>
                        <input type="text" name="nama_wisata" id="nama_wisata" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi/Alamat</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Script untuk modal (TETAP SAMA)
    const modalElement = new bootstrap.Modal(document.getElementById('modalObjekWisata'));
    const form = document.getElementById('formObjekWisata');
    const modalLabel = document.getElementById('modalLabel');
    const idInput = document.getElementById('wisata_id');
    const namaInput = document.getElementById('nama_wisata');
    const lokasiInput = document.getElementById('lokasi');
    const deskripsiInput = document.getElementById('deskripsi');

    function resetForm() {
        form.action = "<?= base_url('objekwisata/store') ?>";
        modalLabel.textContent = "Tambah Lokasi Wisata";
        idInput.value = "";
        form.reset();
    }

    function editData(data) {
        form.action = "<?= base_url('objekwisata/store') ?>";
        modalLabel.textContent = "Edit Lokasi Wisata";
        idInput.value = data.id;
        namaInput.value = data.nama_wisata;
        lokasiInput.value = data.lokasi;
        deskripsiInput.value = data.deskripsi;
        modalElement.show();
    }

    // Script BARU untuk permintaan izin
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-request-access').forEach(button => {
            button.addEventListener('click', function() {
                const btn = this;
                const wisataId = btn.dataset.wisataId;
                const action = btn.dataset.actionType;

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch("<?= site_url('objekwisata/requestaccess') ?>", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({
                            'wisata_id': wisataId,
                            'action_type': action,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terkirim!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            btn.classList.remove('btn-outline-warning', 'btn-outline-danger');
                            btn.classList.add('btn-secondary', 'disabled');
                            btn.innerHTML = '<i class="fas fa-clock"></i>';
                            btn.title = 'Menunggu Persetujuan';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Gagal mengirim permintaan.'
                            });
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-lock"></i>';
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan koneksi.'
                        });
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-lock"></i>';
                    });
            });
        });
    });
</script>

<?= $this->endSection() ?>