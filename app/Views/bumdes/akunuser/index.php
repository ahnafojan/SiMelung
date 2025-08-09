<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Manajemen Admin BUMDes</h1>

    <!-- Form Tambah Admin -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah User Admin</h6>
        </div>
        <div class="card-body">
            <form action="#" method="post">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap">
                </div>

                <div class="form-group">
                    <label for="email">Email Admin</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
                </div>

                <div class="form-group">
                    <label>Pilih Role (maksimal 2)</label>
                    <div class="form-check">
                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="keuangan" id="role1">
                        <label class="form-check-label" for="role1">Admin Keuangan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="umkm" id="role2">
                        <label class="form-check-label" for="role2">Admin UMKM</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="pariwisata" id="role3">
                        <label class="form-check-label" for="role3">Admin Pariwisata</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="pengepul" id="role4">
                        <label class="form-check-label" for="role4">Admin Pengepul</label>
                    </div>
                    <small class="form-text text-muted">Maksimal 2 role boleh dipilih.</small>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>


    <!-- Tabel Daftar Admin Dummy -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar User Admin</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data dummy -->
                        <tr>
                            <td>1</td>
                            <td>Ahmad Fauzi</td>
                            <td>fauzi@gmail.com</td>
                            <td>fauzi_admin</td>
                            <td>
                                <span class="badge bg-primary text-white">Keuangan</span>
                                <span class="badge bg-success text-white">UMKM</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Sri Wahyuni</td>
                            <td>sriwahyuni@gmail.com</td>
                            <td>sri_admin</td>
                            <td>
                                <span class="badge bg-primary text-white">Pariwisata</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Bayu Nugroho</td>
                            <td>bayu.nugroho@gmail.com</td>
                            <td>bayu_admin</td>
                            <td>
                                <span class="badge bg-info text-white">UMKM</span>
                                <span class="badge bg-warning text-dark">Pengepul</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </td>
                        </tr>
                        <!-- End dummy -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Batasin jumlah yang bisa dipilih -->
    <script>
        const checkboxes = document.querySelectorAll('.role-checkbox');
        const max = 2;

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                let checked = document.querySelectorAll('.role-checkbox:checked');
                if (checked.length > max) {
                    checkbox.checked = false;
                    alert('Maksimal hanya boleh memilih 2 role.');
                }
            });
        });
    </script>

</div>

<?= $this->endSection() ?>