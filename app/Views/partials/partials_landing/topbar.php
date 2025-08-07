<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="<?= base_url('/') ?>">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" style="height:32px; margin-right:10px;">
            Simelung
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