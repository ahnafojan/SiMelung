<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 page-title">Laporan UMKM</h1>
            <p class="mb-0 page-subtitle">Detail Rekap UMKM desa Melung yang terdaftar.</p>
        </div>
    </div>

    <!-- Bagian Preview Data (Sudah direvisi untuk menampilkan data real) -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview Data UMKM</h6>
        </div>
        <div class="card-body">
            <p class="small text-muted">Berikut adalah daftar data UMKM yang akan di-export:</p>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama UMKM</th>
                            <th>Pemilik</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($umkmData)): // DIREVISI: Menggunakan $umkmData 
                        ?>
                            <tr>
                                <td colspan="5" class="text-center text-danger">Tidak ada data UMKM ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach ($umkmData as $data): // DIREVISI: Menggunakan $umkmData 
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= esc($data['nama_umkm']) ?></td>
                                    <td><?= esc($data['pemilik']) ?></td>
                                    <td><?= esc($data['alamat']) ?></td>
                                    <td><?= esc($data['kontak']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>