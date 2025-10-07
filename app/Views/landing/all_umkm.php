<?= $this->extend('layouts/main_layout_landing') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-color: #3d4f66;
        --primary-dark: #2c3e50;
        --primary-light: #5a6f87;
        --success-color: #11998e;
        --success-light: #38ef7d;
        --shadow-sm: 0 2px 8px rgba(61, 79, 102, 0.15);
        --shadow-md: 0 4px 16px rgba(61, 79, 102, 0.2);
        --shadow-lg: 0 8px 32px rgba(61, 79, 102, 0.25);
    }

    /* Hero Header */
    .all-umkm-hero {
        position: relative;
        background: linear-gradient(135deg, #3d4f66 0%, #2c3e50 100%);
        padding: 5rem 0 6rem;
        margin-top: -20px;
        overflow: hidden;
    }

    .all-umkm-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
        animation: float 15s ease-in-out infinite;
    }

    .all-umkm-hero::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 80px;
        background: #f8f9fa;
        clip-path: polygon(0 50%, 100% 0, 100% 100%, 0 100%);
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
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
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
        margin-bottom: 2rem;
        animation: fadeIn 1s ease-out;
    }

    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 2rem;
        animation: fadeIn 1.2s ease-out;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        display: block;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
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

    /* Container */
    .umkm-container {
        max-width: 1400px;
        margin: -60px auto 4rem;
        padding: 0 1.5rem;
        position: relative;
        z-index: 3;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        animation: slideUp 0.6s ease-out;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(61, 79, 102, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
        font-size: 1.1rem;
    }

    .filter-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.75rem 1.5rem;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #4a5568;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    /* Grid Layout */
    .umkm-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        animation: fadeIn 0.8s ease-out 0.2s both;
    }

    /* Card Design */
    .umkm-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .umkm-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
    }

    .umkm-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    .umkm-card:hover::before {
        opacity: 0.05;
    }

    .card-image-wrapper {
        position: relative;
        width: 100%;
        height: 240px;
        overflow: hidden;
        background: linear-gradient(135deg, #3d4f66 0%, #2c3e50 100%);
    }

    .card-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .umkm-card:hover .card-image-wrapper img {
        transform: scale(1.1);
    }

    .card-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .card-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        opacity: 0.5;
    }

    .card-content {
        padding: 1.75rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 2;
    }

    .card-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.75rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-description {
        font-size: 0.95rem;
        color: #718096;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin-bottom: 1.25rem;
    }

    .card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .card-btn {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .card-btn i {
        font-size: 1rem;
    }

    .btn-location {
        background: linear-gradient(135deg, #3d4f66 0%, #2c3e50 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-location::before {
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

    .btn-location:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-location:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(61, 79, 102, 0.4);
    }

    .btn-contact {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-contact::before {
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

    .btn-contact:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-contact:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(56, 239, 125, 0.4);
    }

    .btn-location span,
    .btn-contact span {
        position: relative;
        z-index: 1;
    }

    .btn-location i,
    .btn-contact i {
        position: relative;
        z-index: 1;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-md);
        animation: fadeIn 0.8s ease-out;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #3d4f66 0%, #2c3e50 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 8px 24px rgba(61, 79, 102, 0.2);
    }

    .empty-icon i {
        font-size: 3.5rem;
        color: white;
    }

    .empty-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .empty-subtitle {
        font-size: 1.1rem;
        color: #718096;
        max-width: 500px;
        margin: 0 auto;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .umkm-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-stats {
            gap: 2rem;
        }

        .stat-number {
            font-size: 2rem;
        }
    }

    @media (max-width: 768px) {
        .all-umkm-hero {
            padding: 5rem 0 4rem;
            margin-top: 0;
        }

        .hero-content {
            padding: 0 1.5rem;
        }

        .hero-badge {
            font-size: 0.85rem;
            padding: 0.6rem 1.2rem;
            margin-bottom: 1.25rem;
        }

        .hero-title {
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .hero-subtitle {
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .hero-stats {
            flex-direction: row;
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .stat-number {
            font-size: 1.75rem;
        }

        .stat-label {
            font-size: 0.75rem;
        }

        .umkm-container {
            margin-top: -40px;
            padding: 0 1rem;
        }

        .filter-section {
            flex-direction: column;
            align-items: stretch;
            padding: 1.25rem;
        }

        .search-box {
            min-width: 100%;
        }

        .search-box input {
            font-size: 0.95rem;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
        }

        .filter-buttons {
            justify-content: center;
            gap: 0.5rem;
        }

        .filter-btn {
            padding: 0.65rem 1.25rem;
            font-size: 0.85rem;
        }

        .umkm-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .card-image-wrapper {
            height: 200px;
        }

        .card-content {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.2rem;
        }

        .card-description {
            font-size: 0.9rem;
        }

        .card-actions {
            grid-template-columns: 1fr;
        }

        .card-btn {
            padding: 0.85rem 1rem;
        }

        .empty-icon {
            width: 100px;
            height: 100px;
        }

        .empty-icon i {
            font-size: 2.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
        }

        .empty-subtitle {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .all-umkm-hero {
            padding: 4rem 0 3.5rem;
        }

        .hero-title {
            font-size: 1.5rem;
        }

        .hero-subtitle {
            font-size: 0.9rem;
        }

        .hero-stats {
            gap: 1.5rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .stat-label {
            font-size: 0.7rem;
        }

        .filter-btn {
            flex: 1 1 calc(50% - 0.25rem);
            min-width: 120px;
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
<div class="all-umkm-hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-store"></i>
            <span>UMKM Desa Melung</span>
        </div>
        <h1 class="hero-title">Semua UMKM Aktif</h1>
        <p class="hero-subtitle">Temukan produk unggulan dan layanan berkualitas dari para pelaku usaha lokal</p>

        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number"><?= count($all_umkm) ?></span>
                <span class="stat-label">UMKM Terdaftar</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_unique(array_column($all_umkm, 'kategori'))) ?></span>
                <span class="stat-label">Kategori</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="umkm-container">
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari UMKM berdasarkan nama atau kategori...">
        </div>
        <div class="filter-buttons">
            <button class="filter-btn active" data-category="all">
                <i class="fas fa-th"></i> Semua
            </button>
            <button class="filter-btn" data-category="makanan">
                <i class="fas fa-utensils"></i> Makanan
            </button>
            <button class="filter-btn" data-category="minuman">
                <i class="fas fa-coffee"></i> Minuman
            </button>
            <button class="filter-btn" data-category="kerajinan">
                <i class="fas fa-palette"></i> Kerajinan
            </button>
            <button class="filter-btn" data-category="agribisnis">
                <i class="fas fa-leaf"></i> Agribisnis
            </button>
        </div>
    </div>

    <?php if (!empty($all_umkm)): ?>
        <!-- UMKM Grid -->
        <div class="umkm-grid" id="umkmGrid">
            <?php foreach ($all_umkm as $umkm): ?>
                <div class="umkm-card"
                    data-category="<?= strtolower(esc($umkm['kategori'] ?? 'umkm')) ?>"
                    data-name="<?= strtolower(esc($umkm['nama_umkm'])) ?>"
                    onclick="window.location='<?= base_url('umkm/detail/' . $umkm['id']) ?>'">
                    <div class="card-image-wrapper">
                        <?php if (!empty($umkm['foto_umkm'])): ?>
                            <img src="<?= base_url('uploads/foto_umkm/' . $umkm['foto_umkm']) ?>"
                                alt="<?= esc($umkm['nama_umkm']) ?>">
                        <?php else: ?>
                            <div class="card-image-placeholder">
                                <i class="fas fa-store"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-badge">
                            <?= esc($umkm['kategori'] ?? 'UMKM') ?>
                        </div>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title"><?= esc($umkm['nama_umkm']) ?></h3>
                        <p class="card-description">
                            <?= esc($umkm['deskripsi'] ? substr($umkm['deskripsi'], 0, 120) . '...' : 'Klik untuk melihat detail lengkap UMKM ini') ?>
                        </p>

                        <div class="card-divider"></div>

                        <div class="card-actions">
                            <a href="<?= esc($umkm['gmaps_url'] ?? '#') ?>"
                                target="_blank"
                                class="card-btn btn-location"
                                onclick="event.stopPropagation()">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Lokasi</span>
                            </a>
                            <a href="tel:<?= esc($umkm['kontak'] ?? '#') ?>"
                                class="card-btn btn-contact"
                                onclick="event.stopPropagation()">
                                <i class="fas fa-phone"></i>
                                <span>Hubungi</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-store"></i>
            </div>
            <h2 class="empty-title">Belum Ada UMKM Aktif</h2>
            <p class="empty-subtitle">Saat ini belum ada UMKM yang terdaftar. Silakan hubungi admin untuk informasi lebih lanjut.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const umkmCards = document.querySelectorAll('.umkm-card');

        let currentCategory = 'all';

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterCards(currentCategory, searchTerm);
        });

        // Filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.dataset.category;
                filterCards(currentCategory, searchInput.value.toLowerCase());
            });
        });

        function filterCards(category, searchTerm) {
            umkmCards.forEach(card => {
                const cardCategory = card.dataset.category;
                const cardName = card.dataset.name;

                const categoryMatch = category === 'all' || cardCategory.includes(category);
                const searchMatch = cardName.includes(searchTerm) || cardCategory.includes(searchTerm);

                if (categoryMatch && searchMatch) {
                    card.style.display = 'flex';
                    card.style.animation = 'fadeIn 0.5s ease-out';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>