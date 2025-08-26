<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SiMelung | Login</title>
    <link rel="icon" type="image/png" href="<?= base_url('img/nojdl.png') ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .login-wrapper {
            background-color: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            overflow: hidden;
            display: flex;
            max-width: 950px;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            /* Penting untuk posisi tombol Kembali */
        }

        .login-wrapper:hover {
            transform: translateY(-5px);
            box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, 0.25);
        }

        .login-content {
            padding: 3rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-image-container {
            flex: 1;
            background: #e6eff7;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-image {
            width: 90%;
            height: auto;
            object-fit: contain;
            animation: fadeIn 1s ease-out;
        }

        .logo {
            width: 80px;
            margin-bottom: 0.5rem;
            animation: pulse 1s infinite alternate;
        }

        /* Tombol Kembali yang baru */
        .btn-back {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            color: #2D336B;
            transform: translateX(-5px);
        }

        .title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2D336B;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .input-group-password {
            position: relative;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: #2D336B;
            box-shadow: 0 0 0 4px rgba(45, 51, 107, 0.15);
            background-color: #fff;
        }

        .form-control-password {
            padding-right: 3rem;
        }

        .toggle-password-icon {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            cursor: pointer;
            color: #adb5bd;
            transition: color 0.2s;
        }

        .toggle-password-icon:hover {
            color: #6c757d;
        }

        .btn-primary {
            background-color: #2D336B;
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #21264E;
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 0.75rem;
            font-size: 0.9rem;
            padding: 1rem;
            animation: slideIn 0.5s ease-out;
        }

        /* Keyframe Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes pulse {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.05);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 767px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 450px;
                box-shadow: none;
            }

            .login-wrapper:hover {
                transform: none;
                box-shadow: none;
            }

            .login-image-container {
                display: none;
            }

            .login-content {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <a href="<?= base_url('/') ?>" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div class="login-content">
            <div class="text-center mb-4">
                <img src="<?= base_url('img/nojdl.png') ?>" alt="Logo SiMelung" class="logo">
            </div>
            <div class="text-start mb-4">
                <h1 class="title">Hallo, Selamat Datang Kembali !</h1>
                <p class="subtitle">Silahkan Masukan Username dan Password Anda</p>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/login/process') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="username" class="form-label d-block text-start">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required />
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label d-block text-start">Password</label>
                    <div class="input-group-password">
                        <input type="password" class="form-control form-control-password" id="password" name="password" placeholder="Password" required />
                        <i class="bi bi-eye-slash-fill toggle-password-icon" id="togglePasswordIcon"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
        <div class="login-image-container d-none d-md-flex">
            <img src="<?= base_url('img/login_illustration.svg') ?>" alt="Login Illustration" class="login-image">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePasswordIcon = document.querySelector("#togglePasswordIcon");
        const password = document.querySelector("#password");

        togglePasswordIcon.addEventListener("click", function() {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("bi-eye-slash-fill");
            this.classList.toggle("bi-eye-fill");
        });
    </script>
</body>

</html>