<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<?php
// Memberikan nilai default untuk mencegah error
$currentPage = $currentPage ?? 1;
$perPage = $perPage ?? 10;
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Manajemen Aset</h1>
            <p class="mb-0 page-subtitle text-muted">Kelola data aset komersial, termasuk melihat, mengedit, dan menghapus.</p>
        </div>
        <a href="<?= base_url('aset-komersial') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Tambah Aset Baru
        </a>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th class="text-start">#</th>
                            <th>Foto Aset</th>
                            <th>Nama Barang</th>
                            <th>Kode & NUP</th>
                            <th>Tahun & Merek</th>
                            <th>Nilai Perolehan</th>
                            <th>Pengadaan</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($asets)) : ?>
                            <?php $nomor = ($currentPage - 1) * $perPage + 1; ?>
                            <?php foreach ($asets as $a) : ?>
                                <tr>
                                    <td class="text-start fw-bold"><?= $nomor++ ?></td>
                                    <td>
                                        <a href="<?= base_url('uploads/foto_aset/' . $a['foto']) ?>" data-lightbox="aset-images" data-title="<?= esc($a['nama_aset']) ?>">
                                            <img src="<?= base_url('uploads/foto_aset/' . $a['foto']) ?>" alt="<?= esc($a['nama_aset']) ?>" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                        </a>
                                    </td>
                                    <td><?= esc($a['nama_aset']) ?></td>
                                    <td>
                                        <span class="fw-bold d-block"><?= esc($a['kode_aset']) ?></span>
                                        <small class="text-muted">NUP: <?= esc($a['nup']) ?: '-' ?></small>
                                    </td>
                                    <td>
                                        <span class="fw-bold d-block"><?= esc($a['tahun_perolehan']) ?></span>
                                        <small class="text-muted"><?= esc($a['merk_type']) ?: '-' ?></small>
                                    </td>
                                    <td>Rp <?= number_format($a['nilai_perolehan'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="d-block"><?= esc($a['metode_pengadaan']) ?></span>
                                        <small class="text-muted"><?= esc($a['sumber_pengadaan']) ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $kondisi = esc($a['keterangan']);
                                        $badgeClass = 'bg-secondary';
                                        if ($kondisi == 'Baik') $badgeClass = 'bg-success';
                                        if ($kondisi == 'Perlu Perawatan' || $kondisi == 'Dalam Perbaikan') $badgeClass = 'bg-warning text-dark';
                                        if ($kondisi == 'Rusak') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $kondisi ?: 'N/A' ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">

                                            <?php if ($a['edit_status'] == 'approved') : ?>
                                                <button class="btn btn-sm btn-outline-warning mx-1 btn-edit-aset"
                                                    data-id="<?= $a['id_aset'] ?>"
                                                    data-nama_aset="<?= esc($a['nama_aset']) ?>"
                                                    data-kode_aset="<?= esc($a['kode_aset']) ?>"
                                                    data-nup="<?= esc($a['nup']) ?>"
                                                    data-tahun_perolehan="<?= esc($a['tahun_perolehan']) ?>"
                                                    data-merk_type="<?= esc($a['merk_type']) ?>"
                                                    data-nilai_perolehan="<?= esc($a['nilai_perolehan']) ?>"
                                                    data-keterangan="<?= esc($a['keterangan']) ?>"
                                                    data-metode_pengadaan="<?= esc($a['metode_pengadaan']) ?>"
                                                    data-sumber_pengadaan="<?= esc($a['sumber_pengadaan']) ?>"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditAset" title="Edit Aset">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php elseif ($a['edit_status'] == 'pending') : ?>
                                                <button class="btn btn-sm btn-secondary mx-1 disabled" title="Permintaan edit sedang diproses">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            <?php else : ?>
                                                <button class="btn btn-sm btn-outline-warning mx-1 btn-request-access"
                                                    data-aset-id="<?= $a['id_aset'] ?>"
                                                    data-action-type="edit" title="Minta Izin Edit">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($a['delete_status'] == 'approved') : ?>
                                                <button class="btn btn-sm btn-outline-danger mx-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal<?= $a['id_aset'] ?>" title="Hapus Aset">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php elseif ($a['delete_status'] == 'pending') : ?>
                                                <button class="btn btn-sm btn-secondary mx-1 disabled" title="Permintaan hapus sedang diproses">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            <?php else : ?>
                                                <button class="btn btn-sm btn-outline-danger mx-1 btn-request-access"
                                                    data-aset-id="<?= $a['id_aset'] ?>"
                                                    data-action-type="delete" title="Minta Izin Hapus">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Hapus untuk setiap item -->
                                <div class="modal fade" id="deleteModal<?= $a['id_aset'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus aset <strong><?= esc($a['nama_aset']) ?></strong>?
                                                Aksi ini tidak dapat dibatalkan.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <a href="<?= base_url('ManajemenAsetKomersial/delete/' . $a['id_aset']) ?>" class="btn btn-danger">Hapus Permanen</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <p class="fw-bold mb-0">Belum ada data aset yang dicatat.</p>
                                        <p>Silakan tambahkan data aset baru melalui tombol di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- =============================================================== -->
        <!-- BLOK PAGINATION -->
        <!-- =============================================================== -->
        <?php if (isset($pager) && $pager->getPageCount('asets') > 1): ?>
            <div class="card-footer">
                <div class="pagination-wrapper">
                    <form method="get" class="per-page-selector">
                        <label class="per-page-label">
                            <i class="fas fa-list-ul mr-2"></i> Tampilkan
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
                        <?= $pager->links('asets', 'custom_pagination_template') ?>
                    </nav>

                    <div class="page-info">
                        <span class="info-text">
                            <i class="fas fa-info-circle mr-2"></i>
                            <?php
                            $totalItems = $pager->getTotal('asets');
                            $startItem  = ($currentPage - 1) * $perPage + 1;
                            $endItem    = min($currentPage * $perPage, $totalItems);
                            ?>
                            Menampilkan <?= $startItem ?>-<?= $endItem ?> dari <?= $totalItems ?> total data
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- =============================================================== -->

    </div>
</div>

<!-- ============================================ -->
<!-- ▼▼▼ MODAL EDIT DENGAN DROPDOWN ▼▼▼ -->
<!-- ============================================ -->
<div class="modal fade" id="modalEditAset" tabindex="-1" aria-labelledby="modalEditAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formEditAset" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="modal-content shadow">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark" id="modalEditAsetLabel">Edit Data Aset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_aset" id="editIdAset">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editKategoriAset" class="form-label">Kategori Aset <span class="text-danger">*</span></label>
                            <select name="kategori_aset" id="editKategoriAset" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategoriAset as $kategori) : ?>
                                    <option value="<?= esc($kategori) ?>"><?= esc($kategori) ?></option>
                                <?php endforeach; ?>
                                <option value="Lainnya">Lainnya...</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="container_edit_nama_aset_lainnya" style="display: none;">
                            <label for="editNamaAsetLainnya" class="form-label">Nama Aset Lainnya <span class="text-danger">*</span></label>
                            <input type="text" name="nama_aset_lainnya" id="editNamaAsetLainnya" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="editKodeAset" class="form-label">Kode Aset <span class="text-danger">*</span></label>
                            <input type="text" name="kode_aset" id="editKodeAset" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editNup" class="form-label">Nomor Urut Pendaftaran (NUP)</label>
                            <input type="text" name="nup" id="editNup" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="editTahunPerolehan" class="form-label">Tahun Perolehan</label>
                            <input type="number" name="tahun_perolehan" id="editTahunPerolehan" class="form-control" placeholder="Contoh: 2024">
                        </div>
                        <div class="col-md-6">
                            <label for="editMerkType" class="form-label">Merk / Tipe</label>
                            <input type="text" name="merk_type" id="editMerkType" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="editNilaiPerolehan" class="form-label">Nilai Perolehan (Rp)</label>
                            <input type="number" name="nilai_perolehan" id="editNilaiPerolehan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="editKeterangan" class="form-label">Kondisi Aset <span class="text-danger">*</span></label>
                            <select name="keterangan" id="editKeterangan" class="form-select" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Baik">Baik</option>
                                <option value="Perlu Perawatan">Perlu Perawatan</option>
                                <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editMetodePengadaan" class="form-label">Metode Pengadaan</label>
                            <select name="metode_pengadaan" id="editMetodePengadaan" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Hibah">Hibah</option>
                                <option value="Pembelian">Pembelian</option>
                                <option value="Penyewaan">Penyewaan</option>
                                <option value="Peminjaman">Peminjaman</option>
                                <option value="Penukaran">Penukaran</option>
                                <option value="Pembuatan sendiri">Pembuatan sendiri</option>
                                <option value="Perbaikan/rekondisi">Perbaikan/rekondisi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editSumberPengadaan" class="form-label">Sumber Pengadaan</label>
                            <input type="text" name="sumber_pengadaan" id="editSumberPengadaan" class="form-control" required>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Ganti Foto Aset (opsional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* CSS UNTUK PAGINATION KUSTOM */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 0.75rem 1.25rem;
    }

    .per-page-selector,
    .page-info,
    .pagination-nav {
        display: flex;
        align-items: center;
    }

    .per-page-selector {
        gap: 0.5rem;
        color: #6c757d;
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
        font-size: 0.875rem;
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
        color: #6c757d;
        font-size: 0.875rem;
    }

    .pagination-nav .pagination {
        margin: 0;
    }

    .pagination-nav .page-item .page-link {
        color: #007bff;
    }

    .pagination-nav .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    @media (max-width: 768px) {
        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
        }

        .pagination-nav {
            order: -1;
        }
    }
