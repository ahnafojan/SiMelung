<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Admin</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        .profile-icon {
            font-size: 8rem;
            color: #0d6efd;
        }
    </style>
</head>

<body class="bg-light py-5">

    <div class="container text-center">
        <!-- Ikon dan Nama -->
        <i class="fas fa-user-circle profile-icon mb-3"></i>
        <h2 class="mb-1">Ahmad Fauzi</h2> <!-- Ganti ini dengan session()->get('nama') jika dinamis -->
        <p class="text-muted">Admin BUMDes</p>

        <!-- Tombol Aksi -->
        <div class="mt-4">
            <a href="<?= site_url('/') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <a href="<?= site_url('logout') ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>