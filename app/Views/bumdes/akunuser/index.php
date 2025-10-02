<?php
// Definisikan daftar role sekali saja di sini untuk menghindari duplikasi
$roles_list = ['keuangan', 'umkm', 'pariwisata', 'komersial'];

// Cek apakah role 'keuangan' sudah ada
$keuanganRoleExists = false;
if (!empty($users)) {
    foreach ($users as $user) {
        $userRoles = explode(',', $user['roles']);
        if (in_array('keuangan', array_map('trim', $userRoles))) {
            $keuanganRoleExists = true;
            break;
        }
    }
}
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
        align-items: flex-start;
        /* Mengatur alignment ke atas */
    }

    /* MODIFIKASI: Perubahan CSS di sini */
    .role-selector {
        position: relative;
        text-align: center;
        /* Membuat teks di dalamnya center */
    }

    .role-selector .role-status-text {
        display: block;
        font-size: 0.75rem;
        margin-top: 2px;
        color: var(--secondary-text);
        width: 100%;
    }

    /* AKHIR MODIFIKASI CSS */

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
        border-color: #e3e6f0;
    }

    .role-selector:hover label:not([for*=":disabled"]) {
        border-color: var(--primary-color);
    }

    .role-selector label[for*=":disabled"] {
        background-color: #f8f9fc;
        color: #b8b8b8;
        cursor: not-allowed;
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
        color: var(--text-dark);
        /* Kontras warna lebih baik */
    }

    /* === MODIFIKASI: Tambahkan dua class di bawah ini === */
    .role-bumdes {
        background-color: #34495e;
        /* Warna Abu-abu Tua (Netral) */
    }

    .role-desa {
        background-color: #c0392b;
        /* Warna Merah Tua (Otoritas) */
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

    <?php if (session()->getFlashdata('success')) : ?>
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
                                <?php foreach ($roles_list as $role) : ?>

                                    <?php
                                    $isKeuangan = ($role === 'keuangan');
                                    $isDisabled = ($isKeuangan && $keuanganRoleExists);
                                    ?>
                                    <div class="role-selector">
                                        <input class="role-checkbox" type="checkbox" name="roles[]" value="<?= $role ?>" id="role_<?= $role ?>" <?= $isDisabled ? 'disabled' : '' ?>>
                                        <label for="role_<?= $role ?><?= $isDisabled ? ':disabled' : '' ?>">Admin <?= ucfirst($role) ?></label>
                                        <?php if ($isDisabled) : ?>
                                            <small class="role-status-text">(Sudah Ada)</small>
                                        <?php endif; ?>
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
                                <?php if (!empty($users)) : ?>
                                    <?php foreach ($users as $user) : ?>

                                        <?php
                                        // 1. Ubah string role menjadi array
                                        $userRoles = array_map('trim', explode(',', $user['roles']));

                                        // 2. Cek apakah role yang dilindungi ada di dalam array
                                        $isProtected = in_array('bumdes', $userRoles) || in_array('desa', $userRoles);

                                        // 3. Siapkan atribut untuk tombol hapus
                                        $disabledAttribute = $isProtected ? 'disabled title="User dengan role ini tidak dapat dihapus"' : '';
                                        ?>

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
                                                    echo "<span class='role-badge role-" . esc(trim($role)) . " mr-1'>" . esc(ucfirst(str_replace('_', ' ', $role))) . "</span>";
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
                                                        data-username="<?= esc($user['username']) ?>"
                                                        <?= $disabledAttribute ?>>
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
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
                            <?php foreach ($roles_list as $role) : ?>
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
    const keuanganRoleExists = <?= $keuanganRoleExists ? 'true' : 'false' ?>;

    $(document).ready(function() {
        const MAX_ROLES = 2;

        /**
         * Fungsi tunggal untuk menerapkan semua aturan batas role.
         */
        function applyRoleLimits(container) {
            const checkboxes = container.find('input[type="checkbox"]');
            const checkedCount = container.find('input:checked').length;

            if (checkedCount >= MAX_ROLES) {
                checkboxes.not(':checked').prop('disabled', true);
            } else {
                checkboxes.not('[data-permanent-disabled]').prop('disabled', false);
            }
        }

        // Jalankan fungsi di atas setiap kali ada perubahan
        $('#add-role-container, #edit-role-container').on('change', 'input[type="checkbox"]', function() {
            const container = $(this).closest('.role-selector-group');
            applyRoleLimits(container);
        });

        // Atur kondisi awal saat modal dibuka
        $('#editUserModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const userRoles = button.data('roles').toString().split(',').map(r => r.trim()).filter(r => r);
            const editContainer = $('#edit-role-container');

            // 1. Reset form
            editContainer.find('.form-text.text-danger, .role-status-text').remove();
            editContainer.find('input[type="checkbox"]')
                .prop('checked', false).prop('disabled', false).removeAttr('data-permanent-disabled');

            // 2. Isi data
            $(this).find('#editUserId').val(button.data('id'));
            $(this).find('#editUsername').val(button.data('username'));
            $(this).find('#editPassword').val('');
            userRoles.forEach(role => {
                if (role) editContainer.find(`input[value="${role}"]`).prop('checked', true);
            });

            // 3. Aturan khusus (seperti keuangan & desa)
            const isCurrentUserKeuangan = userRoles.includes('keuangan');
            const keuanganCheckbox = editContainer.find('input[value="keuangan"]');
            if (keuanganRoleExists && !isCurrentUserKeuangan) {
                keuanganCheckbox.prop('disabled', true).attr('data-permanent-disabled', 'true');
                keuanganCheckbox.parent().append('<small class="role-status-text">(Sudah Ada)</small>');
            }
            const isDesaUser = userRoles.includes('desa');
            if (isDesaUser) {
                editContainer.find('input[type="checkbox"]:not(:checked)').prop('disabled', true);
            }

            // ===================================================================
            // INI PERBAIKAN FINAL UNTUK KONDISI AWAL MODAL
            // ===================================================================
            // Panggil fungsi utama untuk mengatur kondisi awal.
            // Ini akan secara otomatis menonaktifkan role lain jika user 'bumdes' sudah punya 2 role.
            applyRoleLimits(editContainer);
        });

        // --- Sisa kode helper lainnya (tidak berubah) ---
        function setupPasswordToggle() {
            $(document).on('click', '.toggle-password', function() {
                const input = $(this).closest('.input-group').find('input');
                const icon = $(this).find('i');
                const isPassword = input.attr('type') === 'password';
                input.attr('type', isPassword ? 'text' : 'password');
                icon.toggleClass('fa-eye fa-eye-slash');
            });
        }
        setupPasswordToggle();

        function setupFormLoading(formId) {
            $(`#${formId}`).on('submit', function() {
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            });
        }
        setupFormLoading('addUserForm');
        setupFormLoading('editUserForm');
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