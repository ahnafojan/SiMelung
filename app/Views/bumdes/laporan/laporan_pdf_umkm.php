<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Laporan Data UMKM') ?></title>
    <style>
        @page {
            margin: 20px 25px;
        }

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
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        .main-table thead th {
            background-color: #EFEFEF;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* === KOP SURAT - Perbaikan Centering === */
        .kop-surat {
            margin-bottom: 5px;
        }

        /* clear fix */
        .kop-surat::after {
            content: "";
            clear: both;
            display: table;
        }

        .kop-kiri,
        .kop-tengah,
        .kop-kanan {
            float: left;
            vertical-align: middle;
        }

        .kop-kiri,
        .kop-kanan {
            width: 15%;
        }

        .kop-kiri {
            text-align: center;
        }

        .kop-kiri img {
            width: 75px;
            height: auto;
        }

        .kop-tengah {
            width: 70%;
            text-align: center;
        }

        .kop-tengah h1,
        .kop-tengah h2,
        .kop-tengah p {
            margin: 0;
        }

        .kop-tengah h1 {
            font-size: 16px;
            font-weight: bold;
        }

        .kop-tengah h2 {
            font-size: 20px;
            font-weight: bold;
        }

        .kop-tengah p {
            font-size: 11px;
        }

        .kop-line {
            clear: both;
            border: 0;
            border-top: 2px solid black;
            border-bottom: 1px solid black;
            margin: 0 0 15px 0;
            padding: 1px;
        }

        /* === JUDUL LAPORAN === */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h3 {
            margin: 0 0 3px 0;
            font-size: 14px;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .report-title p {
            margin: 0;
            font-size: 12px;
        }

        /* === TANDA TANGAN === */
        .signature-section {
            margin-top: 30px;
        }

        .signature-box {
            width: 35%;
            float: right;
            text-align: center;
        }

        .signature-box p {
            margin: 0;
            padding-bottom: 2px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="kop-surat">
        <div class="kop-kiri">
            <!-- PENGGUNAAN BASE64 DARI CONTROLLER -->
            <?php if (!empty($logoBase64)): ?>
                <img src="data:BUMDESS.png;base64,<?= $logoBase64 ?>" alt="Logo BUMDES">
            <?php endif; ?>
        </div>
        <div class="kop-tengah">
            <h1>BADAN USAHA MILIK DESA</h1>
            <!-- MENGGUNAKAN VARIABEL NAMA BUMDES -->
            <h2>"<?= esc(strtoupper($namaPokdarwis ?? 'ALAM LESTARI')) ?>"</h2>
            <p>DESA MELUNG KECAMATAN KEDUNGBANTENG KABUPATEN BANYUMAS</p>
            <p>Sekretariat: Kantor Kepala Desa Melung Kode Pos 53152</p>
        </div>
        <div class="kop-kanan">
            &nbsp;
        </div>
    </div>

    <div class="kop-line"></div>

    <div class="report-title">
        <!-- JUDUL DISESUAIKAN UNTUK UMKM -->
        <h3>LAPORAN DATA UMKM</h3>
        <p><?= esc($subtitle ?? 'Daftar Usaha Mikro, Kecil, dan Menengah') ?></p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th>Nama UMKM</th>
                <th>Deskripsi</th>
                <th style="width:15%;">Pemilik</th>
                <th style="width:15%;">Alamat</th>
                <th style="width:15%;">Kontak</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($umkmData)): ?>
                <?php foreach ($umkmData as $index => $item): ?>
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
                    <td colspan="6" class="text-center">Tidak ada data UMKM yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>
                <?= esc($lokasi ?? 'Melung') ?>, <?= date('d F Y') ?>
            </p>
            <p>
                <!-- JABATAN DISESUAIKAN -->
                <?= esc($jabatanPenandatangan ?? 'Ketua BUMDES') ?>
            </p>
            <div style="height: 60px;"></div>
            <p class="signature-name">
                <?= esc($namaPenandatangan ?? '_________________') ?>
            </p>
        </div>
    </div>

</body>

</html>