<!DOCTYPE html>
<html>

<head>
    <title>Laporan Aset Pariwisata</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #ccc;
        }

        td img {
            width: 60px;
            height: 40px;
            object-fit: cover;
        }

        .text-left {
            text-align: left;
        }

        .small-text {
            font-size: 10px;
            color: #555;
            display: block;
        }
    </style>
</head>

<body>
    <h2>Laporan Aset Pariwisata</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pariwisata</th>
                <th>Foto Aset</th>
                <th>Nama Aset</th>
                <th>Kode & NUP</th>
                <th>Tahun</th>
                <th>Nilai Perolehan</th>
                <th>Pengadaan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($asets as $aset): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td class="text-left"><?= $aset['nama_pariwisata'] ?></td>
                    <td>
                        <?php if ($aset['foto_aset']): ?>
                            <img src="<?= base_url('uploads/aset_pariwisata/' . $aset['foto_aset']) ?>" alt="<?= $aset['nama_aset'] ?>">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="text-left"><?= $aset['nama_aset'] ?></td>
                    <td>
                        <?= $aset['kode_aset'] ?>
                        <span class="small-text">NUP: <?= $aset['nup'] ?: '-' ?></span>
                    </td>
                    <td><?= $aset['tahun_perolehan'] ?></td>
                    <td>Rp <?= number_format($aset['nilai_perolehan'], 0, ',', '.') ?></td>
                    <td class="text-left">
                        <?= $aset['metode_pengadaan'] ?>
                        <span class="small-text"><?= $aset['sumber_pengadaan'] ?></span>
                    </td>
                    <td class="text-left"><?= $aset['keterangan'] ?: '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>