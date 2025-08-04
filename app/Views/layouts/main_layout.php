<!DOCTYPE html>
<html lang="en">

<?= $this->include('partials/partials_admin/head') ?>

<body id="page-top" class="bg-background">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?= $this->include('partials/partials_admin/sidebar') ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $this->include('partials/partials_admin/topbar') ?>

                <!-- Page Content -->
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>

            </div>

            <!-- Footer -->
            <?= $this->include('partials/partials_admin/footer') ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scripts -->
    <?= $this->include('partials/partials_admin/scripts') ?>

</body>

</html>