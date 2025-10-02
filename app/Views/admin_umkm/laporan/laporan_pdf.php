<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Laporan Data UMKM Desa Melung') ?></title>
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

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* === KOP SURAT === */
        .kop-surat table, .kop-surat td {
            border: 0;
            padding: 0;
            vertical-align: middle;
        }

        .kop-logo { width: 20%; text-align: left; }
        .kop-logo img { width: 100px; }

        .kop-tengah { width: 60%; text-align: center; }
        .kop-kanan { width: 20%; }

        .kop-tengah h1, .kop-tengah h2, .kop-tengah p {
            margin: 0;
            padding: 1px 0;
        }

        .kop-tengah h1 {
            font-size: 16px;
            font-weight: bold;
        }

        .kop-tengah h2 {
            font-size: 20px;
            font-weight: bold;
            color: #005a8d;
        }

        .kop-tengah p { font-size: 11px; }

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

        .signature-section table, .signature-section td { border: 0; }
        .signature-section p { margin: 0; }
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
                        <img src="<?= $logoBase64 ?>" alt="Logo Desa Melung">
                    <?php endif; ?>
                </td>
                <td class="kop-tengah">
                    <h1>LAPORAN DATA UMKM</h1>
                    <h2>DESA MELUNG</h2>
                    <p>Sekretariat: Jl. Raya Melung No. 50, Kode Pos 53152</p>
                </td>
                <td class="kop-kanan">&nbsp;</td>
            </tr>
        </table>
    </div>
    <hr class="kop-line">

    <div class="report-title">
        <h3><?= esc($title ?? 'Laporan Data UMKM Desa Melung') ?></h3>
        <p><?= esc($subtitle ?? 'Daftar lengkap UMKM Desa Melung') ?></p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama UMKM</th>
                <th>Deskripsi</th>
                <th>Pemilik</th>
                <th>Alamat</th>
                <th>Kontak</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($umkm)): ?>
                <?php foreach ($umkm as $index => $item): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td><?= esc($item['nama_umkm']) ?></td>
                        <td><?= esc($item['deskripsi']) ?></td>
                        <td><?= esc($item['pemilik']) ?></td>
                        <td><?= esc($item['alamat']) ?></td>
                        <td><?= esc($item['kontak']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data UMKM.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <table>
            <tr>
                <td style="width: 50%; text-align: center;">
                    <p>Mengetahui,</p>
                    <p>Ketua BUMDES Desa Melung</p>
                    <div style="height: 60px;"></div>
                    <p class="underline">
                        <?= esc($namaKetua ?? '_________________') ?>
                    </p>
                </td>
                <td style="width: 50%; text-align: center;">
                    <p>Desa Melung, <?= date('d F Y') ?></p>
                    <p><?= esc($jabatanKanan ?? 'Sekretaris') ?></p>
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
