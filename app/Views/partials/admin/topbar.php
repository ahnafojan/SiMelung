<nav class="navbar navbar-expand navbar-light bg-light topbar mb-4 static-top shadow-sm border-bottom" style="padding-top: 1.2rem; padding-bottom: 1.2rem;">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <!-- Topbar profile -->
    <ul class="navbar-nav ml-auto">
        <a class="nav-link dropdown-toggle fs-3" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                <?= session()->get('username') ?? 'Admin' ?>
            </span>
            <i class="fas fa-fw fa-user fa-lg"></i>
        </a>

        <!-- Menu Dropdown -->
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