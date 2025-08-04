<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Melung</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('css/custom-theme.css') ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('img/favicon.ico') ?>">
    <style>
        .carousel-caption h2 {
            text-shadow: 2px 2px 8px #000;
        }

        /* Hanya style khusus, warna utama pakai custom-theme.css */
    </style>
</head>

<?= $this->include('partials/partials_admin/head') ?>

<body class="bg-background">

    <?= $this->include('partials/partials_landing/topbar') ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('partials/partials_landing/footer') ?>

    <!-- Bootstrap JS & dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>