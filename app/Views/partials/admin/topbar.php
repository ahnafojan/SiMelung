<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for...">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <!-- Topbar profile -->
    <li class="nav-item dropdown no-arrow d-flex align-items-center">
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
    </li>


</nav>