</style>

<!-- Mengirim data PHP ke JavaScript -->
<script>
    const kategoriAsetList = <?= json_encode($kategoriAset ?? []) ?>;
</script>

<!-- ============================================ -->
<!-- ▼▼▼ JAVASCRIPT BARU UNTUK MODAL EDIT ▼▼▼ -->
<!-- ============================================ -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menangani logika modal edit
        document.querySelectorAll('.btn-edit-aset').forEach(button => {
            button.addEventListener('click', function() {
                const form = document.getElementById('formEditAset');
                const data = this.dataset;

                // 1. Set action form secara dinamis
                form.action = `<?= site_url('ManajemenAsetKomersial/update') ?>/${data.id}`;

                // 2. Isi semua field input standar
                document.getElementById('editIdAset').value = data.id;
                document.getElementById('editKodeAset').value = data.kode_aset;
                document.getElementById('editNup').value = data.nup;
                document.getElementById('editTahunPerolehan').value = data.tahun_perolehan;
                document.getElementById('editMerkType').value = data.merk_type;
                document.getElementById('editNilaiPerolehan').value = data.nilai_perolehan;
                document.getElementById('editMetodePengadaan').value = data.metode_pengadaan;
                document.getElementById('editSumberPengadaan').value = data.sumber_pengadaan;

                // 3. Set value untuk dropdown Kondisi
                document.getElementById('editKeterangan').value = data.keterangan;

                // 4. Logika cerdas untuk dropdown Kategori Aset
                const namaAset = data.nama_aset;
                const kategoriSelect = document.getElementById('editKategoriAset');
                const lainnyaContainer = document.getElementById('container_edit_nama_aset_lainnya');
                const lainnyaInput = document.getElementById('editNamaAsetLainnya');

                if (kategoriAsetList.includes(namaAset)) {
                    // Jika nama aset ada di daftar standar
                    kategoriSelect.value = namaAset;
                    lainnyaContainer.style.display = 'none';
                    lainnyaInput.removeAttribute('required');
                    lainnyaInput.value = '';
                } else {
                    // Jika nama aset adalah custom dari "Lainnya"
                    kategoriSelect.value = 'Lainnya';
                    lainnyaContainer.style.display = 'block';
                    lainnyaInput.setAttribute('required', 'required');
                    lainnyaInput.value = namaAset;
                }
            });
        });

        // Menangani perubahan pada dropdown Kategori di dalam modal
        document.getElementById('editKategoriAset').addEventListener('change', function() {
            const lainnyaContainer = document.getElementById('container_edit_nama_aset_lainnya');
            const lainnyaInput = document.getElementById('editNamaAsetLainnya');
            if (this.value === 'Lainnya') {
                lainnyaContainer.style.display = 'block';
                lainnyaInput.setAttribute('required', 'required');
            } else {
                lainnyaContainer.style.display = 'none';
                lainnyaInput.removeAttribute('required');
                lainnyaInput.value = '';
            }
        });

        // Menangani AJAX untuk tombol "Minta Izin"
        document.querySelectorAll('.btn-request-access').forEach(button => {
            button.addEventListener('click', function() {
                const btn = this;
                const asetId = btn.dataset.asetId;
                const action = btn.dataset.actionType;

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch("<?= site_url('ManajemenAsetKomersial/requestAccess') ?>", {
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
                                title: 'Berhasil',
                                text: data.message
                            });
                            btn.classList.remove('btn-outline-warning', 'btn-outline-danger');
                            btn.classList.add('btn-secondary', 'disabled');
                            btn.innerHTML = '<i class="fas fa-clock"></i>';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message
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