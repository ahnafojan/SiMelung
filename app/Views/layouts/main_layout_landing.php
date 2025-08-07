<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website Resmi BUMDes Melung - Badan Usaha Milik Desa Melung">
    <meta name="keywords" content="BUMDes, Melung, Desa, Usaha Desa, UMKM, Petani Kopi, Kopi Melung">
    <title><?= $title ?? 'Simelung | Landingpage' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('img/nojdl.png') ?>">

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2D336B;
            --secondary-color: #4A5568;
            --accent-color: #F7B801;
            --text-light: #FFFFFF;
            --text-dark: #2D3748;
            --bg-light: #F7FAFC;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
        }

        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary-custom:hover {
            background-color: #1E2447;
            border-color: #1E2447;
        }

        .hero-section {
            background: linear-gradient(rgba(45, 51, 107, 0.8), rgba(45, 51, 107, 0.8)),
                url('<?= base_url('img/mellung.jpg') ?>') center/cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: var(--text-light);
        }

        .section-padding {
            padding: 80px 0;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .footer-custom {
            background-color: var(--primary-color);
            color: var(--text-light);
        }

        .section-title {
            position: relative;
            margin-bottom: 50px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: var(--accent-color);
        }

        .img-hover {
            transition: transform 0.3s ease;
        }

        .img-hover:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .hero-section {
                min-height: 70vh;
                text-align: center;
            }

            .section-padding {
                padding: 50px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="<?= base_url('/') ?>">
                <i class="fas fa-leaf mr-2"></i>BUMDes Melung
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#umkm">UMKM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#petani">Petani</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#grafik">Grafik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#aset">Aset</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light btn-sm ml-2" href="<?= base_url('login') ?>">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?= $this->include('partials/partials_landing/topbar') ?>

    <!-- Main Content -->
    <?= $this->renderSection('content') ?>

    <!-- Footer -->
    <?= $this->include('partials/partials_landing/footer') ?>

    <!-- Bootstrap JS & dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(45, 51, 107, 0.95)';
            } else {
                navbar.style.backgroundColor = 'var(--primary-color)';
            }
        });
    </script>
</body>

</html>