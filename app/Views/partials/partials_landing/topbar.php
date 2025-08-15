<!-- NAVBAR -->
<nav class="navbar navbar-dark navbar-custom fixed-top" id="mainNavbar">
    <div class="container-fluid px-3 d-flex justify-content-between align-items-center">
        <a class="navbar-brand m-0 d-flex align-items-center" href="<?= base_url('/') ?>">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" class="navbar-logo" />
            <span class="brand-text">Simelung</span>
        </a>

        <!-- Burger -->
        <button id="burgerBtn" class="burger-btn" aria-label="Toggle menu" aria-controls="offcanvasMenu" aria-expanded="false">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </button>
    </div>
</nav>

<!-- MENU SAMPING -->
<aside id="offcanvasMenu" aria-hidden="true" aria-labelledby="burgerBtn" role="dialog">
    <div class="menu-header">
        <button id="closeBtn" class="close-btn" aria-label="Close menu" title="Tutup">&times;</button>
        <div class="menu-brand">
            <img src="<?= base_url('img/KKNLOGO.png') ?>" alt="Logo" class="menu-logo" />
            <h4>BUMDES Melung</h4>
        </div>
    </div>

    <ul class="menu-list" role="menu">
        <li role="none"><a role="menuitem" href="#home" class="menu-link"><i class="fas fa-home mr-2"></i>Beranda</a></li>
        <li role="none"><a role="menuitem" href="#tentang" class="menu-link"><i class="fas fa-info-circle mr-2"></i>Tentang</a></li>
        <li role="none"><a role="menuitem" href="#umkm" class="menu-link"><i class="fas fa-store mr-2"></i>UMKM</a></li>
        <li role="none"><a role="menuitem" href="#petani" class="menu-link"><i class="fas fa-users mr-2"></i>Petani</a></li>
        <li role="none"><a role="menuitem" href="#grafik" class="menu-link"><i class="fas fa-chart-line mr-2"></i>Grafik</a></li>
        <li role="none"><a role="menuitem" href="#aset" class="menu-link"><i class="fas fa-boxes mr-2"></i>Aset</a></li>
    </ul>

    <!-- Footer: Login Admin tersembunyi / tidak mencolok -->
    <div class="menu-footer">
        <a href="<?= base_url('login') ?>" class="btn-login subtle" aria-label="Login Admin">
            <i class="fas fa-lock mr-2" aria-hidden="true"></i>
            <span>Login Admin</span>
        </a>
    </div>
</aside>

<!-- Overlay -->
<div id="menuOverlay" class="menu-overlay"></div>

