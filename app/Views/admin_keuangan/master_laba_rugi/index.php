<?= $this->extend('admin_keuangan/layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <div class="mt-2 mt-sm-0">
                <a href="<?= site_url('/master-laba-rugi/new'); ?>" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Tambah Komponen</a>
            </div>
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pendapatan-tab" data-bs-toggle="tab" data-bs-target="#pendapatan-pane" type="button" role="tab">
                        <i class="fas fa-arrow-down text-success me-1"></i>Pendapatan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="biaya-tab" data-bs-toggle="tab" data-bs-target="#biaya-pane" type="button" role="tab">
                        <i class="fas fa-arrow-up text-danger me-1"></i>Biaya
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="pendapatan-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Komponen</th>
                                    <th class="text-end" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($komponen['pendapatan'])): ?>
                                    <tr>
                                        <td colspan="2" class="text-center fst-italic">Belum ada komponen pendapatan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($komponen['pendapatan'] as $item): ?>
                                        <tr>
                                            <td class="align-middle"><?= esc($item['nama_komponen']); ?></td>
                                            <td class="text-end">
                                                <form action="<?= site_url('/master-laba-rugi/' . $item['id']); ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komponen ini?');">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="biaya-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Komponen</th>
                                    <th class="text-end" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($komponen['biaya'])): ?>
                                    <tr>
                                        <td colspan="2" class="text-center fst-italic">Belum ada komponen biaya.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($komponen['biaya'] as $item): ?>
                                        <tr>
                                            <td class="align-middle"><?= esc($item['nama_komponen']); ?></td>
                                            <td class="text-end">
                                                <form action="<?= site_url('/master-laba-rugi/' . $item['id']); ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komponen ini?');">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>