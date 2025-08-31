<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Tambah Master Aset</h1>
            <p class="mb-0 page-subtitle text-muted">Mencatat aset BUMDes untuk mendukung kegiatan komersial.</p>
        </div>
        <a href="<?= base_url('ManajemenAsetKomersial') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-list-ul me-1"></i> Lihat Daftar Aset
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="<?= site_url('aset-komersial') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h6 class="mb-0 text-dark"><i class="fas fa-info-circle me-2"></i> Informasi Dasar Aset</h6>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="kategori_aset" class="form-label">Kategori Barang / Aset <span class="text-danger">*</span></label>
                                    <select name="kategori_aset" id="kategori_aset" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($kategoriAset as $kategori) : ?>
                                            <option value="<?= esc($kategori) ?>"><?= esc($kategori) ?></option>
                                        <?php endforeach; ?>
                                        <option value="Lainnya">Lainnya...</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="container_nama_aset_lainnya" style="display: none;">
                                    <label for="nama_aset_lainnya" class="form-label">Nama Aset Lainnya <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_aset_lainnya" id="nama_aset_lainnya" class="form-control" placeholder="Tuliskan nama aset spesifik di sini">
                                </div>

                                <div class="mb-3">
                                    <label for="kode_aset" class="form-label">Kode Aset <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_aset" id="kode_aset" class="form-control" placeholder="Contoh: AST-001" required>
                                </div>

                                <div class="mb-3">
                                    <label for="nup" class="form-label">Nomor Urut Pendaftaran (NUP)</label>
                                    <input type="text" name="nup" id="nup" class="form-control" placeholder="Contoh: 001">
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="tahun_perolehan" class="form-label">Tahun Perolehan</label>
                                        <input type="number" name="tahun_perolehan" id="tahun_perolehan" class="form-control" placeholder="Contoh: 2024">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="merk_type" class="form-label">Merk / Tipe</label>
                                        <input type="text" name="merk_type" id="merk_type" class="form-control" placeholder="Contoh: Philips X100">
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="nilai_perolehan" class="form-label">Nilai Perolehan (Rp)</label>
                                    <input type="number" name="nilai_perolehan" id="nilai_perolehan" class="form-control" placeholder="Contoh: 15000000">
                                </div>

                                <div class="mb-0">
                                    <label for="keterangan" class="form-label">Kondisi Aset <span class="text-danger">*</span></label>
                                    <select name="keterangan" id="keterangan" class="form-select" required>
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Perlu Perawatan">Perlu Perawatan</option>
                                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                                        <option value="Rusak">Rusak</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h6 class="mb-0 text-dark"><i class="fas fa-handshake me-2"></i> Detail Pengadaan & Bukti</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="metode_pengadaan" class="form-label">Metode Pengadaan <span class="text-danger">*</span></label>
                                    <select name="metode_pengadaan" id="metode_pengadaan" class="form-select" required>
                                        <option value="">-- Pilih Jenis Pengadaan --</option>
                                        <option value="Hibah">Hibah</option>
                                        <option value="Pembelian">Pembelian</option>
                                        <option value="Penyewaan">Penyewaan</option>
                                        <option value="Peminjaman">Peminjaman</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="sumber_pengadaan" class="form-label">Sumber Pengadaan <span class="text-danger">*</span></label>
                                    <input type="text" name="sumber_pengadaan" id="sumber_pengadaan" class="form-control" placeholder="Contoh: Universitas Amikom Purwokerto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Upload Foto Aset <span class="text-danger">*</span></label>
                                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*" required>
                                    <small class="text-muted mt-2">Format: JPG/PNG. Ukuran maks: 2MB.</small>
                                    <div id="image-preview-container" class="mt-3" style="display: none;">
                                        <img id="image-preview" src="#" alt="Pratinjau Foto Aset" class="img-fluid rounded shadow-sm border border-2" style="max-height: 200px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary btn-md px-4">
                        <i class="fas fa-save me-2"></i> Simpan Data Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Skrip untuk menampilkan input "Lainnya" pada Kategori Aset
        const kategoriSelect = document.getElementById('kategori_aset');
        const lainnyaContainer = document.getElementById('container_nama_aset_lainnya');
        const lainnyaInput = document.getElementById('nama_aset_lainnya');

        kategoriSelect.addEventListener('change', function() {
            if (this.value === 'Lainnya') {
                lainnyaContainer.style.display = 'block';
                lainnyaInput.setAttribute('required', 'required');
            } else {
                lainnyaContainer.style.display = 'none';
                lainnyaInput.removeAttribute('required');
                lainnyaInput.value = '';
            }
        });

        // Skrip untuk preview gambar
        const inputFile = document.getElementById('foto');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');

        inputFile.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.style.display = 'none';
                previewImage.src = '#';
            }
        });
    });
</script>

<?= $this->endSection() ?>