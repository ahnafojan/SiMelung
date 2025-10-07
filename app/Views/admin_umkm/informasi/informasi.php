<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<style>
    /* Styling untuk pesan notifikasi custom yang muncul di pojok kanan atas */
    #customMessageBox {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1050;
        transition: opacity 0.3s, transform 0.3s;
        opacity: 0;
        transform: translateY(-20px);
        font-weight: 600;
        display: flex;
        align-items: center;
        min-width: 250px;
    }

    #customMessageBox.show {
        opacity: 1;
        transform: translateY(0);
    }

    #customMessageBox.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    #customMessageBox.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Style untuk loading spinner */
    .fa-spin {
        animation: fa-spin 1s infinite linear;
    }

    @keyframes fa-spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(359deg);
        }
    }
</style>

<!-- Tempat Notifikasi Kustom -->
<div id="customMessageBox"></div>

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
                        <th>Status Publikasi</th>
                        <th>Aksi Publikasi</th>
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
                                <td id="status-<?= $u['id'] ?>">
                                    <?php if ($u['is_published'] == 1): ?>
                                        <span class="badge badge-success">Ditampilkan <i class="fas fa-check-circle"></i></span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Disembunyikan <i class="fas fa-times-circle"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td id="actions-<?= $u['id'] ?>">
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
    // Fungsi kustom untuk menampilkan pesan
    function showMessageBox(message, type) {
        const box = document.getElementById('customMessageBox');
        box.textContent = message;
        box.className = '';
        box.classList.add('show', type);
        setTimeout(() => {
            box.classList.remove('show');
        }, 4000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            const button = e.target.closest('.toggle-publish-btn');
            if (!button) return;

            e.preventDefault();
            const umkmId = button.dataset.id;
            const newStatus = button.dataset.status;

            // Ambil CSRF token dari helper CodeIgniter
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';

            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            button.disabled = true;

            fetch('<?= base_url('informasi/togglePublish') ?>/' + umkmId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        'is_published': newStatus,
                        [csrfName]: csrfHash
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error('HTTP ' + response.status + ': ' + (text.substring(0, 100) || 'Server error'));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        showMessageBox('✅ Berhasil: Status UMKM diubah.', 'success');
                        updateButtonAndStatus(umkmId, newStatus);
                    } else {
                        showMessageBox('❌ Gagal: ' + (data.message || 'Terjadi kesalahan.'), 'error');
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    showMessageBox('❌ Error: ' + error.message, 'error');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
        });

        function updateButtonAndStatus(id, status) {
            const statusCell = document.getElementById(`status-${id}`);
            const actionCell = document.getElementById(`actions-${id}`);

            if (statusCell && actionCell) {
                if (status === '1') {
                    statusCell.innerHTML = '<span class="badge badge-success">Ditampilkan <i class="fas fa-check-circle"></i></span>';
                    actionCell.innerHTML = `
                    <button class="btn btn-sm btn-danger toggle-publish-btn" data-id="${id}" data-status="0" title="Sembunyikan dari publik">
                        <i class="fas fa-eye-slash"></i> Sembunyikan
                    </button>`;
                } else {
                    statusCell.innerHTML = '<span class="badge badge-danger">Disembunyikan <i class="fas fa-times-circle"></i></span>';
                    actionCell.innerHTML = `
                    <button class="btn btn-sm btn-success toggle-publish-btn" data-id="${id}" data-status="1" title="Tampilkan di Landing Page">
                        <i class="fas fa-eye"></i> Tampilkan
                    </button>`;
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>