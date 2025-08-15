<!DOCTYPE html>
<html lang="en">

<?= $this->include('partials/admin/head') ?>

<body id="page-top" class="bg-background">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        $role = session()->get('role');

        switch ($role) {
            case 'desa':
                $sidebarPath = 'partials/admin/sidebar/sidebar_desa';
                break;
            case 'bumdes':
                $sidebarPath = 'partials/admin/sidebar/sidebar_bumdes';
                break;
            case 'keuangan':
                $sidebarPath = 'partials/admin/sidebar/sidebar_keuangan';
                break;
            case 'umkm':
                $sidebarPath = 'partials/admin/sidebar/sidebar_umkm';
                break;
            case 'komersial':
                $sidebarPath = 'partials/admin/sidebar/sidebar_komersial';
                break;
            case 'pariwisata':
                $sidebarPath = 'partials/admin/sidebar/sidebar_pariwisata';
                break;
            default:
                $sidebarPath = 'partials/admin/sidebar/sidebar_default';
                break;
        }

        echo $this->include($sidebarPath);
        ?>


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $this->include('partials/admin/topbar') ?>

                <!-- Page Content -->
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>

            </div>

            <!-- Footer -->
            <?= $this->include('partials/admin/footer') ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scripts -->
    <?= $this->include('partials/admin/scripts') ?>

</body>

</html>