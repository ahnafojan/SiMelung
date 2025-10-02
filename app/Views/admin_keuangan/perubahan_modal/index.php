<?= $this->extend('admin_keuangan/layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    /* Style Global & Helper yang Digunakan di Halaman Ini */
    input[readonly] {
        background-color: #e9ecef;
        cursor: not-allowed;
        opacity: 1;
    }

    .input-group>input[readonly].form-control,
    .input-group .form-control {
        border-left: 0;
    }

    .input-group-text {
        background-color: #e9ecef;
        border-right: 0;
    }

    .input-group {
        align-items: center;
    }

    /* Style Final untuk Laporan Perubahan Modal */
    .laporan-section {
        margin-bottom: 2rem;
    }

    .laporan-section-header {
        font-size: 1rem;
        font-weight: bold;
        color: #4e73df;
        /* Warna primer template */
        border-bottom: 2px solid #eaecf4;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .laporan-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eaecf4;
    }

    .laporan-item label {
        margin-bottom: 0;
        padding-right: 1rem;
    }

    .laporan-item .input-group {
        max-width: 50%;
    }

    /* Bagian Total Akhir */
    .laporan-total {
        background-color: #f8f9fc;
    }

    .laporan-total-label {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .laporan-total-nilai {
        font-weight: bold;
        font-size: 1.25rem;
        color: #1cc88a;
        /* Warna success */
    }

    #modal-akhir-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }

    /* Style untuk efek flash pada total */
    #modal-akhir-container.highlight-update {
        animation: flash-subtle 0.7s ease-out;
    }

    @keyframes flash-subtle {
        from {
            background-color: #fcf8e3;
        }

        to {
            background-color: transparent;
        }
    }

    /* Tampilan Responsif untuk Laporan */
    @media (max-width: 576px) {
        .laporan-item {
            flex-direction: column;
            /* Label di atas, input di bawah */
            align-items: flex-start;
            gap: 0.5rem;
        }

        .laporan-item .input-group {
            width: 100%;
            max-width: 100%;
        }

        #modal-akhir-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>

    <div class="card shadow mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-2"></i> Filter Laporan
        </div>
        <div class="card-body">
            <form action="<?= base_url('/perubahan-modal') ?>" method="get" class="row align-items-end">
                <div class="col-md-3">
                    <label for="tahun" class="form-label">Tahun Periode</label>
                    <select name="tahun" id="tahun" class="form-select">
                        <option value="">Pilih Tahun</option>
                        <?php if (!empty($daftar_tahun)): ?>
                            <?php foreach ($daftar_tahun as $item): ?>
                                <option value="<?= esc($item['tahun'] ?? '') ?>" <?= (($item['tahun'] ?? '') == $tahun_terpilih) ? 'selected' : '' ?>>
                                    <?= esc($item['tahun'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($tahun_terpilih)) : ?>

        <div class="card shadow mb-4">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h5 class="mb-2 mb-sm-0">Periode Tahun: <?= esc($tahun_terpilih) ?></h5>
                <div class="btn-group">
                    <a href="<?= site_url('perubahan-modal/export-excel/' . $tahun_terpilih) ?>" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </a>
                    <a href="<?= site_url('perubahan-modal/export-pdf/' . $tahun_terpilih) ?>" class="btn btn-outline-danger btn-sm" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </a>
                </div>
            </div>
            <form action="<?= base_url('/perubahan-modal/simpan') ?>" method="post">
                <div class="card-body p-sm-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tahun" value="<?= esc($tahun_terpilih) ?>">
                    <input type="hidden" name="laba_rugi_bersih" value="<?= $laba_rugi_bersih ?>">

                    <div class="laporan-section">
                        <h6 class="laporan-section-header">Penambahan</h6>
                        <div class="laporan-item">
                            <label>Laba/Rugi Bersih</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control text-end" value="<?= number_format($laba_rugi_bersih, 0, ',', '.') ?>" readonly>
                            </div>
                        </div>
                        <?php foreach ($komponen as $item): if ($item['kategori'] == 'penambahan'): ?>
                                <div class="laporan-item">
                                    <label><?= esc($item['nama_komponen']) ?></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="jumlah[<?= $item['id'] ?>]" class="form-control text-end input-rupiah" data-kategori="penambahan" value="<?= number_format($detail_map[$item['id']] ?? 0, 0, ',', '.') ?>">
                                    </div>
                                </div>
                        <?php endif;
                        endforeach; ?>
                    </div>

                    <div class="laporan-section">
                        <h6 class="laporan-section-header">Pengurangan</h6>
                        <?php foreach ($komponen as $item): if ($item['kategori'] == 'pengurangan'): ?>
                                <div class="laporan-item">
                                    <label><?= esc($item['nama_komponen']) ?></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="jumlah[<?= $item['id'] ?>]" class="form-control text-end input-rupiah" data-kategori="pengurangan" value="<?= number_format($detail_map[$item['id']] ?? 0, 0, ',', '.') ?>">
                                    </div>
                                </div>
                        <?php endif;
                        endforeach; ?>
                    </div>
                </div>

                <div class="card-footer laporan-total">
                    <div id="modal-akhir-container">
                        <span class="laporan-total-label">Modal Akhir (per 31 Desember <?= esc($tahun_terpilih) ?>)</span>
                        <span class="laporan-total-nilai" id="modal-akhir">[Hasil Perhitungan]</span>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Laporan
                    </button>
                </div>
            </form>
        </div>

    <?php else: ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            Silakan pilih tahun periode terlebih dahulu untuk menampilkan laporan.
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<?php if (!empty($tahun_terpilih)) : ?>
    <script>
        // Script ini hanya akan dieksekusi jika bagian laporan ditampilkan
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk mengubah string format Rupiah menjadi angka
            function parseNumber(str) {
                // Menghapus semua karakter kecuali digit dan tanda minus
                return parseFloat(String(str).replace(/[^\d-]/g, '')) || 0;
            }

            // Fungsi untuk memformat angka menjadi format Rupiah (IDR)
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            // Fungsi utama untuk menghitung total modal akhir
            function calculateTotal() {
                let labaBersih = parseNumber('<?= $laba_rugi_bersih ?>');
                let totalPenambahan = labaBersih;
                let totalPengurangan = 0;

                document.querySelectorAll('.input-rupiah').forEach(input => {
                    let nilai = parseNumber(input.value);
                    if (input.dataset.kategori === 'penambahan') {
                        totalPenambahan += nilai;
                    } else {
                        totalPengurangan += nilai;
                    }
                });

                let modalAkhir = totalPenambahan - totalPengurangan;
                document.getElementById('modal-akhir').innerText = 'Rp ' + formatNumber(modalAkhir);

                // Memberi efek highlight saat nilai berubah
                const totalContainer = document.getElementById('modal-akhir-container');
                totalContainer.classList.remove('highlight-update');
                void totalContainer.offsetWidth; // Trik untuk memicu ulang animasi CSS
                totalContainer.classList.add('highlight-update');
            }

            // Inisialisasi IMask pada semua input dengan class .input-rupiah
            function initMask() {
                document.querySelectorAll('.input-rupiah').forEach(function(input) {
                    const mask = IMask(input, {
                        mask: Number,
                        scale: 0,
                        signed: false,
                        thousandsSeparator: '.',
                        min: 0
                    });

                    // Setiap kali nilai input berubah, panggil fungsi kalkulasi ulang
                    mask.on('accept', function() {
                        calculateTotal();
                    });
                });
            }

            // Jalankan fungsi saat halaman selesai dimuat
            initMask();
            calculateTotal(); // Hitung total awal saat halaman dimuat
        });
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>