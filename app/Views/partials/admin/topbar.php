<style>
    /* Breadcrumb terintegrasi dengan topbar */
    .topbar-breadcrumb {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
        list-style: none !important;
        display: flex !important;
        align-items: center !important;
    }

    .topbar-breadcrumb-item {
        display: flex !important;
        align-items: center !important;
    }

    .topbar-breadcrumb-link {
        color: #858796 !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        padding: 0.25rem 0.5rem !important;
        border-radius: 4px !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        align-items: center !important;
        font-size: 0.875rem !important;
    }

    .topbar-breadcrumb-link:hover {
        color: #4e73df !important;
        background: rgba(78, 115, 223, 0.1) !important;
        text-decoration: none !important;
    }

    .topbar-breadcrumb-separator {
        color: #d1d3e2 !important;
        margin: 0 0.5rem !important;
        font-size: 0.75rem !important;
    }

    .topbar-breadcrumb-item.active {
        background: #4e73df !important;
        color: white !important;
        padding: 0.25rem 0.75rem !important;
        border-radius: 4px !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        display: flex !important;
        align-items: center !important;
    }

    .topbar-breadcrumb-icon {
        margin-right: 0.375rem !important;
        font-size: 0.75rem !important;
    }

    /* Responsive - hide breadcrumb on small screens */
    @media (max-width: 768px) {
        .topbar-breadcrumb {
            display: none !important;
        }
    }
</style>

<nav class="navbar navbar-expand navbar-light bg-light topbar mb-4 static-top shadow-sm border-bottom" style="padding-top: 1.2rem; padding-bottom: 1.2rem;">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Breadcrumb terintegrasi langsung dalam topbar -->
    <ol class="topbar-breadcrumb d-none d-md-flex">
        <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
            <?php foreach ($breadcrumbs as $key => $crumb): ?>

                <?php if ($key > 0): ?>
                    <li class="topbar-breadcrumb-separator">
                        <i class="fas fa-chevron-right"></i>
                    </li>
                <?php endif; ?>

                <?php if ($key === array_key_last($breadcrumbs)): ?>
                    <li class="topbar-breadcrumb-item active" aria-current="page">
                        <?php if (!empty($crumb['icon'])): ?>
                            <i class="<?= esc($crumb['icon'], 'attr') ?> topbar-breadcrumb-icon"></i>
                        <?php endif; ?>
                        <?= esc($crumb['title']) ?>
                    </li>
                <?php else: ?>
                    <li class="topbar-breadcrumb-item">
                        <a href="<?= esc($crumb['url'], 'attr') ?>" class="topbar-breadcrumb-link">
                            <?php if (!empty($crumb['icon'])): ?>
                                <i class="<?= esc($crumb['icon'], 'attr') ?> topbar-breadcrumb-icon"></i>
                            <?php endif; ?>
                            <?= esc($crumb['title']) ?>
                        </a>
                    </li>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php endif; ?>
    </ol>

    <ul class="navbar-nav ml-auto">
        <a class="nav-link dropdown-toggle fs-3" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                <?= session()->get('username') ?? 'Admin' ?>
            </span>
            <i class="fas fa-fw fa-user fa-lg"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            <?php if (!empty($userRoles)): ?>
                <?php foreach ($userRoles as $role): ?>
                    <a class="dropdown-item" href="<?= site_url('switch-role/' . urlencode($role['role'])) ?>">
                        <i class="fas fa-exchange-alt fa-sm fa-fw me-2 text-gray-400"></i>
                        Ganti ke <?= ucfirst(esc($role['role'])) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= site_url('logout') ?>">
                <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </ul>
</nav>