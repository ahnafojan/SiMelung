<?= $this->extend('layouts/main_layout_landing') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #3d4f66 0%, #2c3e50 100%);
        --secondary-gradient: linear-gradient(135deg, #5a6f87 0%, #3d4f66 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --accent-gradient: linear-gradient(135deg, #4a5f7f 0%, #2c3e50 100%);
        --shadow-sm: 0 2px 8px rgba(61, 79, 102, 0.15);
        --shadow-md: 0 4px 16px rgba(61, 79, 102, 0.2);
        --shadow-lg: 0 8px 32px rgba(61, 79, 102, 0.25);
    }

    /* Hero Header dengan Parallax Effect */
    .umkm-hero {
        position: relative;
        background: var(--primary-gradient);
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-top: -20px;
    }

    .umkm-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        animation: float 15s ease-in-out infinite;
    }

    .umkm-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(to top, #f8f9fa, transparent);
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
        padding: 3rem 1rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        animation: slideDown 0.6s ease-out;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.8s ease-out;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        font-weight: 400;
        animation: fadeIn 1s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Main Content Area */
    .umkm-container {
        max-width: 1400px;
        margin: -80px auto 3rem;
        padding: 0 1.5rem;
        position: relative;
        z-index: 3;
    }

    .umkm-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
    }

    /* Main Card */
    .umkm-main-card {
        background: white;
        border-radius: 24px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        animation: slideUp 0.8s ease-out;
    }

    .umkm-image-wrapper {
        position: relative;
        width: 100%;
        height: 450px;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .umkm-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .umkm-main-card:hover .umkm-image-wrapper img {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
    }

    .umkm-body {
        padding: 2.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 28px;
        background: var(--primary-gradient);
        border-radius: 4px;
    }

    .umkm-description {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #4a5568;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 16px;
        border-left: 4px solid #667eea;
    }

    /* Info Cards */
    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-gradient);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .info-card:hover::before {
        transform: scaleY(1);
    }

    .info-card-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .info-card-label {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .info-card-value {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 600;
        word-break: break-word;
    }

    /* Action Buttons */
    .action-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-action {
        padding: 1rem 2rem;
        border-radius: 16px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
        color: white;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-action:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-action i {
        font-size: 1.2rem;
        position: relative;
        z-index: 1;
    }

    .btn-action span {
        position: relative;
        z-index: 1;
    }

    .btn-location {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-location:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    }

    .btn-contact {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .btn-contact:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(56, 239, 125, 0.4);
    }

    /* Sidebar */
    .umkm-sidebar {
        animation: slideUp 0.8s ease-out 0.2s both;
    }

    .sidebar-card {
        background: white;
        border-radius: 24px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        position: sticky;
        top: 100px;
    }

    .sidebar-header {
        background: var(--primary-gradient);
        color: white;
        padding: 1.5rem;
        font-weight: 700;
        font-size: 1.2rem;
        text-align: center;
        position: relative;
    }

    .sidebar-header::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: white;
        border-radius: 2px;
    }

    .sidebar-content {
        padding: 1.5rem;
        max-height: 600px;
        overflow-y: auto;
    }

    .sidebar-content::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .sidebar-content::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 16px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .sidebar-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .sidebar-item:hover {
        transform: translateX(8px);
        background: white;
        border-color: #667eea;
        box-shadow: var(--shadow-sm);
    }

    .sidebar-item:hover::before {
        left: 100%;
    }

    .sidebar-thumbnail {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .sidebar-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .sidebar-item:hover .sidebar-thumbnail img {
        transform: scale(1.1);
    }

    .sidebar-thumbnail-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .sidebar-info h5 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.3;
    }

    .sidebar-category {
        display: inline-block;
        font-size: 0.8rem;
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .umkm-content {
            grid-template-columns: 1fr;
        }

        .sidebar-card {
            position: static;
        }

        .hero-title {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 768px) {
        .umkm-container {
            margin-top: -50px;
        }

        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .umkm-image-wrapper {
            height: 300px;
        }

        .umkm-body {
            padding: 1.5rem;
        }

        .action-buttons {
            grid-template-columns: 1fr;
        }

        .info-cards {
            grid-template-columns: 1fr;
        }
    }

    /* Loading Animation */
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }

        100% {
            background-position: 1000px 0;
        }
    }
</style>

<!-- Hero Section -->
<div class="umkm-hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-store"></i> UMKM Lokal
        </div>
        <h1 class="hero-title"><?= esc($umkm['nama_umkm']) ?></h1>
        <p class="hero-subtitle"><?= esc($umkm['kategori']) ?></p>
    </div>
</div>

<!-- Main Content -->
<div class="umkm-container">
    <div class="umkm-content">
        <!-- Main Card -->
        <div class="umkm-main-card">
            <div class="umkm-image-wrapper">
                <?php if (!empty($umkm['foto_umkm'])): ?>
                    <img src="<?= base_url('uploads/foto_umkm/' . $umkm['foto_umkm']) ?>"
                        alt="<?= esc($umkm['nama_umkm']) ?>">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-store" style="font-size: 5rem; color: white; opacity: 0.5;"></i>
                    </div>
                <?php endif; ?>
                <div class="image-overlay"></div>
            </div>

            <div class="umkm-body">
                <h2 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Tentang UMKM
                </h2>

                <div class="umkm-description">
                    <?= esc($umkm['deskripsi']) ?>
                </div>

                <h2 class="section-title">
                    <i class="fas fa-address-card"></i>
                    Informasi Kontak
                </h2>

                <div class="info-cards">
                    <div class="info-card">
                        <div class="info-card-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="info-card-label">Pemilik</div>
                        <div class="info-card-value"><?= esc($umkm['pemilik']) ?></div>
                    </div>

                    <div class="info-card">
                        <div class="info-card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-card-label">Alamat</div>
                        <div class="info-card-value"><?= esc($umkm['alamat']) ?></div>
                    </div>

                    <div class="info-card">
                        <div class="info-card-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-card-label">Kontak</div>
                        <div class="info-card-value"><?= esc($umkm['kontak']) ?></div>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="<?= esc($umkm['gmaps_url'] ?? '#') ?>"
                        target="_blank"
                        class="btn-action btn-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Lihat Lokasi</span>
                    </a>
                    <a href="tel:<?= esc($umkm['kontak'] ?? '#') ?>"
                        class="btn-action btn-contact">
                        <i class="fas fa-phone"></i>
                        <span>Hubungi Sekarang</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="umkm-sidebar">
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <i class="fas fa-store"></i> UMKM Lainnya
                </div>
                <div class="sidebar-content">
                    <?php foreach ($other_umkm as $u): ?>
                        <a href="<?= base_url('umkm/detail/' . $u['id']) ?>" class="sidebar-item">
                            <div class="sidebar-thumbnail">
                                <?php if (!empty($u['foto_umkm'])): ?>
                                    <img src="<?= base_url('uploads/foto_umkm/' . $u['foto_umkm']) ?>"
                                        alt="<?= esc($u['nama_umkm']) ?>">
                                <?php else: ?>
                                    <div class="sidebar-thumbnail-placeholder">
                                        <i class="fas fa-store"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="sidebar-info">
                                <h5><?= esc($u['nama_umkm']) ?></h5>
                                <span class="sidebar-category"><?= esc($u['kategori']) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>