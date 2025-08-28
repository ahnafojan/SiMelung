<?php
// Definisikan daftar role sekali saja di sini untuk menghindari duplikasi
$roles_list = ['keuangan', 'umkm', 'pariwisata', 'komersial'];
?>

<?= $this->extend('layouts/main_layout_admin') ?>

<?= $this->section('content') ?>

<style>
    :root {
        --primary-color: #4e73df;
        --success-color: #1cc88a;
        --danger-color: #e74a3b;
        --warning-color: #f6c23e;
        --info-color: #36b9cc;
        --secondary-text: #858796;
        --card-bg: #ffffff;
        --border-color: #e3e6f0;
        --body-bg: #f8f9fc;
    }

    /* === Page Header === */
    .page-title {
        color: #3a3b45;
        font-weight: 700;
    }

    .page-subtitle {
        color: var(--secondary-text);
        font-size: 0.9rem;
    }

    /* === Card Styling === */
    .custom-card {
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .custom-card .card-header {
        background-color: var(--card-bg);
        border-bottom: 1px solid var(--border-color);
        font-weight: 600;
        color: var(--primary-color);
    }

    /* === Form Enhancements === */
    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #5a5c69;
    }

    /* Custom Role Selector: Menggunakan checkbox tersembunyi untuk fungsionalitas */
    .role-selector-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .role-selector {
        position: relative;
    }

    .role-selector input[type="checkbox"] {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .role-selector label {
        display: block;
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 50px;
        /* Pill shape */
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        margin-bottom: 0;
    }

    .role-selector input[type="checkbox"]:checked+label {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 2px 5px rgba(78, 115, 223, 0.4);
    }

    .role-selector input[type="checkbox"]:disabled+label {
        background-color: #f8f9fc;
        color: #b8b8b8;
        cursor: not-allowed;
    }

    .role-selector:hover label:not([disabled]) {
        border-color: var(--primary-color);
    }

    /* === Table Enhancements === */
    .table-custom thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        background-color: var(--body-bg);
        border: none;
    }

    .table-custom tbody tr {
        background-color: var(--card-bg);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .table-custom tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .table-custom td,
    .table-custom th {
        border: none;
        padding: 1rem;
        vertical-align: middle;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        font-size: 1rem;
    }

    .role-badge {
        padding: 0.4em 0.8em;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 50px;
        color: white;
    }

    .role-keuangan {
        background-color: var(--primary-color);
    }

    .role-umkm {
        background-color: var(--success-color);
    }

    .role-pariwisata {
        background-color: var(--info-color);
    }

    .role-komersial {
        background-color: var(--warning-color);
        color: #3a3b45;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #e0e0e0;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #5a5c69;
    }
</style>

<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3 page-title">Manajemen User Admin BUMDes</h1>
        <p class="page-subtitle">Tambah, edit, dan hapus akun admin untuk berbagai divisi.</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">

        <div class="col-lg-5 mb-4">
            <div class="card custom-card">
                <div class="card-header py-3">
                    <i class="fas fa-user-plus mr-2"></i> Form Tambah User Admin
                </div>
                <div class="card-body">
                    <form id="addUserForm" action="<?= site_url('admin-user/create') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="cth: admin.keuangan" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"><i class="fa fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label d-block mb-2" id="add-role-label">Pilih Role (maksimal 2)</label>
                            <div class="role-selector-group" id="add-role-container" role="group" aria-labelledby="add-role-label">
                                <?php foreach ($roles_list as $role): ?>
                                    <div class="role-selector">
                                        <input class="role-checkbox" type="checkbox" name="roles[]" value="<?= $role ?>" id="role_<?= $role ?>">
                                        <label for="role_<?= $role ?>">Admin <?= ucfirst($role) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <small class="form-text text-muted mt-2">Anda dapat memilih hingga 2 role untuk satu user.</small>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="reset" class="btn btn-light mr-2">Reset</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card custom-card">
                <div class="card-header py-3">
                    <i class="fas fa-users mr-2"></i> Daftar User Admin
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-custom" id="dataTable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>
                                                <div class="user-info">
                                                    <div class="user-avatar"><?= strtoupper(substr(esc($user['username']), 0, 1)) ?></div>
                                                    <span class="font-weight-bold"><?= esc($user['username']) ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $roles = explode(',', $user['roles']);
                                                foreach ($roles as $role) {
                                                    echo "<span class='role-badge role-" . esc(trim($role)) . " mr-1'>" . esc(ucfirst($role)) . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-warning btn-edit" data-toggle="modal" data-target="#editUserModal"
                                                        data-id="<?= esc($user['id']) ?>"
                                                        data-username="<?= esc($user['username']) ?>"
                                                        data-roles="<?= esc($user['roles']) ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-delete"
                                                        data-id="<?= esc($user['id']) ?>"
                                                        data-username="<?= esc($user['username']) ?>">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-state">
                                                <i class="fas fa-user-slash"></i>
                                                <h5 class="mt-3">Belum Ada User</h5>
                                                <p class="text-muted">Gunakan form di samping untuk menambahkan user admin baru.</p>
                                            </div>
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

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editUserForm" method="post" action="<?= site_url('admin-user/edit') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="user_id" id="editUserId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel">Edit User Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="editPassword" class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="editPassword" name="password" placeholder="Password baru">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary toggle-password" type="button"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label d-block mb-2" id="edit-role-label">Pilih Role (maksimal 2)</label>
                        <div class="role-selector-group" id="edit-role-container" role="group" aria-labelledby="edit-role-label">
                            <?php foreach ($roles_list as $role): ?>
                                <div class="role-selector">
                                    <input class="edit-role-checkbox" type="checkbox" name="roles[]" value="<?= $role ?>" id="edit_role_<?= $role ?>">
                                    <label for="edit_role_<?= $role ?>">Admin <?= ucfirst($role) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="deleteUserForm" method="post" action="<?= site_url('admin-user/delete') ?>" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="user_id" id="deleteUserId">
</form>

<script>
    $(document).ready(function() {

        const MAX_ROLES = 2;

        /**
         * Mengelola batasan pemilihan role pada checkbox.
         * Checkbox lain akan dinonaktifkan saat batas maksimum tercapai.
         * @param {string} containerId - ID dari elemen pembungkus checkbox.
         */
        function handleRoleSelection(containerId) {
            const container = $(`#${containerId}`);
            if (!container.length) return;

            container.on('change', 'input[type="checkbox"]', function() {
                const checkedCount = container.find('input:checked').length;
                const checkboxes = container.find('input[type="checkbox"]');

                if (checkedCount >= MAX_ROLES) {
                    checkboxes.not(':checked').prop('disabled', true);
                } else {
                    checkboxes.prop('disabled', false);
                }
            });
        }

        handleRoleSelection('add-role-container');
        handleRoleSelection('edit-role-container');

        /**
         * Mengaktifkan fitur lihat/sembunyikan password pada input.
         */
        function setupPasswordToggle() {
            // Gunakan event delegation agar berfungsi juga untuk elemen dinamis/modal
            $(document).on('click', '.toggle-password', function() {
                const input = $(this).closest('.input-group').find('input');
                const icon = $(this).find('i');
                const isPassword = input.attr('type') === 'password';

                input.attr('type', isPassword ? 'text' : 'password');
                icon.toggleClass('fa-eye fa-eye-slash');
            });
        }

        setupPasswordToggle();

        /**
         * Memberikan feedback loading pada tombol submit form untuk mencegah double-click.
         * @param {string} formId - ID dari form.
         */
        function setupFormLoading(formId) {
            $(`#${formId}`).on('submit', function() {
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            });
        }

        setupFormLoading('addUserForm');
        setupFormLoading('editUserForm');

        // --- Modal & Action Handlers ---

        // Edit Modal: Mengisi data saat modal ditampilkan
        $('#editUserModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);
            const roles = button.data('roles').toString().split(',');
            const editContainer = $('#edit-role-container');

            modal.find('#editUserId').val(button.data('id'));
            modal.find('#editUsername').val(button.data('username'));
            modal.find('#editPassword').val('');

            // Reset state role
            editContainer.find('input[type="checkbox"]').prop('checked', false).prop('disabled', false);
            roles.forEach(role => {
                if (role.trim()) {
                    editContainer.find(`input[value="${role.trim()}"]`).prop('checked', true);
                }
            });

            // Trigger manual untuk mengaplikasikan logika disable jika perlu
            editContainer.find('input:first').trigger('change');
        });

        // Delete Action: Menggunakan SweetAlert2 untuk konfirmasi
        $('.btn-delete').on('click', function() {
            const id = $(this).data('id');
            const username = $(this).data('username');

            Swal.fire({
                title: 'Anda Yakin?',
                html: `User <strong>${username}</strong> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger-color)',
                cancelButtonColor: 'var(--secondary-text)',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteUserId').val(id);
                    $('#deleteUserForm').submit();
                }
            });
        });

    });
</script>

<?= $this->endSection() ?>