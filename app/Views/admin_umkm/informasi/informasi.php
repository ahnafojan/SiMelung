<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<style>
Styling untuk pesan notifikasi custom yang muncul di pojok kanan atas /
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
/ Style untuk loading spinner */
.fa-spin {
animation: fa-spin 1s infinite linear;
}
@keyframes fa-spin {
0% { transform: rotate(0deg); }
100% { transform: rotate(359deg); }
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
                            
                            <!-- Status Publikasi. Beri ID unik untuk pembaruan dinamis -->
                            <td id="status-<?= $u['id'] ?>">
                                <?php if ($u['is_published'] == 1): ?>
                                    <span class="badge badge-success">Ditampilkan <i class="fas fa-check-circle"></i></span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Disembunyikan <i class="fas fa-times-circle"></i></span>
                                <?php endif; ?>
                            </td>

                            <!-- Tombol Aksi Publikasi. Beri ID unik untuk pembaruan dinamis -->
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

<!-- Input tersembunyi untuk CSRF token. Pastikan variabel ini dikirim dari controller -->

<input type="hidden" id="csrfToken" name="<?= isset($csrf_token) ? $csrf_token : csrf_token() ?>" value="<?= isset($csrf_hash) ? $csrf_hash : csrf_hash() ?>">

<!-- Skrip AJAX untuk Mengubah Status Publikasi -->

<script>
// Fungsi kustom untuk menampilkan pesan (menggantikan alert/confirm)
function showMessageBox(message, type) {
const box = document.getElementById('customMessageBox');
box.innerHTML = message;
box.className = ''; // Reset classes
box.classList.add('show', type);
setTimeout(() => {
box.classList.remove('show');
}, 4000); // Pesan hilang setelah 4 detik
}

document.addEventListener('DOMContentLoaded', function() {

// Event delegation untuk tombol toggle-publish-btn
document.body.addEventListener(&#39;click&#39;, function(e) {
    // Cari tombol terdekat yang memiliki class &#39;toggle-publish-btn&#39;
    const button = e.target.closest(&#39;.toggle-publish-btn&#39;);
    if (!button) return;

    e.preventDefault();
    
    const umkmId = button.dataset.id;
    const newStatus = button.dataset.status;

    // Ambil data CSRF dari input tersembunyi
    const csrfInput = document.getElementById(&#39;csrfToken&#39;);
    if (!csrfInput) {
        showMessageBox(&#39;❌ CSRF Token tidak ditemukan.&#39;, &#39;error&#39;);
        return;
    }

    const csrfTokenName = csrfInput.name;
    const csrfTokenValue = csrfInput.value;

    const originalContent = button.innerHTML;
    
    // Tampilkan loading state
    button.innerHTML = &#39;&lt;i class=&quot;fas fa-spinner fa-spin&quot;&gt;&lt;/i&gt; Loading...&#39;;
    button.disabled = true;

    // Menggunakan Fetch API untuk mengirim permintaan AJAX ke Controller
    // PATH DIUBAH menjadi &#39;informasi/togglePublish&#39;
    fetch(&#39;&lt;?= base_url(&#39;informasi/togglePublish&#39;) ?&gt;/&#39; + umkmId, {
        method: &#39;POST&#39;,
        headers: {
            &#39;Content-Type&#39;: &#39;application/x-www-form-urlencoded&#39;,
            &#39;X-Requested-With&#39;: &#39;XMLHttpRequest&#39;
        },
        body: new URLSearchParams({
            &#39;is_published&#39;: newStatus,
            [csrfTokenName]: csrfTokenValue // Mengirim token CSRF yang benar
        })
    })
    .then(response =&gt; {
        // Periksa jika respons HTTP gagal (404, 500, dll)
        if (!response.ok) {
            // Mencoba membaca respons teks jika terjadi error HTTP
            return response.text().then(text =&gt; { throw new Error(&#39;Server error: &#39; + response.status + &#39; - &#39; + text.substring(0, 100)); });
        }
        return response.json();
    })
    .then(data =&gt; {
        if (data.status === &#39;success&#39;) {
            showMessageBox(&#39;✅ Berhasil: Status UMKM diubah.&#39;, &#39;success&#39;);
            
            // Panggil fungsi untuk memperbarui tampilan tanpa reload
            updateButtonAndStatus(umkmId, newStatus);
            
        } else {
            showMessageBox(&#39;❌ Gagal: &#39; + data.message, &#39;error&#39;);
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    })
    .catch(error =&gt; {
        console.error(&#39;Error:&#39;, error);
        // Menampilkan error yang lebih spesifik
        showMessageBox(&#39;❌ Terjadi kesalahan: &#39; + error.message, &#39;error&#39;);
        button.innerHTML = originalContent;
        button.disabled = false;
    });
});

// Fungsi untuk memperbarui tampilan tombol dan status tanpa reload
function updateButtonAndStatus(id, status) {
    const statusCell = document.getElementById(`status-${id}`);
    const actionCell = document.getElementById(`actions-${id}`);

    if (statusCell &amp;&amp; actionCell) {
        if (status === &#39;1&#39;) {
            // Update Status Cell: Ditampilkan
            statusCell.innerHTML = &#39;&lt;span class=&quot;badge badge-success&quot;&gt;Ditampilkan &lt;i class=&quot;fas fa-check-circle&quot;&gt;&lt;/i&gt;&lt;/span&gt;&#39;;
            // Update Action Cell: Ganti ke tombol Sembunyikan
            actionCell.innerHTML = `
                &lt;button class=&quot;btn btn-sm btn-danger toggle-publish-btn&quot; data-id=&quot;${id}&quot; data-status=&quot;0&quot; title=&quot;Sembunyikan dari publik&quot;&gt;
                    &lt;i class=&quot;fas fa-eye-slash&quot;&gt;&lt;/i&gt; Sembunyikan
                &lt;/button&gt;`;
        } else {
            // Update Status Cell: Disembunyikan
            statusCell.innerHTML = &#39;&lt;span class=&quot;badge badge-danger&quot;&gt;Disembunyikan &lt;i class=&quot;fas fa-times-circle&quot;&gt;&lt;/i&gt;&lt;/span&gt;&#39;;
            // Update Action Cell: Ganti ke tombol Tampilkan
            actionCell.innerHTML = `
                &lt;button class=&quot;btn btn-sm btn-success toggle-publish-btn&quot; data-id=&quot;${id}&quot; data-status=&quot;1&quot; title=&quot;Tampilkan di Landing Page&quot;&gt;
                    &lt;i class=&quot;fas fa-eye&quot;&gt;&lt;/i&gt; Tampilkan
                &lt;/button&gt;`;
        }
    }
}

});
</script>

<?= $this->endSection() ?>