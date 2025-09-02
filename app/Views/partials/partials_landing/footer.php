<footer class="footer-custom py-5 bg-mountain-dark text-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="font-weight-bold mb-3">BUMDes Melung</h5>
                <p class="text-light">Badan Usaha Milik Desa yang bergerak dalam pengembangan ekonomi lokal melalui sektor pertanian, perdagangan, dan jasa.</p>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="font-weight-bold mb-3">Kontak Kami</h5>
                <p class="mb-2 d-flex align-items-start">
                    <i class="fas fa-map-marker-alt mr-2" style="margin-top: 3px;"></i> <span>Desa Melung, Kecamatan Kedungbanteng, Kabupaten Banyumas, Jawa Tengah</span>
                </p>
                <p class="mb-2">
                    <i class="fas fa-envelope mr-2"></i>
                    bumdesmelung@gmail.com
                </p>
                <p class="mb-2">
                    <i class="fas fa-phone mr-2"></i>
                    +62 8820-0801-0365
                </p>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="font-weight-bold mb-3">Ikuti Kami</h5>
                <div class="social-links">
                    <a href="#" class="text-light mr-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light mr-3"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/bumdes.melung?igsh=NXRhcHczMjg3Njd4" class="text-light mr-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-4 border-light">
        <div class="text-center">
            <span>Copyright &copy; Simelung <?= date('Y') ?></span>
        </div>
    </div>
</footer>

<style>
    .footer-custom {
        background-color: var(--mountain-dark);
        color: var(--warm-white);
    }

    .footer-custom h5 {
        font-size: 1.25rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .footer-custom p {
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .social-links a {
        font-size: 1.5rem;
        transition: color 0.3s;
    }

    .social-links a:hover {
        color: var(--coffee-gold);
    }

    .border-light {
        border-color: rgba(255, 255, 255, 0.2);
    }
</style>