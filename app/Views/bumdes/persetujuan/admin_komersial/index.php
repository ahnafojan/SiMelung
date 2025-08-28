<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<style>
    /* Variabel Warna untuk Konsistensi */
    :root {
        --primary-color: #4e73df;
        --success-color: #1cc88a;
        --danger-color: #e74a3b;
        --warning-color: #f6c23e;
        --secondary-text: #858796;
        --body-bg: #f0f2f5;
        --card-bg: #ffffff;
        --border-color: #e3e6f0;
    }

    /* === Header Halaman === */
    .page-title {
        color: #3a3b45;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: var(--secondary-text);
        font-size: 0.9rem;
    }

    .btn-refresh {
        background-color: var(--primary-color);
        color: white;
        border: none;
    }

    .btn-refresh:hover {
        background-color: #2e59d9;
        color: white;
    }

    /* === Panel Filter === */
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

    /* === Konten Utama: Card Daftar Permintaan === */
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

    /* === Tampilan Mobile (Default): Card-based === */
    .view-desktop {
        display: none;
    }

    .view-mobile {
        background-color: var(--body-bg);
        padding: 1rem 0.5rem;
    }

    #request-list-mobile {
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
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .request-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
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
    }

    .requester-info .icon-circle i {
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

    .card-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .card-actions .btn {
        flex-grow: 1;
        /* Tombol memenuhi ruang */
        font-weight: 600;
    }

    /* === Tampilan Desktop (Layar > 768px): Table-based === */
    @media (min-width: 768px) {
        .view-mobile {
            display: none;
        }

        .view-desktop {
            display: block;
        }

        .table-custom thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            background-color: #f8f9fc;
        }

        .table-custom tbody tr {
            transition: background-color 0.15s ease-in-out;
        }

        .table-custom tbody tr:hover {
            background-color: #f1f3f8;
        }

        .table-custom td {
            vertical-align: middle;
        }

        .btn-group-actions .btn {
            min-width: 90px;
        }
    }

    /* === Elemen Tambahan === */
    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
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
</style>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title">Daftar Permintaan Izin Akses</h1>
            <p class="mb-0 page-subtitle">Kelola dan respon permintaan perubahan data yang masuk.</p>
        </div>
        <button class="btn btn-sm btn-refresh shadow-sm d-none d-sm-inline-block" onclick="refreshData()">
            <i class="fas fa-sync-alt fa-sm mr-1"></i> Refresh Data
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-3 filter-card shadow-sm">
                <div class="row align-items-end">
                    <div class="col-md-6 col-lg-3 mb-2">
                        <label for="filter-action" class="filter-label">Filter Aksi</label>
                        <select class="form-control form-control-sm" id="filter-action">
                            <option value="">Semua Aksi</option>
                            <option value="edit">Edit</option>
                            <option value="delete">Hapus</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-2">
                        <label for="filter-target" class="filter-label">Filter Target Data</label>
                        <select class="form-control form-control-sm" id="filter-target">
                            <option value="">Semua Target</option>
                            <option value="petani">Petani</option>
                            <option value="pohon">Pohon</option>
                            <option value="kopi_masuk">Kopi Masuk</option>
                            <option value="kopi_keluar">Kopi Keluar</option>
                            <option value="jenis_pohon">Jenis Pohon</option>
                            <option value="aset">Aset</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-2">
                        <label for="filter-date" class="filter-label">Tanggal Permintaan</label>
                        <input type="date" class="form-control form-control-sm" id="filter-date">
                    </div>
                    <div class="col-md-6 col-lg-3 mb-2">
                        <label for="filter-search" class="filter-label">Cari Nama Pemohon</label>
                        <input type="text" class="form-control form-control-sm" id="filter-search" placeholder="Ketik nama...">
                    </div>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()">
                        <i class="fas fa-eraser mr-1"></i> Reset Filter
                    </button>
                    <span class="small" id="result-count">
                        Menampilkan <?= count($requests) ?> dari <?= count($requests) ?> permintaan
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow main-content-card">
        <div class="card-header py-3">
            <i class="fas fa-list-ul mr-2"></i> Data Permintaan Akses
        </div>

        <?php if (empty($requests)): ?>
            <div class="card-body">
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>Tidak Ada Permintaan</h5>
                    <p class="mb-0">Saat ini belum ada permintaan baru yang perlu ditinjau.</p>
                </div>
            </div>
        <?php else: ?>

            <div class="card-body view-mobile p-0">
                <div id="request-list-mobile">
                    <?php foreach ($requests as $req): ?>
                        <?php
                        $action_type_normalized = strtolower(esc($req['action_type'] === 'update' ? 'edit' : $req['action_type']));
                        $actionClass = 'secondary';
                        $actionIcon = 'info-circle';
                        if ($action_type_normalized === 'edit') {
                            $actionClass = 'warning';
                            $actionIcon = 'pencil-alt';
                        } elseif ($action_type_normalized === 'delete') {
                            $actionClass = 'danger';
                            $actionIcon = 'trash-alt';
                        }
                        ?>
                        <div class="request-card" id="request-card-<?= $req['id'] ?>"
                            data-action="<?= $action_type_normalized ?>"
                            data-target="<?= esc($req['target_type']) ?>"
                            data-date="<?= date('Y-m-d', strtotime($req['created_at'])) ?>"
                            data-requester="<?= strtolower(esc($req['requester_name'])) ?>">

                            <div class="card-header-info">
                                <div class="requester-info">
                                    <div class="icon-circle"><i class="fas fa-user-tie"></i></div>
                                    <div>
                                        <div class="requester-name"><?= esc($req['requester_name']) ?></div>
                                        <div class="request-time"><?= esc(date('d M Y, H:i', strtotime($req['created_at']))) ?></div>
                                    </div>
                                </div>
                                <span class="badge badge-<?= $actionClass ?>">
                                    <i class="fas fa-<?= $actionIcon ?> mr-1"></i> <?= ucfirst($action_type_normalized) ?>
                                </span>
                            </div>

                            <hr class="my-2">

                            <div class="card-body-details">
                                <strong>Target Data:</strong><br>
                                <span>
                                    <?php if ($req['target_type'] === 'petani' && !empty($req['petani_target_name'])): ?>
                                        Petani: <strong><?= esc($req['petani_target_name']) ?></strong>
                                    <?php elseif ($req['target_type'] === 'pohon' && !empty($req['pohon_owner_name'])): ?>
                                        Pohon Milik: <strong><?= esc($req['pohon_owner_name']) ?></strong>
                                    <?php elseif ($req['target_type'] === 'kopi_masuk' && !empty($req['kopimasuk_petani_name'])): ?>
                                        Kopi Masuk: <strong><?= esc($req['kopimasuk_petani_name']) ?> (<?= esc($req['kopimasuk_jumlah']) ?> Kg)</strong>
                                    <?php elseif ($req['target_type'] === 'kopi_keluar' && !empty($req['kopikeluar_tujuan'])): ?>
                                        Kopi Keluar: <strong><?= esc($req['kopikeluar_jumlah']) ?> Kg ke <?= esc($req['kopikeluar_tujuan']) ?></strong>
                                    <?php elseif ($req['target_type'] === 'aset' && !empty($req['aset_target_name'])): ?>
                                        Aset: <strong><?= esc($req['aset_target_name']) ?> (<?= esc($req['aset_target_kode']) ?>)</strong>
                                    <?php else: ?>
                                        <?= esc(ucfirst(str_replace('_', ' ', $req['target_type']))) ?> ID: #<?= esc($req['target_id']) ?>
                                    <?php endif; ?>
                                </span>
                            </div>

                            <div class="card-actions">
                                <button class="btn btn-sm btn-success btn-respond" data-request-id="<?= $req['id'] ?>" data-decision="approve"><i class="fas fa-check-circle mr-1"></i> Setujui</button>
                                <button class="btn btn-sm btn-danger btn-respond" data-request-id="<?= $req['id'] ?>" data-decision="reject"><i class="fas fa-times-circle mr-1"></i> Tolak</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-body view-desktop p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0" id="dataTable" width="100%">
                        <thead>
                            <tr>
                                <th>Pemohon</th>
                                <th>Jenis Aksi</th>
                                <th>Target Data</th>
                                <th>Tanggal</th>
                                <th class="text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="request-list-desktop">
                            <?php foreach ($requests as $req): ?>
                                <?php
                                $action_type_normalized = strtolower(esc($req['action_type'] === 'update' ? 'edit' : $req['action_type']));
                                $actionClass = 'secondary';
                                $actionIcon = 'info-circle';
                                if ($action_type_normalized === 'edit') {
                                    $actionClass = 'warning';
                                    $actionIcon = 'pencil-alt';
                                } elseif ($action_type_normalized === 'delete') {
                                    $actionClass = 'danger';
                                    $actionIcon = 'trash-alt';
                                }
                                ?>
                                <tr id="request-row-<?= $req['id'] ?>"
                                    data-action="<?= $action_type_normalized ?>"
                                    data-target="<?= esc($req['target_type']) ?>"
                                    data-date="<?= date('Y-m-d', strtotime($req['created_at'])) ?>"
                                    data-requester="<?= strtolower(esc($req['requester_name'])) ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle bg-primary mr-3"><i class="fas fa-user-tie text-white"></i></div>
                                            <div>
                                                <div class="font-weight-bold text-gray-800"><?= esc($req['requester_name']) ?></div>
                                                <div class="small text-muted">ID: <?= esc($req['requester_id']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $actionClass ?> badge-pill px-3 py-2">
                                            <i class="fas fa-<?= $actionIcon ?> mr-1"></i> <?= ucfirst($action_type_normalized) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($req['target_type'] === 'petani' && !empty($req['petani_target_name'])): ?>
                                            Petani: <strong><?= esc($req['petani_target_name']) ?></strong>
                                        <?php elseif ($req['target_type'] === 'pohon' && !empty($req['pohon_owner_name'])): ?>
                                            Pohon Milik: <strong><?= esc($req['pohon_owner_name']) ?></strong>
                                        <?php elseif ($req['target_type'] === 'kopi_masuk' && !empty($req['kopimasuk_petani_name'])): ?>
                                            Kopi Masuk: <strong><?= esc($req['kopimasuk_petani_name']) ?> (<?= esc($req['kopimasuk_jumlah']) ?> Kg)</strong>
                                        <?php elseif ($req['target_type'] === 'kopi_keluar' && !empty($req['kopikeluar_tujuan'])): ?>
                                            Kopi Keluar: <strong><?= esc($req['kopikeluar_jumlah']) ?> Kg ke <?= esc($req['kopikeluar_tujuan']) ?></strong>
                                        <?php elseif ($req['target_type'] === 'aset' && !empty($req['aset_target_name'])): ?>
                                            Aset: <strong><?= esc($req['aset_target_name']) ?> (<?= esc($req['aset_target_kode']) ?>)</strong>
                                        <?php else: ?>
                                            <?= esc(ucfirst(str_replace('_', ' ', $req['target_type']))) ?> ID: #<?= esc($req['target_id']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold"><?= esc(date('d M Y', strtotime($req['created_at']))) ?></div>
                                        <div class="small text-muted"><?= esc(date('H:i', strtotime($req['created_at']))) ?> WIB</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm btn-group-actions" role="group">
                                            <button class="btn btn-success btn-respond" data-request-id="<?= $req['id'] ?>" data-decision="approve"><i class="fas fa-check-circle mr-1"></i> Setujui</button>
                                            <button class="btn btn-danger btn-respond" data-request-id="<?= $req['id'] ?>" data-decision="reject"><i class="fas fa-times-circle mr-1"></i> Tolak</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalRequests = <?= count($requests) ?>;

        function applyFilters() {
            const actionFilter = document.getElementById('filter-action').value;
            const targetFilter = document.getElementById('filter-target').value;
            const dateFilter = document.getElementById('filter-date').value;
            const searchFilter = document.getElementById('filter-search').value.toLowerCase();
            let visibleCount = 0;

            const filterLogic = (element) => {
                const action = element.dataset.action;
                const target = element.dataset.target;
                const date = element.dataset.date;
                const requester = element.dataset.requester;

                const actionMatch = !actionFilter || action === actionFilter;
                const targetMatch = !targetFilter || target === targetFilter;
                const dateMatch = !dateFilter || date === dateFilter;
                const searchMatch = !searchFilter || requester.includes(searchFilter);

                return actionMatch && targetMatch && dateMatch && searchMatch;
            };

            // Filter untuk tampilan desktop (tabel) dan mobile (card)
            document.querySelectorAll('#request-list-desktop tr, #request-list-mobile .request-card').forEach(el => {
                if (filterLogic(el)) {
                    el.style.display = '';
                    // Hitung hanya satu jenis elemen untuk menghindari penghitungan ganda
                    if (el.tagName === 'TR') {
                        visibleCount++;
                    }
                } else {
                    el.style.display = 'none';
                }
            });

            document.getElementById('result-count').textContent = `Menampilkan ${visibleCount} dari ${totalRequests} permintaan`;
        }

        // Event listeners untuk filter
        document.getElementById('filter-action').addEventListener('change', applyFilters);
        document.getElementById('filter-target').addEventListener('change', applyFilters);
        document.getElementById('filter-date').addEventListener('change', applyFilters);
        document.getElementById('filter-search').addEventListener('keyup', applyFilters);

        // Fungsi untuk membersihkan filter
        window.clearFilters = function() {
            document.getElementById('filter-action').value = '';
            document.getElementById('filter-target').value = '';
            document.getElementById('filter-date').value = '';
            document.getElementById('filter-search').value = '';
            applyFilters();
        };

        // Fungsi untuk refresh data
        window.refreshData = function() {
            location.reload();
        };

        // Event handler untuk tombol Setujui/Tolak
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-respond')) {
                const button = e.target.closest('.btn-respond');
                const requestId = button.dataset.requestId;
                const decision = button.dataset.decision;
                const decisionText = (decision === 'approve') ? 'menyetujui' : 'menolak';

                Swal.fire({
                    title: `Anda Yakin?`,
                    text: `Anda akan ${decisionText} permintaan ini. Tindakan ini tidak dapat diurungkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: (decision === 'approve') ? 'var(--success-color)' : 'var(--danger-color)',
                    cancelButtonColor: 'var(--secondary-text)',
                    confirmButtonText: `Ya, ${decisionText}!`,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Menggunakan Fetch API untuk AJAX
                        fetch("<?= site_url('persetujuanKomersial/respond') ?>", {
                                method: "POST",
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: new URLSearchParams({
                                    'request_id': requestId,
                                    'decision': decision,
                                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: data.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });

                                    // Hilangkan elemen dari kedua tampilan dengan efek fade out
                                    const cardElement = document.getElementById('request-card-' + requestId);
                                    const rowElement = document.getElementById('request-row-' + requestId);

                                    [cardElement, rowElement].forEach(el => {
                                        if (el) {
                                            el.style.transition = 'opacity 0.5s ease';
                                            el.style.opacity = '0';
                                            setTimeout(() => {
                                                el.remove();
                                                applyFilters(); // Update hitungan setelah menghapus
                                            }, 500);
                                        }
                                    });
                                } else {
                                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error', 'Koneksi ke server gagal.', 'error');
                                console.error('AJAX Error:', error);
                            });
                    }
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>