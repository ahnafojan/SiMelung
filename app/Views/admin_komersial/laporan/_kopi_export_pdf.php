<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .summary {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2><?= esc($title) ?></h2>

    <?php if ($type === 'masuk'): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Petani</th>
                    <th>Total Masuk (Kg)</th>
                    <th>Tanggal Setor Terakhir</th>
                    <th>Jumlah Transaksi</th>
                    <th>Rata-rata Setoran (Kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data as $row): ?>
                    <tr>
                        <td class="center"><?= $no++ ?></td>
                        <td><?= esc($row['nama_petani']) ?></td>
                        <td class="right"><?= number_format($row['total_masuk'], 2) ?></td>
                        <td class="center"><?= esc($row['tanggal_terakhir']) ?></td>
                        <td class="center"><?= $row['jumlah_transaksi'] ?></td>
                        <td class="right"><?= number_format($row['rata_rata_setoran'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($type === 'keluar'): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis Kopi</th>
                    <th>Tujuan Pembeli</th>
                    <th>Jumlah (Kg)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data as $row): ?>
                    <tr>
                        <td class="center"><?= $no++ ?></td>
                        <td><?= $row['tanggal'] ?? '-' ?></td>
                        <td><?= esc($row['jenis_kopi']) ?></td>
                        <td><?= esc($row['tujuan']) ?></td>
                        <td class="right"><?= number_format($row['jumlah'], 2) ?> Kg</td>
                        <td><?= esc($row['keterangan']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($type === 'stok'): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Kopi</th>
                    <th>Total Stok (Kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                $total = 0;
                foreach ($data as $row): ?>
                    <tr>
                        <td class="center"><?= $no++ ?></td>
                        <td><?= esc($row['jenis_kopi']) ?></td>
                        <td class="right"><?= number_format($row['stok_akhir'], 2) ?></td>
                    </tr>
                <?php $total += $row['stok_akhir'];
                endforeach; ?>
            </tbody>
        </table>
        <div class="summary">Total Stok Akhir Global: <?= number_format($total, 2) ?> Kg</div>
    <?php endif; ?>

</body>

</html>