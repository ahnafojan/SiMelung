<?= $this->extend('layouts/main_layout_admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Manajemen Admin BUMDes</h1>

    <!-- Tampilkan pesan sukses -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Form Tambah Admin -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah User Admin</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('admin-user/create') ?>" method="post">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
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
                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="komersial" id="role4">
                        <label class="form-check-label" for="role4">Admin Komersial</label>
                    </div>
                    <small class="form-text text-muted">Maksimal 2 role boleh dipilih.</small>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Admin -->
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
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($user['username']) ?></td>
                                    <td>
                                        <?php
                                        $roles = explode(',', $user['roles']);
                                        foreach ($roles as $role) {
                                            $color = 'secondary';
                                            if ($role === 'keuangan') $color = 'primary';
                                            elseif ($role === 'umkm') $color = 'success';
                                            elseif ($role === 'pariwisata') $color = 'info';
                                            elseif ($role === 'pengepul') $color = 'warning text-dark';
                                            echo "<span class='badge bg-$color'>" . esc(ucfirst($role)) . "</span> ";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-warning btn-edit"
                                            data-id="<?= esc($user['id']) ?>"
                                            data-username="<?= esc($user['username']) ?>"
                                            data-roles="<?= esc($user['roles']) ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <button
                                            class="btn btn-sm btn-danger btn-delete"
                                            data-id="<?= esc($user['id']) ?>"
                                            data-username="<?= esc($user['username']) ?>">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data user admin.</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>

                <!-- Modal Edit User -->
                <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="editUserForm" method="post" action="<?= site_url('admin-user/edit') ?>">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserLabel">Edit User Admin</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" id="editUserId" value="">

                                    <div class="form-group">
                                        <label for="editUsername">Username</label>
                                        <input type="text" class="form-control" id="editUsername" name="username" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="editPassword">Password Baru (kosongkan jika tidak diubah)</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="editPassword" name="password" placeholder="Password baru">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="fa fa-eye"></i> <!-- Icon lihat -->
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Pilih Role (maksimal 2)</label>
                                        <div class="form-check">
                                            <input class="form-check-input edit-role-checkbox" type="checkbox" name="roles[]" value="keuangan" id="editRoleKeuangan">
                                            <label class="form-check-label" for="editRoleKeuangan">Admin Keuangan</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input edit-role-checkbox" type="checkbox" name="roles[]" value="umkm" id="editRoleUmkm">
                                            <label class="form-check-label" for="editRoleUmkm">Admin UMKM</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input edit-role-checkbox" type="checkbox" name="roles[]" value="pariwisata" id="editRolePariwisata">
                                            <label class="form-check-label" for="editRolePariwisata">Admin Pariwisata</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input edit-role-checkbox" type="checkbox" name="roles[]" value="pengepul" id="editRolePengepul">
                                            <label class="form-check-label" for="editRolePengepul">Admin Pengepul</label>
                                        </div>
                                        <small class="form-text text-muted">Maksimal 2 role boleh dipilih.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Hapus User -->
                <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="deleteUserForm" method="post" action="<?= site_url('admin-user/delete') ?>">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserLabel">Hapus User Admin</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" id="deleteUserId" value="">
                                    <p>Apakah Anda yakin ingin menghapus user <strong id="deleteUsername"></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Font Awesome (untuk icon mata) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        $(document).ready(function() {
            $("#togglePassword").on("click", function() {
                let input = $("#editPassword");
                let icon = $(this).find("i");

                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash"); // Ubah jadi icon sembunyi
                } else {
                    input.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye"); // Ubah jadi icon lihat
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Batasi maksimal 2 role checkbox di modal edit
            $('.edit-role-checkbox').on('change', function() {
                if ($('.edit-role-checkbox:checked').length > 2) {
                    this.checked = false;
                    alert('Maksimal hanya boleh memilih 2 role.');
                }
            });

            // Tombol Edit diklik -> tampilkan data di modal edit
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                var username = $(this).data('username');
                var roles = $(this).data('roles').split(',');

                $('#editUserId').val(id);
                $('#editUsername').val(username);
                $('#editPassword').val('');

                // Reset semua checkbox dulu
                $('.edit-role-checkbox').prop('checked', false);
                // Centang role yang sesuai
                roles.forEach(function(role) {
                    $('#editRole' + capitalizeFirstLetter(role.trim())).prop('checked', true);
                });

                $('#editUserModal').modal('show');
            });

            // Tombol Hapus diklik -> tampilkan data di modal hapus
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var username = $(this).data('username');

                $('#deleteUserId').val(id);
                $('#deleteUsername').text(username);

                $('#deleteUserModal').modal('show');
            });

            // Fungsi bantu untuk capitalize huruf pertama
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>



    <!-- Batasin jumlah role yang dipilih -->
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