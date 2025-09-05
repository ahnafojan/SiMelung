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
            /* Pastikan tombol ada di kanan */
            justify-content: flex-end;
        }

        .table-responsive .table tbody tr td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 1rem;
            flex-shrink: 0;
        }
    }

    .asset-photo {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .asset-photo:hover {
        transform: scale(1.1);
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

    .pagination .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .pagination .page-link {
        color: #007bff;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Manajemen Aset Pariwisata</h1>
            <p class="mb-0 page-subtitle text-muted">Kelola data aset Pariwisata Desa Melung.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAset">
            <i class="fas fa-plus-circle me-1"></i> Tambah Aset Baru
        </button>
    </div>

    <!-- Notifikasi -->
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

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Aset Terdaftar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Lokasi Wisata</th>
                            <th>Foto</th>
                            <th>Nama Aset</th>
                            <th>Kode & NUP</th>
                            <th>Tahun</th>
                            <th>Nilai (Rp)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($asets)) : ?>
                            <?php
                            // Inisialisasi nomor urut berdasarkan halaman saat ini
                            $nomor = ($currentPage - 1) * $perPage + 1;
                            ?>
                            <?php foreach ($asets as $aset) : ?>
                                <tr>
                                    <td data-label="No"><?= $nomor++ ?></td>
                                    <td data-label="Lokasi Wisata"><?= esc($aset['nama_wisata']) ?></td>
                                    <td data-label="Foto">
                                        <?php if (!empty($aset['foto_aset'])) : ?>
                                            <img src="<?= base_url('uploads/aset_pariwisata/' . $aset['foto_aset']) ?>" alt="<?= esc($aset['nama_pariwisata'] ?? $aset['nama_aset'] ?? '') ?>" class="asset-photo img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;" data-bs-toggle="modal" data-bs-target="#modalLihatFoto" data-src="<?= base_url('uploads/aset_pariwisata/' . $aset['foto_aset']) ?>" data-title="<?= esc($aset['nama_pariwisata'] ?? $aset['nama_aset'] ?? '') ?>">
                                        <?php else : ?>
                                            <span class="text-muted">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Nama Aset"><?= esc($aset['nama_pariwisata'] ?? $aset['nama_aset'] ?? '') ?></td>
                                    <td data-label="Kode & NUP">
                                        <span class="d-block font-weight-bold"><?= esc($aset['kode_aset']) ?></span>
                                        <small class="text-muted">NUP: <?= esc($aset['nup'] ?: '-') ?></small>
                                    </td>
                                    <td data-label="Tahun"><?= esc($aset['tahun_perolehan']) ?></td>
                                    <td data-label="Nilai (Rp)"><?= number_format($aset['nilai_perolehan'], 0, ',', '.') ?></td>
                                    <td data-label="Aksi" class="text-center">
                                        <div class="btn-group">
                                            <?php if ($aset['can_edit'] ?? false) : ?>
                                                <!-- Tombol Edit Jika Punya Izin -->
                                                <button class="btn btn-sm btn-outline-warning mx-1" title="Edit Aset" data-bs-toggle="modal" data-bs-target="#modalEditAset" data-id="<?= $aset['id'] ?>" data-objek-wisata-id="<?= $aset['objek_wisata_id'] ?? '' ?>" data-nama-aset="<?= esc($aset['nama_pariwisata'] ?? $aset['nama_aset'] ?? '') ?>" data-kode-aset="<?= esc($aset['kode_aset']) ?>" data-nup="<?= esc($aset['nup']) ?>" data-tahun-perolehan="<?= esc($aset['tahun_perolehan']) ?>" data-nilai-perolehan="<?= esc($aset['nilai_perolehan']) ?>" data-metode-pengadaan="<?= esc($aset['metode_pengadaan']) ?>" data-sumber-pengadaan="<?= esc($aset['sumber_pengadaan']) ?>" data-keterangan="<?= esc($aset['keterangan']) ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php else : ?>
                                                <!-- Tombol Minta Izin Edit -->
                                                <button class="btn btn-sm btn-outline-warning mx-1 btn-request-access" data-aset-id="<?= $aset['id'] ?>" data-action-type="edit" title="Minta Izin Edit">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($aset['can_delete'] ?? false) : ?>
                                                <!-- Tombol Hapus Jika Punya Izin -->
                                                <a href="<?= base_url('asetpariwisata/delete/' . $aset['id']) ?>" class="btn btn-sm btn-outline-danger mx-1" title="Hapus Aset" onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php else : ?>
                                                <!-- Tombol Minta Izin Hapus -->
                                                <button class="btn btn-sm btn-outline-danger mx-1 btn-request-access" data-aset-id="<?= $aset['id'] ?>" data-action-type="delete" title="Minta Izin Hapus">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data aset yang ditambahkan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Blok Pagination Kustom -->
        <div class="card-footer">
            <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
                <div class="pagination-wrapper">
                    <!-- Per Page Selector -->
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

                    <!-- Pagination Navigation -->
                    <nav class="pagination-nav" aria-label="Navigasi Halaman">
                        <?= $pager->links('default', 'custom_pagination_template') ?>
                    </nav>

                    <!-- Page Info -->
                    <div class="page-info">
                        <span class="info-text">
                            <i class="fas fa-info-circle mr-2"></i>
                            <?php
                            $totalItems = $pager->getTotal();
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
</div>

<!-- Modal Tambah Aset -->
<div class="modal fade" id="modalTambahAset" tabindex="-1" aria-labelledby="modalTambahAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= base_url('asetpariwisata/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAsetLabel">Formulir Tambah Aset Baru</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Lokasi Objek Wisata</label>
                            <select name="objek_wisata_id" class="form-control" required>
                                <option value="">-- Pilih Lokasi Wisata --</option>
                                <?php if (!empty($list_wisata)) : ?>
                                    <?php foreach ($list_wisata as $wisata) : ?>
                                        <option value="<?= $wisata['id'] ?>"><?= esc($wisata['nama_wisata']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nama Aset</label>
                            <input type="text" name="nama_aset" class="form-control" placeholder="Contoh: Meja Informasi" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Kode Aset</label>
                            <input type="text" name="kode_aset" class="form-control" placeholder="Contoh: ASET-001" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nomor Urut Perolehan (NUP)</label>
                            <input type="text" name="nup" class="form-control" placeholder="Contoh: 12345">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Tahun Perolehan</label>
                            <input type="number" name="tahun_perolehan" class="form-control" placeholder="Contoh: 2023" min="1900" max="2100" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nilai Perolehan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="tambah_nilai_perolehan" name="nilai_perolehan" class="form-control rupiah-input" placeholder="500.000" required>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Metode Pengadaan</label>
                            <select name="metode_pengadaan" class="form-control" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="Hibah">Hibah</option>
                                <option value="Pembelian">Pembelian</option>
                                <option value="Sewa">Sewa</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Sumber Pengadaan</label>
                            <input type="text" name="sumber_pengadaan" class="form-control" placeholder="Contoh: Dana Desa" required>
                        </div>
                        <div class="col-12 form-group mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Isi keterangan tambahan jika ada..."></textarea>
                        </div>
                        <div class="col-12 form-group mb-3">
                            <label>Foto Aset</label>
                            <input type="file" name="foto_aset" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Aset -->
<div class="modal fade" id="modalEditAset" tabindex="-1" aria-labelledby="modalEditAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditAset" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditAsetLabel">Formulir Edit Aset</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Lokasi Objek Wisata</label>
                            <select id="edit_objek_wisata_id" name="objek_wisata_id" class="form-control" required>
                                <option value="">-- Pilih Lokasi Wisata --</option>
                                <?php if (!empty($list_wisata)) : ?>
                                    <?php foreach ($list_wisata as $wisata) : ?>
                                        <option value="<?= $wisata['id'] ?>"><?= esc($wisata['nama_wisata']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nama Aset</label>
                            <input type="text" id="edit_nama_aset" name="nama_aset" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Kode Aset</label>
                            <input type="text" id="edit_kode_aset" name="kode_aset" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nomor Urut Perolehan (NUP)</label>
                            <input type="text" id="edit_nup" name="nup" class="form-control">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Tahun Perolehan</label>
                            <input type="number" id="edit_tahun_perolehan" name="tahun_perolehan" class="form-control" min="1900" max="2100" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nilai Perolehan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="edit_nilai_perolehan" name="nilai_perolehan" class="form-control rupiah-input" required>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Metode Pengadaan</label>
                            <select id="edit_metode_pengadaan" name="metode_pengadaan" class="form-control" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="Hibah">Hibah</option>
                                <option value="Pembelian">Pembelian</option>
                                <option value="Sewa">Sewa</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Sumber Pengadaan</label>
                            <input type="text" id="edit_sumber_pengadaan" name="sumber_pengadaan" class="form-control" required>
                        </div>
                        <div class="col-12 form-group mb-3">
                            <label>Keterangan</label>
                            <textarea id="edit_keterangan" name="keterangan" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12 form-group mb-3">
                            <label>Ganti Foto Aset (Opsional)</label>
                            <input type="file" name="foto_aset" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Lihat Foto -->
<div class="modal fade" id="modalLihatFoto" tabindex="-1" aria-labelledby="modalLihatFotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLihatFotoLabel">Foto Aset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fotoAsetLengkap" src="" class="img-fluid rounded" alt="Foto Aset" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Logika untuk Modal Lihat Foto
        const modalLihatFoto = document.getElementById('modalLihatFoto');
        if (modalLihatFoto) {
            modalLihatFoto.addEventListener('show.bs.modal', function(event) {
                const triggerElement = event.relatedTarget;
                const imageUrl = triggerElement.getAttribute('data-src');
                const imageTitle = triggerElement.getAttribute('data-title');
                const modal = this;
                modal.querySelector('.modal-title').textContent = 'Foto Aset: ' + imageTitle;
                modal.querySelector('#fotoAsetLengkap').src = imageUrl;
            });
        }

        // Logika untuk Mengisi Modal Edit (CARA PALING STABIL)
        const modalEditAset = document.getElementById('modalEditAset');
        if (modalEditAset) {
            modalEditAset.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const objekWisataId = button.getAttribute('data-objek-wisata-id');
                const namaAset = button.getAttribute('data-nama-aset');
                const kodeAset = button.getAttribute('data-kode-aset');
                const nup = button.getAttribute('data-nup');
                const tahunPerolehan = button.getAttribute('data-tahun-perolehan');
                const nilaiPerolehan = button.getAttribute('data-nilai-perolehan');
                const metodePengadaan = button.getAttribute('data-metode-pengadaan');
                const sumberPengadaan = button.getAttribute('data-sumber-pengadaan');
                const keterangan = button.getAttribute('data-keterangan');

                const modal = this;
                const form = modal.querySelector('#formEditAset');
                form.action = '<?= base_url('asetpariwisata/update') ?>/' + id;

                modal.querySelector('#edit_objek_wisata_id').value = objekWisataId || '';
                modal.querySelector('#edit_nama_aset').value = namaAset || '';
                modal.querySelector('#edit_kode_aset').value = kodeAset || '';
                modal.querySelector('#edit_nup').value = nup || '';
                modal.querySelector('#edit_tahun_perolehan').value = tahunPerolehan || '';
                modal.querySelector('#edit_nilai_perolehan').value = nilaiPerolehan || '0';
                modal.querySelector('#edit_metode_pengadaan').value = metodePengadaan || '';
                modal.querySelector('#edit_sumber_pengadaan').value = sumberPengadaan || '';
                modal.querySelector('#edit_keterangan').value = keterangan || '';
            });
        }

        // Logika untuk Tombol Minta Izin
        document.querySelectorAll('.btn-request-access').forEach(button => {
            button.addEventListener('click', function() {
                const btn = this;
                const asetId = btn.dataset.asetId;
                const action = btn.dataset.actionType;

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                const requestUrl = "<?= site_url('asetpariwisata/requestaccess') ?>";

                fetch(requestUrl, {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({
                            'aset_id': asetId,
                            'action_type': action,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Permintaan Terkirim!',
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
                            text: 'Terjadi kesalahan koneksi. Silakan coba lagi.'
                        });
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-lock"></i>';
                    });
            });
        });
    });
</script>
<?= $this->endSection() ?>