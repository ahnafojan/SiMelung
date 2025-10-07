<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Informasi UMKM Desa Melung</h1>
    <p class="text-muted">
        Kelola UMKM yang akan ditampilkan di pop-up Landing Page.
    </p>

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
                        <th>Status Publikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (!empty($umkm)): ?>
                        <?php $no = 1;
                        foreach ($umkm as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($u['nama_umkm']) ?></td>
                                <td><?= esc($u['pemilik']) ?></td>
                                <td><?= esc($u['alamat']) ?></td>
                                <td>
                                    <?php if (!empty($u['foto_umkm'])): ?>
                                        <img src="<?= base_url('uploads/foto_umkm/' . esc($u['foto_umkm'])) ?>"
                                            width="80" height="80" style="object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        <span class="text-muted">–</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u['is_published']): ?>
                                        <span class="badge badge-success">Ditampilkan</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Disembunyikan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u['is_published']): ?>
                                        <button class="btn btn-sm btn-danger toggle-publish-btn"
                                            data-id="<?= (int)$u['id'] ?>" data-status="0">
                                            <i class="fas fa-eye-slash"></i> Sembunyikan
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-success toggle-publish-btn"
                                            data-id="<?= (int)$u['id'] ?>" data-status="1">
                                            <i class="fas fa-eye"></i> Tampilkan
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Tidak ada data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ✅ WAJIB: CSRF Field -->
<?= csrf_field() ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-publish-btn');
            if (!btn) return;

            const id = btn.dataset.id;
            const status = btn.dataset.status;
            const action = status === '1' ? 'Tampilkan' : 'Sembunyikan';

            if (!confirm(`Yakin ingin ${action} UMKM ini?`)) return;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            const formData = new FormData();
            formData.append('is_published', status);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= site_url('informasi/togglePublish/') ?>' + id, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // TIDAK PERLU RELOAD - Update UI langsung
                        updateRowStatus(id, status);

                        // Tampilkan notifikasi sukses
                        showNotification('success', data.message);
                    } else {
                        showNotification('error', data.message);
                        btn.disabled = false;
                        btn.innerHTML = `<i class="fas fa-${status === '1' ? 'eye' : 'eye-slash'}"></i> ${action}`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification('error', 'Kesalahan koneksi');
                    btn.disabled = false;
                    btn.innerHTML = `<i class="fas fa-${status === '1' ? 'eye' : 'eye-slash'}"></i> ${action}`;
                });
        });
    });

    // Fungsi untuk update status di tabel tanpa reload
    function updateRowStatus(id, newStatus) {
        const row = document.querySelector(`[data-id="${id}"]`).closest('tr');
        const statusCell = row.querySelector('td:nth-child(6)'); // Kolom status
        const actionCell = row.querySelector('td:nth-child(7)'); // Kolom aksi

        if (newStatus == '1') {
            // Jika di-publish
            statusCell.innerHTML = '<span class="badge badge-success">Ditampilkan</span>';
            actionCell.innerHTML = `
                <button class="btn btn-sm btn-danger toggle-publish-btn" 
                        data-id="${id}" data-status="0">
                    <i class="fas fa-eye-slash"></i> Sembunyikan
                </button>
            `;
        } else {
            // Jika disembunyikan
            statusCell.innerHTML = '<span class="badge badge-danger">Disembunyikan</span>';
            actionCell.innerHTML = `
                <button class="btn btn-sm btn-success toggle-publish-btn" 
                        data-id="${id}" data-status="1">
                    <i class="fas fa-eye"></i> Tampilkan
                </button>
            `;
        }
    }

    // Fungsi untuk menampilkan notifikasi
    function showNotification(type, message) {
        // Hapus notifikasi sebelumnya jika ada
        const existingAlert = document.querySelector('.notification-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        // Buat elemen alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show notification-alert`;
        alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <strong>${type === 'error' ? 'Error!' : 'Sukses!'}</strong> ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        `;

        document.body.appendChild(alertDiv);

        // Auto remove setelah 3 detik
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }
</script>

<?= $this->endSection() ?>