<style>
    /* Sembunyikan navbar lama agar tidak dobel */
    .navbar-expand-lg {
        display: none !important;
    }

    /* Navbar */
    .navbar-custom {
        background: transparent;
        backdrop-filter: blur(0px);
        border-bottom: 1px solid transparent;
        transition: all 0.4s ease;
        padding: 1.2rem 0;
        z-index: 1000;
    }

    .navbar-custom.scrolled {
        background: rgba(44, 62, 80, 0.95);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 0.8rem 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .navbar-logo {
        height: 40px;
        width: auto;
        margin-right: 12px;
        transition: all .3s ease;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, .2));
    }

    .navbar-custom.scrolled .navbar-logo {
        height: 35px;
    }

    .brand-text {
        font-size: 1.35rem;
        font-weight: 700;
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, .3);
        letter-spacing: .5px;
        transition: all .3s;
    }

    .navbar-brand:hover .brand-text {
        color: #d4a574;
        text-decoration: none;
    }

    /* Burger */
    .burger-btn {
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .15);
        border-radius: 12px;
        padding: 8px 10px;
        cursor: pointer;
        transition: all .3s ease;
        backdrop-filter: blur(10px);
    }

    .burger-btn:hover {
        background: rgba(255, 255, 255, .16);
        transform: translateY(-1px);
    }

    .burger-line {
        display: block;
        width: 20px;
        height: 2px;
        background: #fff;
        margin: 4px 0;
        border-radius: 2px;
        transition: all .3s;
    }

    /* Spasi section supaya tidak ketutup navbar */
    #home {
        margin-top: 0;
        padding-top: 100px;
    }

    section:not(#home) {
        margin-top: 0;
        padding-top: 6rem;
    }

    /* Offcanvas Menu */
    #offcanvasMenu {
        position: fixed;
        top: 0;
        right: -350px;
        width: 320px;
        height: 100vh;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #fff;
        z-index: 1100;
        opacity: 0;
        visibility: hidden;
        transition: right .35s ease, opacity .35s ease, visibility .35s ease;
        overflow-y: auto;
        box-shadow: -10px 0 30px rgba(0, 0, 0, .3);
        display: flex;
        flex-direction: column;
        /* penting: biar footer nempel bawah */
    }

    #offcanvasMenu.show {
        right: 0;
        opacity: 1;
        visibility: visible;
    }

    .menu-header {
        padding: 2rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, .08);
        position: relative;
    }

    .close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, .08);
        border: none;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .3s;
        font-size: 1.5rem;
    }

    .close-btn:hover {
        background: rgba(255, 255, 255, .16);
        transform: rotate(90deg);
    }

    .menu-brand {
        display: flex;
        align-items: center;
        margin-top: 1rem;
    }

    .menu-logo {
        height: 50px;
        width: auto;
        margin-right: 15px;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, .3));
    }

    .menu-brand h4 {
        margin: 0;
        font-weight: 700;
        color: #d4a574;
        font-size: 1.15rem;
    }

    .menu-list {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }

    .menu-list li {
        margin: 0;
        opacity: 0;
        transform: translateX(30px);
        transition: all .3s;
    }

    #offcanvasMenu.show .menu-list li {
        opacity: 1;
        transform: translateX(0);
    }

    #offcanvasMenu.show .menu-list li:nth-child(1) {
        transition-delay: .08s
    }

    #offcanvasMenu.show .menu-list li:nth-child(2) {
        transition-delay: .12s
    }

    #offcanvasMenu.show .menu-list li:nth-child(3) {
        transition-delay: .16s
    }

    #offcanvasMenu.show .menu-list li:nth-child(4) {
        transition-delay: .2s
    }

    #offcanvasMenu.show .menu-list li:nth-child(5) {
        transition-delay: .24s
    }

    #offcanvasMenu.show .menu-list li:nth-child(6) {
        transition-delay: .28s
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: .95rem 1.25rem;
        color: rgba(255, 255, 255, .9);
        text-decoration: none;
        font-size: 0.98rem;
        font-weight: 500;
        transition: all .25s;
        border-left: 3px solid transparent;
    }

    .menu-link:hover {
        color: #d4a574;
        background: rgba(212, 165, 116, .08);
        border-left-color: #d4a574;
        text-decoration: none;
        transform: translateX(5px);
    }

    /* Footer (Login Admin "tersamar") */
    .menu-footer {
        margin-top: auto;
        padding: .75rem 1rem 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, .06);
    }

    .btn-login.subtle {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        width: auto;
        padding: 8px 12px;
        background: transparent;
        border: 1px dashed rgba(255, 255, 255, .12);
        border-radius: 10px;
        color: rgba(255, 255, 255, .55);
        text-decoration: none;
        font-weight: 500;
        font-size: .9rem;
        transition: all .25s ease;
        opacity: .55;
        /* tidak mencolok */
        filter: blur(.1px);
        /* sedikit “tersembunyi” */
    }

    .btn-login.subtle:hover,
    .btn-login.subtle:focus {
        color: #e8e8e8;
        border-color: rgba(255, 255, 255, .25);
        background: rgba(255, 255, 255, .04);
        opacity: 1;
        filter: none;
        outline: none;
    }

    .btn-login.subtle i {
        font-size: .9rem;
        opacity: .9;
    }

    /* Overlay */
    .menu-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transition: all .3s;
        backdrop-filter: blur(5px);
    }

    .menu-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        * {
            transition: none !important;
            animation: none !important;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        #offcanvasMenu {
            width: 300px;
            right: -300px;
        }

        .menu-header {
            padding: 1.5rem 1rem;
        }

        .menu-link {
            padding: 0.875rem 1rem;
            font-size: .95rem;
        }

        #home {
            padding-top: 110px;
        }

        section:not(#home) {
            padding-top: 5rem;
        }
    }

    @media (max-width: 480px) {
        #offcanvasMenu {
            width: 280px;
            right: -280px;
        }

        .navbar-logo {
            height: 35px;
        }

        .brand-text {
            font-size: 1.2rem;
        }

        .navbar-custom.scrolled .brand-text {
            font-size: 1.1rem;
        }

        #home {
            padding-top: 100px;
        }

        section:not(#home) {
            padding-top: 4.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('mainNavbar');
        const burgerBtn = document.getElementById('burgerBtn');
        const offcanvasMenu = document.getElementById('offcanvasMenu');
        const closeBtn = document.getElementById('closeBtn');
        const menuOverlay = document.getElementById('menuOverlay');
        const menuLinks = document.querySelectorAll('.menu-link[href^="#"]');
        const loginBtn = document.querySelector('.btn-login.subtle');

        // Navbar scroll effect
        let ticking = false;

        function updateNavbar() {
            const scrollPosition = window.scrollY;
            if (scrollPosition > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }
        window.addEventListener('scroll', requestTick);
        updateNavbar();

        // Menu open/close
        function openMenu() {
            offcanvasMenu.classList.add('show');
            menuOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
            burgerBtn.setAttribute('aria-expanded', 'true');
            offcanvasMenu.setAttribute('aria-hidden', 'false');
        }

        function closeMenu() {
            offcanvasMenu.classList.remove('show');
            menuOverlay.classList.remove('show');
            document.body.style.overflow = '';
            burgerBtn.setAttribute('aria-expanded', 'false');
            offcanvasMenu.setAttribute('aria-hidden', 'true');
        }
        burgerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            openMenu();
        });
        closeBtn.addEventListener('click', closeMenu);
        menuOverlay.addEventListener('click', closeMenu);
        document.addEventListener('click', function(e) {
            if (!offcanvasMenu.contains(e.target) && !burgerBtn.contains(e.target)) closeMenu();
        });

        // Smooth scroll
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    closeMenu();
                    setTimeout(() => {
                        const offsetTop = targetElement.offsetTop - 80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }, 300);
                }
            });
        });

        // Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && offcanvasMenu.classList.contains('show')) closeMenu();
        });

        // Logo hover kecil saja biar tidak mencolok
        const navbarBrand = document.querySelector('.navbar-brand');
        const navbarLogo = document.querySelector('.navbar-logo');
        if (navbarBrand && navbarLogo) {
            navbarBrand.addEventListener('mouseenter', () => {
                navbarLogo.style.transform = 'scale(1.05)';
            });
            navbarBrand.addEventListener('mouseleave', () => {
                navbarLogo.style.transform = 'scale(1)';
            });
        }

        // Aksesibilitas: saat fokus ke tombol login, hilangkan efek samar agar terlihat
        if (loginBtn) {
            loginBtn.addEventListener('focus', () => {
                loginBtn.style.opacity = '1';
                loginBtn.style.filter = 'none';
            });
            loginBtn.addEventListener('blur', () => {
                loginBtn.style.opacity = '';
                loginBtn.style.filter = '';
            });
        }
    });
</script>