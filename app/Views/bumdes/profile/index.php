<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Akun</title>
    <!-- CDN Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light py-4">

    <div class="container">
        <div class="text-center mb-4">
            <h2>Profil Akun</h2>
            <p class="text-muted">Melihat dan mengedit informasi akun Anda.</p>
        </div>

        <div class="row">
            <!-- Form Info Akun -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <strong>Informasi Akun</strong>
                    </div>
                    <div class="card-body">
                        <form action="#">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" value="Nama Pengguna">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="namauser123">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form Ganti Password -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <strong>Ganti Password</strong>
                    </div>
                    <div class="card-body">
                        <form action="#">
                            <div class="mb-3">
                                <label for="old_password" class="form-label">Password Lama</label>
                                <input type="password" class="form-control" name="old_password">
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" name="new_password">
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>
                            <button type="submit" class="btn btn-warning">Ubah Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="text-center mt-4">
            <a href="/" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
            </a>
            <a href="/logout" class="btn btn-danger ms-2" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>