<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<style>
    /*
     * Gaya Kustom untuk Tampilan Modern & Minimalis
     * Diterapkan untuk meningkatkan pengalaman visual halaman master data.
    */

    /* Latar belakang halaman yang lembut */
    body {
        background-color: #f8f9fa;
    }

    /* Judul halaman yang lebih menonjol */
    .page-title {
        font-weight: 700;
        color: #343a40;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 1rem;
    }

    /* Kustomisasi card */
    .card {
        border: none;
        border-radius: 0.75rem;
        /* Sudut lebih tumpul */
    }

    /* Header tabel yang bersih */
    .table thead th {
        background-color: #f1f3f5;
        /* Warna header abu-abu muda */
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
</style>

<div class="container-fluid py-4">

    <!-- Judul Halaman -->
    <div class="mb-4">
        <h1 class="h3 page-title">Master Jenis Pohon</h1>
        <p class="page-subtitle">Manajemen data untuk semua jenis pohon kopi yang terdaftar.</p>
    </div>

    <div class="row">
        <!-- Kolom Form Tambah -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Jenis Pohon Baru
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('jenispohon/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="nama_jenis" class="font-weight-bold">Nama Jenis Pohon</label>
                            <input type="text" id="nama_jenis" name="nama_jenis" class="form-control" placeholder="Contoh: Arabika Gayo" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Tabel Daftar -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Daftar Jenis Pohon
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Nama Jenis Pohon</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($jenisPohon)): ?>
                                    <?php foreach ($jenisPohon as $i => $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $i + 1 ?></td>
                                            <td><?= esc($row['nama_jenis']) ?></td>
                                            <td class="text-center">
                                                <a href="<?= site_url('jenispohon/delete/' . $row['id']) ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <em>Belum ada data jenis pohon.</em>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>