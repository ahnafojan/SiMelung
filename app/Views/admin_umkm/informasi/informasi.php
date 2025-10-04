<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Informasi UMKM Desa Melung</h1>
    <p class="text-muted">Kelola UMKM yang akan ditampilkan di pop-up Landing Page. Status 'Ditampilkan' berarti data ini aktif di publik.</p>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Informasi UMKM</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama UMKM</th>
                        <th>Pemilik</th>
                        <th>Alamat</th>
                        <th>Foto</th>
                        <th>Status Publikasi</th> <!-- Kolom Baru -->
                        <th>Aksi Publikasi</th> <!-- Kolom Baru -->
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (!empty($umkm) && is_array($umkm)): ?>
                        <?php $no = 1; foreach ($umkm as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($u['nama_umkm']) ?></td>
                                <td><?= esc($u['pemilik']) ?></td>
                                <td><?= esc($u['alamat']) ?></td>
                                <td>
                                    <?php if (!empty($u['foto_umkm'])): ?>
                                        <img src="<?= base_url('uploads/foto_umkm/' . $u['foto_umkm']) ?>" 
                                             alt="Foto UMKM" width="80" height="80" 
                                             style="object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada</span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Status Publikasi -->
                                <td>
                                    <?php if ($u['is_published'] == 1): ?>
                                        <span class="badge badge-success">Ditampilkan <i class="fas fa-check-circle"></i></span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Disembunyikan <i class="fas fa-times-circle"></i></span>
                                    <?php endif; ?>
                                </td>

                                <!-- Tombol Aksi Publikasi -->
                                <td>
                                    <?php if ($u['is_published'] == 1): ?>
                                        <button class="btn btn-sm btn-danger toggle-publish-btn" data-id="<?= $u['id'] ?>" data-status="0" title="Sembunyikan dari publik">
                                            <i class="fas fa-eye-slash"></i> Sembunyikan
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-success toggle-publish-btn" data-id="<?= $u['id'] ?>" data-status="1" title="Tampilkan di Landing Page">
                                            <i class="fas fa-eye"></i> Tampilkan
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Belum ada data UMKM</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Skrip AJAX untuk Mengubah Status Publikasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event delegation untuk tombol toggle-publish-btn
    document.body.addEventListener('click', function(e) {
        const button = e.target.closest('.toggle-publish-btn');
        if (!button) return;

        e.preventDefault();
        
        const umkmId = button.dataset.id;
        const newStatus = button.dataset.status;
        const actionText = newStatus === '1' ? 'Tampilkan' : 'Sembunyikan';

        // Tampilkan konfirmasi
        if (!confirm('Anda yakin ingin ' + actionText + ' UMKM ini di Landing Page?')) {
            return;
        }

        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        button.disabled = true;

        // Menggunakan Fetch API untuk mengirim permintaan AJAX ke Controller
        fetch('<?= base_url('umkm/togglePublish') ?>/' + umkmId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'is_published': newStatus,
                // Ambil hash CSRF dari input tersembunyi (Asumsi ada di layout utama)
                '<?= csrf_token() ?>': document.querySelector('input[name="<?= csrf_token() ?>"]').value 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Muat ulang halaman untuk menampilkan status yang baru
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
