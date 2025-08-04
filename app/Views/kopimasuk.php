<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kopi Masuk</h1>
    <p>Fungsi: Mencatat kopi yang disetor oleh petani ke BUMDes.</p>

    <!-- Tombol Tambah Data -->
    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKopi">
            <i class="fas fa-plus"></i> Tambah Data Kopi Masuk
        </button>
    </div>

    <!-- Modal Form Tambah Data -->
    <div class="modal fade" id="modalTambahKopi" tabindex="-1" role="dialog" aria-labelledby="modalTambahKopiLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahKopiLabel">Form Tambah Kopi Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formKopiMasuk">
                        <div class="form-group">
                            <label for="namaPetani">Nama Petani</label>
                            <select id="namaPetani" class="form-control" required>
                                <option value="" disabled selected>Pilih Petani</option>
                                <option value="Pak Ahmad">Pak Ahmad</option>
                                <option value="Bu Sari">Bu Sari</option>
                                <option value="Pak Budi">Pak Budi</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah (Kg)</label>
                            <input type="number" id="jumlah" class="form-control" placeholder="Masukkan jumlah kopi" min="1" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal">Tanggal Setor</label>
                            <input type="date" id="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea id="keterangan" class="form-control" rows="2" placeholder="Contoh: Panen awal bulan"></textarea>
                        </div>

                        <div class="modal-footer px-0">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Dummy Data Kopi Masuk -->
    <div class="card shadow mt-4">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Nama Petani</th>
                        <th>Jumlah (Kg)</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td>1</td>
                        <td>Pak Ahmad</td>
                        <td>50</td>
                        <td>2025-08-01</td>
                        <td>Panen awal bulan</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditKopi1"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopi1"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Bu Sari</td>
                        <td>30</td>
                        <td>2025-07-30</td>
                        <td>Kopi arabika kualitas tinggi</td>
                        <td>
                            <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal Edit Kopi Masuk -->
<div class="modal fade" id="modalEditKopi1" tabindex="-1" role="dialog" aria-labelledby="modalEditKopi1Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form>
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditKopi1Label">Edit Data Kopi Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form seperti Tambah -->
                    <div class="form-group">
                        <label>Nama Petani</label>
                        <input type="text" class="form-control" value="Pak Ahmad" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah (Kg)</label>
                        <input type="number" class="form-control" value="50" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" value="2025-08-01" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" rows="2">Panen awal bulan</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Kopi Masuk -->
<div class="modal fade" id="modalHapusKopi1" tabindex="-1" role="dialog" aria-labelledby="modalHapusKopi1Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form>
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalHapusKopi1Label">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data kopi masuk dari <strong>Pak Ahmad</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script Simpan Dummy -->
<script>
    $('#formKopiMasuk').on('submit', function(e) {
        e.preventDefault();
        alert('Form berhasil dikirim! (Belum terkoneksi ke backend)');
        $('#formKopiMasuk')[0].reset();
        $('#modalTambahKopi').modal('hide');
    });
</script>

<?= $this->endSection() ?>