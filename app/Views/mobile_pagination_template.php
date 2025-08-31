<?php

/**
 * Mobile Pagination Template - BUMDES Melung
 * File: app/Views/mobile_pagination_template.php
 */
?>

<div class="mobile-pagination-container">
    <?php if ($pager->hasPreviousPage()) : ?>
        <a href="<?= $pager->getPreviousPage() ?>" class="mobile-page-btn mobile-prev" aria-label="Halaman Sebelumnya">
            <i class="fas fa-chevron-left"></i>
        </a>
    <?php else : ?>
        <button class="mobile-page-btn mobile-prev" disabled aria-label="Halaman Sebelumnya">
            <i class="fas fa-chevron-left"></i>
        </button>
    <?php endif ?>

    <div class="mobile-page-info">
        <?php
        // Ambil current page dari URI atau default 1
        $currentPage = 1;
        $totalPages = 1;

        // Cari current page dari links
        foreach ($pager->links() as $link) {
            if ($link['active']) {
                $currentPage = (int)$link['title'];
                break;
            }
        }

        // Hitung total pages dari links terakhir
        $links = $pager->links();
        if (!empty($links)) {
            $lastLink = end($links);
            $totalPages = (int)$lastLink['title'];
        }
        ?>
        <span class="current-page"><?= $currentPage ?></span>
        <span class="page-separator">dari</span>
        <span class="total-pages"><?= $totalPages ?></span>
    </div>

    <?php if ($pager->hasNextPage()) : ?>
        <a href="<?= $pager->getNextPage() ?>" class="mobile-page-btn mobile-next" aria-label="Halaman Selanjutnya">
            <i class="fas fa-chevron-right"></i>
        </a>
    <?php else : ?>
        <button class="mobile-page-btn mobile-next" disabled aria-label="Halaman Selanjutnya">
            <i class="fas fa-chevron-right"></i>
        </button>
    <?php endif ?>
</div>