<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen UMKM Desa</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah UMKM Baru</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('umkm/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Nama UMKM</label>
                    <input type="text" class="form-control" name="nama_umkm" placeholder="Contoh: Kopi Sejahtera" required>
                </div>
                <div class="form-group">
                    <label>Pemilik</label>
                    <input type="text" class="form-control" name="pemilik" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select class="form-control" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Kerajinan">Kerajinan</option>
                        <option value="Agribisnis">Agribisnis</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Tuliskan deskripsi UMKM..."></textarea>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat" rows="2" placeholder="Alamat lengkap UMKM"></textarea>
                </div>
                <div class="form-group">
                    <label>Alamat Google Maps (URL)</label>
                    <input type="url" class="form-control" name="gmaps_url" placeholder="https://maps.google.com/...">
                </div>
                <div class="form-group">
                    <label>Kontak</label>
                    <input type="text" class="form-control" name="kontak" placeholder="081234567890 atau email">
                </div>
                <div class="form-group">
                    <label>Foto UMKM</label>
                    <input type="file" class="form-control-file" name="foto_umkm" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar UMKM</h6>
                <div class="d-flex ml-auto">
                    <!-- FORM PENCARIAN -->
                    <form class="form-inline my-2 my-lg-0" action="" method="get">
                        <input class="form-control mr-sm-2" type="search" placeholder="Cari UMKM..." aria-label="Search" name="keyword" value="<?= esc($keyword ?? '') ?>">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </form>

                    <!-- TOMBOL RESET: Muncul hanya jika ada keyword -->
                    <?php if (!empty($keyword)): ?>
                        <a href="<?= base_url('umkm') ?>" class="btn btn-secondary my-2 my-sm-0 ml-2" title="Tampilkan Semua Data">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <!-- Teks Umpan Balik Pencarian -->
            <?php if (!empty($keyword)): ?>
                <p class="text-muted">Menampilkan hasil untuk: <strong>"<?= esc($keyword) ?>"</strong>. Ditemukan <strong><?= count($umkm) ?></strong> data.</p>
            <?php endif; ?>
            <table class="table table-bordered table-hover">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama UMKM</th>
                        <th>Pemilik</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Alamat</th>
                        <th>Google Maps</th>
                        <th>Kontak</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (!empty($umkm) && is_array($umkm)): ?>
                        <?php $no = 1;
                        foreach ($umkm as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($u['nama_umkm']) ?></td>
                                <td><?= esc($u['pemilik']) ?></td>
                                <td><?= esc(ucfirst($u['kategori'])) ?></td>
                                <td><?= esc($u['deskripsi']) ?></td>
                                <td><?= esc($u['alamat']) ?></td>
                                <td>
                                    <?php if (!empty($u['gmaps_url'])): ?>
                                        <!-- Perbaikan: Gunakan URL Google Maps langsung, bukan URL 'show' -->
                                        <a href="<?= esc($u['gmaps_url']) ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-map-marker-alt"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($u['kontak']) ?></td>
                                <td>
                                    <?php if (!empty($u['foto_umkm'])): ?>
                                        <img src="<?= base_url('uploads/foto_umkm/' . $u['foto_umkm']) ?>" alt="Foto UMKM" width="70">
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="d-flex justify-content-center">

                                    <!-- Logika Tombol EDIT (Gembok Kuning) -->
                                    <?php if ($u['edit_status'] === 'approved'): ?>
                                        <!-- APPROVED: Tombol Edit/Buka -->
                                        <button class="btn btn-sm btn-warning mx-1" data-toggle="modal" data-target="#editModal<?= $u['id'] ?>" title="Edit UMKM (Izin Aktif)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    <?php elseif ($u['edit_status'] === 'pending'): ?>
                                        <!-- PENDING: Tombol Menunggu -->
                                        <button class="btn btn-sm btn-secondary mx-1" disabled title="Permintaan Edit Menunggu Persetujuan">
                                            <i class="fas fa-hourglass-half"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- NONE: Tombol Gembok Kuning untuk REQUEST izin -->
                                        <button class="btn btn-sm btn-warning mx-1 request-permission-btn" data-id="<?= $u['id'] ?>" data-action="edit" title="Minta Izin Edit">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    <?php endif; ?>

                                    <!-- Logika Tombol HAPUS (Gembok Merah) -->
                                    <?php if ($u['delete_status'] === 'approved'): ?>
                                        <!-- APPROVED: Tombol Hapus/Buka -->
                                        <a href="<?= base_url('umkm/delete/' . $u['id']) ?>" class="btn btn-sm btn-danger mx-1" onclick="return confirm('Yakin ingin menghapus UMKM ini?')" title="Hapus UMKM (Izin Aktif)">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php elseif ($u['delete_status'] === 'pending'): ?>
                                        <!-- PENDING: Tombol Menunggu -->
                                        <button class="btn btn-sm btn-secondary mx-1" disabled title="Permintaan Hapus Menunggu Persetujuan">
                                            <i class="fas fa-hourglass-half"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- NONE: Tombol Gembok Merah untuk REQUEST izin -->
                                        <button class="btn btn-sm btn-danger mx-1 request-permission-btn" data-id="<?= $u['id'] ?>" data-action="delete" title="Minta Izin Hapus">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Modal Edit (Hanya akan berfungsi jika tombol edit 'approved' diklik) -->
                            <div class="modal fade" id="editModal<?= $u['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $u['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="post" action="<?= base_url('umkm/update/' . $u['id']) ?>" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?= $u['id'] ?>">Edit UMKM</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama UMKM</label>
                                                    <input type="text" class="form-control" name="nama_umkm" value="<?= esc($u['nama_umkm']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Pemilik</label>
                                                    <input type="text" class="form-control" name="pemilik" value="<?= esc($u['pemilik']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Kategori</label>
                                                    <select class="form-control" name="kategori" required>
                                                        <option value="Makanan" <?= ($u['kategori'] == 'Makanan') ? 'selected' : '' ?>>Makanan</option>
                                                        <option value="Minuman" <?= ($u['kategori'] == 'Minuman') ? 'selected' : '' ?>>Minuman</option>
                                                        <option value="Kerajinan" <?= ($u['kategori'] == 'Kerajinan') ? 'selected' : '' ?>>Kerajinan</option>
                                                        <option value="Agribisnis" <?= ($u['kategori'] == 'Agribisnis') ? 'selected' : '' ?>>Agribisnis</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Deskripsi</label>
                                                    <textarea class="form-control" name="deskripsi" rows="3"><?= esc($u['deskripsi']) ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <textarea class="form-control" name="alamat" rows="2"><?= esc($u['alamat']) ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alamat Google Maps (URL)</label>
                                                    <input type="url" class="form-control" name="gmaps_url" value="<?= esc($u['gmaps_url']) ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Kontak</label>
                                                    <input type="text" class="form-control" name="kontak" value="<?= esc($u['kontak']) ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Foto UMKM</label><br>
                                                    <?php if (!empty($u['foto_umkm'])): ?>
                                                        <img src="<?= base_url('uploads/foto_umkm/' . $u['foto_umkm']) ?>" alt="Foto UMKM" width="70" class="mb-2"><br>
                                                    <?php endif; ?>
                                                    <input type="file" class="form-control-file" name="foto_umkm" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Simpan
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Batal
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">
                                <?php if (!empty($keyword)): ?>
                                    Data tidak ditemukan untuk kata kunci "<?= esc($keyword) ?>".
                                <?php else: ?>
                                    Belum ada UMKM.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Skrip JQuery dan AJAX untuk Request Permission -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation untuk menangani tombol permintaan izin
        document.body.addEventListener('click', function(e) {

            const button = e.target.closest('.request-permission-btn');
            if (!button) return;

            e.preventDefault();

            const umkmId = button.dataset.id;
            const actionType = button.dataset.action;

            // Konfirmasi sebelum mengirim permintaan (Ganti dengan modal kustom di lingkungan produksi)
            const confirmMessage = 'Anda yakin ingin mengajukan permintaan izin untuk ' + actionType.toUpperCase() + ' data UMKM ini?';

            if (!confirm(confirmMessage)) { // Menggunakan confirm() karena lingkungan tidak mengizinkan modal kustom yang kompleks
                return;
            }

            // Simpan konten asli tombol dan nonaktifkan
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            button.disabled = true;

            // Menggunakan Fetch API untuk mengirim permintaan AJAX ke Controller
            fetch('<?= base_url('umkm/requestAccess') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        // Menggunakan 'kopimasuk_id' untuk sementara sesuai yang didefinisikan di Controller Anda
                        'kopimasuk_id': umkmId,
                        'action_type': actionType,
                        // Mengambil hash CSRF
                        '<?= csrf_token() ?>': document.querySelector('input[name="<?= csrf_token() ?>"]').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Ganti dengan notifikasi yang lebih baik
                    if (data.status === 'success') {
                        alert(data.message);
                        // Muat ulang halaman untuk menampilkan status PENDING yang baru
                        window.location.reload();
                    } else {
                        alert('Gagal: ' + data.message);
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan jaringan atau server.');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
        });
    });
</script>

<?= $this->endSection() ?>