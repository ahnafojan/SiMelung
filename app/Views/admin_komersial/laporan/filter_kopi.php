<!-- Filter Laporan Rekap Kopi -->
<div class="card shadow border-0 mb-4 animated--grow-in">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <h6 class="m-0 text-filter-title">
            <i class="fas fa-filter mr-2"></i> Filter Laporan Rekap Kopi
        </h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin-komersial/laporan') ?>" method="get" class="row align-items-end">
            <div class="col-lg-3 col-md-6 mb-3">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-left-pill"><i class="far fa-calendar-alt text-secondary"></i></span>
                    </div>
                    <input type="date" id="start_date" name="start_date" value="<?= esc($filter['start_date']) ?>" class="form-control rounded-right-pill">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-left-pill"><i class="far fa-calendar-alt text-secondary"></i></span>
                    </div>
                    <input type="date" id="end_date" name="end_date" value="<?= esc($filter['end_date']) ?>" class="form-control rounded-right-pill">
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="petani" class="form-label">Petani</label>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-left-pill"><i class="fas fa-user-tag text-secondary"></i></span>
                    </div>
                    <select id="petani" name="petani" class="form-control rounded-right-pill">
                        <option value="">-- Semua Petani --</option>
                        <?php foreach ($petaniList as $p): ?>
                            <option value="<?= $p['user_id'] ?>" <?= ($filter['petani'] == $p['user_id']) ? 'selected' : '' ?>>
                                <?= esc($p['nama_petani']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <div class="d-flex w-100">
                    <button type="submit" class="btn btn-primary btn-block mr-2">
                        <i class="fas fa-search mr-1"></i> Tampilkan
                    </button>
                    <a href="<?= base_url('admin-komersial/laporan') ?>" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-sync-alt mr-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>