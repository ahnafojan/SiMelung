<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Manajemen Aset Desa Melung</h1>

    <!-- Form Tambah Aset -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Aset Baru</h6>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group">
                    <label>Kategori Aset</label>
                    <select class="form-control">
                        <option>Mesin</option>
                        <option>Bangunan</option>
                        <option>Kendaraan</option>
                        <option>Peralatan</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Aset</label>
                    <input type="text" class="form-control" placeholder="Masukkan nama aset">
                </div>
                <div class="form-group">
                    <label>Kode Aset</label>
                    <input type="text" class="form-control" placeholder="Masukkan kode aset">
                </div>
                <div class="form-group">
                    <label>Tanggal Perolehan</label>
                    <input type="date" class="form-control">
                </div>
                <div class="form-group">
                    <label>Nilai Perolehan</label>
                    <input type="text" class="form-control" id="nilaiPerolehan" placeholder="Masukkan nilai, contoh: 1000000">
                </div>
                <div class="form-group">
                    <label>Kondisi</label>
                    <select class="form-control">
                        <option>Baik</option>
                        <option>Rusak Ringan</option>
                        <option>Rusak Berat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <select class="form-control">
                        <option>Balai Desa</option>
                        <option>Pagubugan</option>
                        <option>Rumah Singgah Maria</option>
                        <option>BUMDES</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status Aset</label>
                    <select class="form-control">
                        <option>Aktif</option>
                        <option>Rusak</option>
                        <option>Dipinjam</option>
                        <option>Dijual</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Foto Aset</label>
                    <input type="file" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Aset -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Aset</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Nama Aset</th>
                            <th>Kode</th>
                            <th>Nilai</th>
                            <th>Kondisi</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Mesin</td>
                            <td>Mesin Giling</td>
                            <td>KOPI001</td>
                            <td>Rp 5.000.000</td>
                            <td>Baik</td>
                            <td>Balai Desa</td>
                            <td>Aktif</td>
                            <td><img src="<?= base_url('assets/img/sample.jpg') ?>" width="80"></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editAsetModal" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusAsetModal" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>

                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Peralatan</td>
                            <td>Alat Sangrai</td>
                            <td>KOPI002</td>
                            <td>Rp 3.500.000</td>
                            <td>Rusak Ringan</td>
                            <td>Pagubugan</td>
                            <td>Dipinjam</td>
                            <td><img src="<?= base_url('assets/img/sample2.jpg') ?>" width="80"></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editAsetModal" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusAsetModal" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal Edit Aset -->
<div class="modal fade" id="editAsetModal" tabindex="-1" role="dialog" aria-labelledby="editAsetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Aset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Kategori Aset</label>
                        <select class="form-control" name="kategori">
                            <option>Mesin</option>
                            <option>Bangunan</option>
                            <option>Kendaraan</option>
                            <option>Peralatan</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nama Aset</label>
                        <input type="text" class="form-control" name="nama_aset">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Kode Aset</label>
                        <input type="text" class="form-control" name="kode_aset">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tanggal Perolehan</label>
                        <input type="date" class="form-control" name="tgl_perolehan">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nilai Perolehan</label>
                        <input type="text" class="form-control" id="editNilaiPerolehan" name="nilai">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Kondisi</label>
                        <select class="form-control" name="kondisi">
                            <option>Baik</option>
                            <option>Rusak Ringan</option>
                            <option>Rusak Berat</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Lokasi</label>
                        <select class="form-control" name="lokasi">
                            <option>Balai Desa</option>
                            <option>Pagubugan</option>
                            <option>Rumah Singgah Maria</option>
                            <option>BUMDES</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option>Aktif</option>
                            <option>Rusak</option>
                            <option>Dipinjam</option>
                            <option>Dijual</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Foto Aset</label>
                        <input type="file" class="form-control-file" name="foto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Aset -->
<div class="modal fade" id="hapusAsetModal" tabindex="-1" role="dialog" aria-labelledby="hapusAsetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="#" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus aset ini?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script Format Rupiah -->
<script>
    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix + rupiah;
    }

    document.getElementById('nilaiPerolehan').addEventListener('input', function(e) {
        let angka = this.value.replace(/[^0-9]/g, '');
        if (angka) {
            this.value = formatRupiah(angka, 'Rp ');
        } else {
            this.value = '';
        }
    });

    document.getElementById('editNilaiPerolehan').addEventListener('input', function(e) {
        let angka = this.value.replace(/[^0-9]/g, '');
        if (angka) {
            this.value = formatRupiah(angka, 'Rp ');
        } else {
            this.value = '';
        }
    });
</script>

<?= $this->endSection() ?>