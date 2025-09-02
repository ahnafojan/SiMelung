<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Laporan Kopi') ?></title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .main-table thead th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        /* === KOP SURAT === */
        .kop-surat table,
        .kop-surat td {
            border: 0;
            padding: 0;
            vertical-align: middle;
        }

        .kop-logo {
            width: 20%;
            text-align: left;
        }

        .kop-logo img {
            width: 200px;
        }

        .kop-tengah {
            width: 60%;
            text-align: center;
        }

        .kop-kanan {
            width: 20%;
        }

        .kop-tengah h1,
        .kop-tengah h2,
        .kop-tengah p {
            margin: 0;
            padding: 1px 0;
        }

        .kop-tengah h1 {
            font-size: 18px;
            font-weight: bold;
        }

        .kop-tengah h2 {
            font-size: 24px;
            font-weight: bold;
            color: #005a8d;
        }

        .kop-tengah p {
            font-size: 11px;
        }

        .kop-line {
            border: 0;
            border-top: 3px solid black;
            margin: 5px 0 15px 0;
        }

        /* === JUDUL LAPORAN === */
        .report-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .report-title h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .report-title p {
            margin: 0;
            font-size: 11px;
        }

        /* === TANDA TANGAN === */
        .signature-section {
            margin-top: 50px;
        }

        .signature-section table,
        .signature-section td {
            border: 0;
        }

        .signature-section p {
            margin: 0;
        }

        .signature-section .underline {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="kop-surat">
        <table>
            <tr>
                <td class="kop-logo">
                    <?php if (!empty($logoBase64)): ?>
                        <img src="<?= $logoBase64 ?>" alt="Logo">
                    <?php endif; ?>
                </td>
                <td class="kop-tengah">
                    <h1>BADAN USAHA MILIK DESA (BUMDES)</h1>
                    <h2>ALAM LESTARI MELUNG</h2>
                    <p>Sekretariat : Jl. Raya Melung No 50-51 Kode Pos 53152</p>
                </td>
                <td class="kop-kanan">&nbsp;</td>
            </tr>
        </table>
    </div>
    <hr class="kop-line">

    <div class="report-title">
        <h3><?= esc($title ?? 'Laporan') ?></h3>
        <p><?= esc($subtitle ?? '') ?></p>
    </div>

    <table class="main-table">
        <thead>
            <!-- Header Tabel Dinamis Berdasarkan Tipe Laporan -->
            <?php if ($type === 'masuk'): ?>
                <tr>
                    <th>#</th>
                    <th>Nama Petani</th>
                    <th class="text-right">Total Masuk (Kg)</th>
                    <th class="text-center">Tgl Setor Terakhir</th>
                    <th class="text-center">Jml Transaksi</th>
                    <th class="text-right">Rata-rata (Kg)</th>
                </tr>
            <?php elseif ($type === 'keluar'): ?>
                <tr>
                    <th>#</th>
                    <th class="text-center">Tanggal</th>
                    <th>Jenis Kopi</th>
                    <th>Tujuan Pembeli</th>
                    <th class="text-right">Jumlah (Kg)</th>
                    <th>Keterangan</th>
                </tr>
            <?php elseif ($type === 'stok'): ?>
                <tr>
                    <th>#</th>
                    <th>Jenis Kopi</th>
                    <th class="text-right">Total Stok (Kg)</th>
                </tr>
            <?php endif; ?>
        </thead>
        <tbody>
            <?php if (!empty($data)): ?>
                <?php foreach ($data as $index => $item): ?>
                    <!-- Body Tabel Dinamis Berdasarkan Tipe Laporan -->
                    <?php if ($type === 'masuk'): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= esc($item['nama_petani']) ?></td>
                            <td class="text-right"><?= number_format($item['total_masuk'], 2, ',', '.') ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal_terakhir'])) ?></td>
                            <td class="text-center"><?= esc($item['jumlah_transaksi']) ?></td>
                            <td class="text-right"><?= number_format($item['rata_rata_setoran'], 2, ',', '.') ?></td>
                        </tr>
                    <?php elseif ($type === 'keluar'): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                            <td><?= esc($item['jenis_kopi']) ?></td>
                            <td><?= esc($item['tujuan']) ?></td>
                            <td class="text-right"><?= number_format($item['jumlah'], 2, ',', '.') ?></td>
                            <td><?= esc($item['keterangan']) ?></td>
                        </tr>
                    <?php elseif ($type === 'stok'): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= esc($item['jenis_kopi']) ?></td>
                            <td class="text-right"><?= number_format($item['stok_akhir'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <table>
            <tr>
                <td style="width: 50%; text-align: center;">
                    <p>Mengetahui,</p>
                    <p>Ketua BUMDES</p>
                    <div style="height: 60px;"></div>
                    <p class="underline">
                        <?= esc($namaKetua ?? '_________________') ?>
                    </p>
                </td>
                <td style="width: 50%; text-align: center;">
                    <p>
                        <?= esc($lokasi ?? 'Lokasi') ?>, <?= date('d F Y') ?>
                    </p>
                    <p>
                        <?= esc($jabatanKanan ?? 'Admin Komersial') ?>
                    </p>
                    <div style="height: 60px;"></div>
                    <p class="underline">
                        <?= esc($namaKanan ?? '_________________') ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>