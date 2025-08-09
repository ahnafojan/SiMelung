<!DOCTYPE html>
<html lang="en">

<?= $this->include('partials/admin/head') ?>

<body id="page-top" class="bg-background">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?= $this->include(session()->get('sidebar') ?? 'partials/admin/sidebar/sidebar_desa') ?>

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