<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 page-title">Kopi Keluar</h1>
            <p class="mb-0 page-subtitle">Fungsi: Mencatat kopi keluar (penjualan/penyerahan).</p>
        </div>
        <div>
            <span class="badge badge-info p-2">Sisa Stok Semua Kopi: <b><?= number_format($stok, 2, ',', '.') ?> Kg</b></span>
        </div>
    </div>

    <!-- Tombol Tambah Data -->
    <div class="mb-3">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKopiKeluar">
            <i class="fas fa-plus"></i> Tambah Data Kopi Keluar
        </button>
    </div>

    <!-- Modal Form Tambah Data -->
    <div class="modal fade" id="modalTambahKopiKeluar" tabindex="-1" role="dialog" aria-labelledby="modalTambahKopiKeluarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url('kopikeluar/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-content shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTambahKopiKeluarLabel">Form Tambah Kopi Keluar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="stok_kopi_id">Pilih Jenis Kopi</label>
                            <select id="stok_kopi_id" name="stok_kopi_id" class="form-control" required>
                                <option value="">-- Pilih Jenis Kopi --</option>
                                <?php foreach ($stokKopi as $s): ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= esc($s['nama_pohon']) ?> -
                                        Stok: <?= isset($s['total_stok']) ? number_format($s['total_stok'], 2, ',', '.') : '0,00' ?> Kg
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tujuan">Tujuan</label>
                            <input type="text" id="tujuan" name="tujuan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah (Kg)</label>
                            <input type="number" id="jumlah" name="jumlah" step="0.01" class="form-control" min="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal Keluar</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form Edit Data -->
    <div class="modal fade" id="modalEditKopiKeluar" tabindex="-1" role="dialog" aria-labelledby="modalEditKopiKeluarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditKopiKeluar" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-content shadow">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalEditKopiKeluarLabel">Form Edit Kopi Keluar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_stok_kopi_id">Pilih Jenis Kopi</label>
                            <select id="edit_stok_kopi_id" name="stok_kopi_id" class="form-control" required>
                                <option value="">-- Pilih Jenis Kopi --</option>
                                <?php foreach ($stokKopi as $s): ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= esc($s['nama_pohon']) ?> -
                                        Stok: <?= isset($s['total_stok']) ? number_format($s['total_stok'], 2, ',', '.') : '0,00' ?> Kg
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_tujuan">Tujuan</label>
                            <input type="text" id="edit_tujuan" name="tujuan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jumlah">Jumlah (Kg)</label>
                            <input type="number" id="edit_jumlah" name="jumlah" step="0.01" class="form-control" min="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_tanggal">Tanggal Keluar</label>
                            <input type="date" id="edit_tanggal" name="tanggal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_keterangan">Keterangan</label>
                            <textarea id="edit_keterangan" name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Tabel Data Kopi Keluar -->
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Jenis Kopi</th>
                        <th>Tujuan</th>
                        <th>Jumlah (Kg)</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Sisa Stok Jenis Kopi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kopikeluar)): ?>
                        <?php foreach ($kopikeluar as $index => $k): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($k['nama_pohon'] ?? '-') ?></td>
                                <td><?= esc($k['tujuan']) ?></td>
                                <td class="text-right"><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</td>
                                <td><?= date('d-m-Y', strtotime($k['tanggal'])) ?></td>
                                <td><?= esc($k['keterangan']) ?></td>
                                <td class="text-right">
                                    <?= isset($k['sisa_stok_jenis']) ? number_format($k['sisa_stok_jenis'], 2, ',', '.') . ' Kg' : '-' ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <!-- Tombol Edit Dinamis -->
                                        <?php if ($k['can_edit']): ?>
                                            <button class="btn btn-sm btn-warning btn-edit" data-id="<?= $k['id'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-warning btn-request-access"
                                                data-kopikeluar-id="<?= $k['id'] ?>"
                                                data-action-type="edit" title="Minta Izin Edit">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Tombol Hapus Dinamis -->
                                        <?php if ($k['can_delete']): ?>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusKopiKeluar<?= $k['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-danger btn-request-access"
                                                data-kopikeluar-id="<?= $k['id'] ?>"
                                                data-action-type="delete" title="Minta Izin Hapus">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Hapus Kopi Keluar -->
                            <div class="modal fade" id="modalHapusKopiKeluar<?= $k['id'] ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('kopikeluar/delete/' . $k['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus data ini?</p>
                                                <p>
                                                    Jenis Kopi: <strong><?= esc($k['nama_pohon']) ?></strong><br>
                                                    Jumlah: <strong><?= number_format($k['jumlah'], 2, ',', '.') ?> Kg</strong>
                                                </p>
                                                <p class="text-danger font-weight-bold">Tindakan ini akan mengembalikan jumlah stok kopi.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data kopi keluar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- =============================================================================== -->
<!-- GANTI BLOK SCRIPT LAMA ANDA DENGAN YANG INI -->
<!-- Letakkan skrip ini di tempat yang sama seperti sebelumnya (di dalam HTML, sebelum endSection) -->
<!-- =============================================================================== -->
<script>
    $(document).ready(function() {

        // --- BLOK BARU UNTUK MEMPERBAIKI TOMBOL BATAL & X ---
        // Kita targetkan semua elemen yang punya atribut data-dismiss="modal"
        // di dalam modal edit.
        $('#modalEditKopiKeluar').on('click', '[data-dismiss="modal"]', function() {
            // Ketika salah satunya diklik, kita panggil fungsi 'hide' secara manual.
            $('#modalEditKopiKeluar').modal('hide');
        });
        // ----------------------------------------------------


        // Event handler untuk tombol edit (kode ini tetap sama)
        $('.btn-edit').on('click', function() {
            const id = $(this).data('id'); // Ambil ID dari tombol yang diklik

            // Lakukan request AJAX untuk mendapatkan data
            $.ajax({
                url: `<?= base_url('kopikeluar/edit') ?>/${id}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        // Isi nilai-nilai form di dalam modal edit
                        $('#edit_id').val(data.id);
                        $('#edit_stok_kopi_id').val(data.stok_kopi_id);
                        $('#edit_tujuan').val(data.tujuan);
                        $('#edit_jumlah').val(data.jumlah);
                        $('#edit_tanggal').val(data.tanggal);
                        $('#edit_keterangan').val(data.keterangan);

                        // Atur action form agar mengarah ke URL update yang benar
                        $('#formEditKopiKeluar').attr('action', `<?= base_url('kopikeluar/update') ?>/${id}`);

                        // Tampilkan modal edit
                        $('#modalEditKopiKeluar').modal('show');
                    } else {
                        alert('Data tidak ditemukan.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Response:", xhr.responseText);
                    alert('Gagal mengambil data untuk diedit. Silakan periksa konsol browser untuk detail.');
                }
            });
        });
    });
    $('.btn-request-access').on('click', function() {
        const button = $(this);
        const kopiKeluarId = button.data('kopikeluar-id');
        const action = button.data('action-type');

        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "<?= site_url('kopikeluar/requestAccess') ?>",
            method: "POST",
            data: {
                kopikeluar_id: kopiKeluarId,
                action_type: action,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                    button.removeClass('btn-outline-warning btn-outline-danger').addClass('btn-secondary disabled')
                        .html('<i class="fas fa-clock"></i>');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                    button.prop('disabled', false).html('<i class="fas fa-lock"></i>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan koneksi.'
                });
                button.prop('disabled', false).html('<i class="fas fa-lock"></i>');
            }
        });
    });
</script>
<?= $this->endSection() ?>