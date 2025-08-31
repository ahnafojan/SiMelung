<?php

/**
 * Custom Pagination Template - BUMDES Melung
 * File: app/Views/custom_pagination_template.php
 */

$pager->setSurroundCount(2);
?>

<ul class="pagination pagination-list">
    <?php if ($pager->hasPreviousPage()) : ?>
        <li class="pagination-item">
            <a href="<?= $pager->getPreviousPage() ?>" class="pagination-link pagination-prev" aria-label="<?= lang('Pager.previous') ?>" rel="prev">
                <i class="fas fa-chevron-left"></i>
                <span class="d-none d-sm-inline ml-2">Sebelumnya</span>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <li class="pagination-item <?= $link['active'] ? 'active' : '' ?>">
            <?php if ($link['active']) : ?>
                <span class="pagination-link pagination-active" aria-current="page"><?= $link['title'] ?></span>
            <?php else : ?>
                <a href="<?= $link['uri'] ?>" class="pagination-link"><?= $link['title'] ?></a>
            <?php endif ?>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNextPage()) : ?>
        <li class="pagination-item">
            <a href="<?= $pager->getNextPage() ?>" class="pagination-link pagination-next" aria-label="<?= lang('Pager.next') ?>" rel="next">
                <span class="d-none d-sm-inline mr-2">Selanjutnya</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    <?php endif ?>
</ul>