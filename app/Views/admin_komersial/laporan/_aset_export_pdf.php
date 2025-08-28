<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Laporan Aset Produksi') ?></title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
        }

        .header p {
            margin: 5px 0;
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        thead th {
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
    </style>
</head>

<body>

    <div class="header">
        <h1><?= esc($title ?? 'Laporan Aset Produksi') ?></h1>
        <p>Filter Tahun: <?= esc($filterTahun == 'semua' ? 'Semua Tahun' : $filterTahun) ?></p>
        <p>Dicetak pada: <?= date('d F Y, H:i:s') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Aset</th>
                <th>Kode</th>
                <th>NUP</th>
                <th>Tahun</th>
                <th>Merk/Tipe</th>
                <th class="text-right">Nilai (Rp)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($asetData)): ?>
                <?php foreach ($asetData as $index => $item): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td><?= esc($item['nama_aset']) ?></td>
                        <td class="text-center"><?= esc($item['kode_aset']) ?></td>
                        <td class="text-center"><?= esc($item['nup']) ?></td>
                        <td class="text-center"><?= esc($item['tahun_perolehan']) ?></td>
                        <td><?= esc($item['merk_type']) ?></td>
                        <td class="text-right"><?= number_format($item['nilai_perolehan'], 0, ',', '.') ?></td>
                        <td><?= esc($item['keterangan']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>