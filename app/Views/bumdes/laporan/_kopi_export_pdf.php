<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Laporan Kopi BUMDES') ?></title>
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
            width: 140px;
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

        /* === FOOTER TABLE === */
        .main-table tfoot td {
            border: 1px solid #999;
            padding: 8px 6px;
            font-size: 11px;
            background-color: #f2f2f2;
        }

        .main-table tfoot tr {
            page-break-inside: avoid;
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

    <!-- SUMMARY BOX (Optional untuk laporan lebih informatif) -->
    <?php if ($type === 'masuk_dengan_harga' && !empty($data)): ?>
        <?php
        $totalMasukSum = array_sum(array_column($data, 'total_masuk'));
        $totalNilaiMasukSum = array_sum(array_column($data, 'total_nilai_masuk'));
        $totalTransaksiSum = array_sum(array_column($data, 'jumlah_transaksi'));
        $jumlahPetani = count($data);
        ?>
        <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9;">
            <table style="width: 100%; border: 0;">
                <tr>
                    <td style="width: 50%; border: 0; padding: 3px;">
                        <strong>Jumlah Petani:</strong> <?= $jumlahPetani ?> Petani
                    </td>
                    <td style="width: 50%; border: 0; padding: 3px;">
                        <strong>Total Transaksi:</strong> <?= $totalTransaksiSum ?> Transaksi
                    </td>
                </tr>
                <tr>
                    <td style="border: 0; padding: 3px;">
                        <strong>Total Kopi Masuk:</strong> <?= number_format($totalMasukSum, 2, ',', '.') ?> Kg
                    </td>
                    <td style="border: 0; padding: 3px;">
                        <strong>Total Nilai Pembelian:</strong> Rp <?= number_format($totalNilaiMasukSum, 0, ',', '.') ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <?php if ($type === 'keluar_dengan_harga' && !empty($data)): ?>
        <?php
        $totalKeluarSum = array_sum(array_column($data, 'jumlah_kg'));
        $totalNilaiJualSum = array_sum(array_column($data, 'total_nilai_jual'));
        $jumlahTransaksi = count($data);
        ?>
        <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9;">
            <table style="width: 100%; border: 0;">
                <tr>
                    <td style="width: 50%; border: 0; padding: 3px;">
                        <strong>Jumlah Transaksi:</strong> <?= $jumlahTransaksi ?> Transaksi
                    </td>
                    <td style="width: 50%; border: 0; padding: 3px;">
                        <strong>Total Kopi Keluar:</strong> <?= number_format($totalKeluarSum, 2, ',', '.') ?> Kg
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 0; padding: 3px;">
                        <strong>Total Nilai Penjualan:</strong> Rp <?= number_format($totalNilaiJualSum, 0, ',', '.') ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
    <?php if ($type === 'stok_dengan_nilai' && !empty($data)): ?>
        <?php
        $totalStokSum = array_sum(array_column($data, 'stok_akhir'));
        $jumlahJenisKopi = count($data);
        $totalNilaiStok = 0;
        foreach ($data as $item) {
            $totalNilaiStok += ($item['stok_akhir'] ?? 0) * ($item['harga_jual_per_kg'] ?? 0);
        }
        ?>
        <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9;">
            <table style="width: 100%; border: 0;">
                <tr>
                    <td style="width: 50%; border: 0; padding: 3px;">
                        <strong>Jumlah Jenis Kopi:</strong> <?= $jumlahJenisKopi ?> Jenis
                    </td>
                    <td style="width: 50%; border: 0; padding: 3px;">
                        <strong>Total Stok:</strong> <?= number_format($totalStokSum, 2, ',', '.') ?> Kg
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>


    <table class="main-table">
        <thead>
            <!-- Header Tabel Dinamis Berdasarkan Tipe Laporan -->
            <!-- Header untuk masuk_dengan_harga -->
            <?php if ($type === 'masuk_dengan_harga'): ?>
                <tr>
                    <th>#</th>
                    <th>Nama Petani</th>
                    <th>Jenis Kopi</th>
                    <th class="text-right">Total Masuk (Kg)</th>
                    <th class="text-right">Total Harga Masuk (Rp)</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Jml Transaksi</th>
                    <th class="text-right">Rata-rata Setoran (Kg)</th>
                </tr>
            <?php elseif ($type === 'keluar_dengan_harga'): ?>
                <tr>
                    <th>#</th>
                    <th class="text-center">Tanggal</th>
                    <th>Nama Petani</th>
                    <th>Jenis Kopi</th>
                    <th>Tujuan Pembeli</th>
                    <th class="text-right">Jumlah (Kg)</th>
                    <th class="text-right">Harga Jual (Rp/Kg)</th>
                    <th class="text-right">Keuntungan BUMDes (Rp)</th>
                    <th class="text-right">Total Harga Jual Petani (Rp)</th>
                    <th>Keterangan</th>
                </tr>
            <?php elseif ($type === 'stok_dengan_nilai'): ?>
                <tr>
                    <th>#</th>
                    <th>Jenis Kopi</th>
                    <th class="text-right">Total Stok (Kg)</th>
                </tr>
            <?php elseif ($type === 'masuk'): ?>
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
                    <!-- Body untuk masuk_dengan_harga -->
                    <?php if ($type === 'masuk_dengan_harga'): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= esc($item['nama_petani']) ?></td>
                            <td><?= esc($item['jenis_kopi']) ?></td>
                            <td class="text-right"><?= number_format($item['total_masuk'], 2, ',', '.') ?></td>
                            <td class="text-right">Rp <?= number_format($item['total_nilai_masuk'] ?? 0, 0, ',', '.') ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal_transaksi'])) ?></td>
                            <td class="text-center"><?= esc($item['jumlah_transaksi']) ?></td>
                            <td class="text-right"><?= number_format($item['rata_rata_setoran'], 2, ',', '.') ?></td>
                        </tr>
                    <?php elseif ($type === 'keluar_dengan_harga'): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                            <td><?= esc($item['nama_petani'] ?? '-') ?></td>
                            <td><?= esc($item['jenis_kopi'] ?? '-') ?></td>
                            <td><?= esc($item['tujuan_pembeli'] ?? '-') ?></td>
                            <td class="text-right"><?= number_format($item['jumlah_kg'] ?? 0, 2, ',', '.') ?></td>
                            <td class="text-right">Rp <?= number_format($item['harga_jual_per_kg'] ?? 0, 0, ',', '.') ?></td>
                            <td class="text-right">Rp <?= number_format($item['keuntungan_bumdes'] ?? 0, 0, ',', '.') ?></td>
                            <td class="text-right">Rp <?= number_format($item['total_harga_petani'] ?? 0, 0, ',', '.') ?></td>
                            <td><?= esc($item['keterangan'] ?? '-') ?></td>
                        </tr>
                    <?php elseif ($type === 'stok_dengan_nilai'): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= esc($item['jenis_kopi']) ?></td>
                            <td class="text-right"><?= number_format($item['stok_akhir'], 2, ',', '.') ?></td>
                        </tr>
                    <?php elseif ($type === 'masuk'): ?>
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
                <!-- [PERBAIKAN] Sesuaikan colspan dengan jumlah kolom maksimum yang mungkin ditampilkan -->
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data yang ditemukan.</td> <!-- Misalnya, jika maks kolom adalah 10 (keluar_dengan_harga) -->
                </tr>
            <?php endif; ?>
        </tbody>

        <!-- TOTAL ROW UNTUK KOPI MASUK DENGAN HARGA -->
        <?php if ($type === 'masuk_dengan_harga' && !empty($data)): ?>
            <tfoot style="background-color: #f2f2f2;">
                <tr>
                    <td colspan="3" class="text-center text-bold">TOTAL</td>
                    <td class="text-right text-bold">
                        <?php
                        $totalMasuk = array_sum(array_column($data, 'total_masuk'));
                        echo number_format($totalMasuk, 2, ',', '.');
                        ?>
                    </td>
                    <td class="text-right text-bold">
                        Rp <?php
                            $totalNilai = array_sum(array_column($data, 'total_nilai_masuk'));
                            echo number_format($totalNilai, 0, ',', '.');
                            ?>
                    </td>
                    <td class="text-center text-bold">-</td>
                    <td class="text-center text-bold">
                        <?php
                        $totalTransaksi = array_sum(array_column($data, 'jumlah_transaksi'));
                        echo $totalTransaksi;
                        ?>
                    </td>
                    <td class="text-right text-bold">
                        <?php
                        $avgSetoran = $totalTransaksi > 0 ? $totalMasuk / $totalTransaksi : 0;
                        echo number_format($avgSetoran, 2, ',', '.');
                        ?>
                    </td>
                </tr>
            </tfoot>
        <?php endif; ?>

        <!-- TOTAL ROW UNTUK KOPI KELUAR DENGAN HARGA -->
        <?php if ($type === 'keluar_dengan_harga' && !empty($data)): ?>
            <tfoot style="background-color: #f2f2f2;">
                <tr>
                    <td colspan="5" class="text-center text-bold">TOTAL</td>
                    <td class="text-right text-bold">
                        <?php
                        $totalKeluar = array_sum(array_column($data, 'jumlah_kg'));
                        echo number_format($totalKeluar, 2, ',', '.');
                        ?>
                    </td>
                    <td class="text-center text-bold">-</td>
                    <td class="text-right text-bold">
                        Rp <?php
                            $totalKeuntungan = array_sum(array_column($data, 'keuntungan_bumdes'));
                            echo number_format($totalKeuntungan, 0, ',', '.');
                            ?>
                    </td>
                    <td class="text-right text-bold">
                        Rp <?php
                            $totalHargaPetani = array_sum(array_column($data, 'total_harga_petani'));
                            echo number_format($totalHargaPetani, 0, ',', '.');
                            ?>
                    </td>
                    <td class="text-center text-bold">-</td>
                </tr>
            </tfoot>
        <?php endif; ?>

        <!-- Di _kopi_export_pdf.php, bagian TOTAL ROW UNTUK STOK DENGAN NILAI -->
        <?php if ($type === 'stok_dengan_nilai' && !empty($data)): ?>
            <tfoot style="background-color: #f2f2f2;">
                <tr>
                    <td colspan="2" class="text-center text-bold">TOTAL STOK GLOBAL</td>
                    <td class="text-right text-bold">
                        <?php
                        $totalStok = array_sum(array_column($data, 'stok_akhir'));
                        echo number_format($totalStok, 2, ',', '.');
                        ?>
                    </td>
                </tr>
            </tfoot>
        <?php endif; ?>


    </table>

    <!-- Tanda Tangan BUMDES (Tunggal) -->
    <div class="signature-section">
        <?php
        // Format tanggal Indonesia untuk tanda tangan
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $tanggalTtd = date('d ') . $bulanIndonesia[(int)date('n')] . date(' Y');
        ?>
        <table>
            <tr>
                <td style="width: 50%;"></td> <!-- Kolom kosong untuk mendorong ke kanan -->
                <td style="width: 50%; text-align: center;">
                    <p><?= esc($lokasi ?? 'Lokasi') ?>, <?= esc($tanggalTtd) ?></p>
                    <p><?= esc($jabatanPenandatangan ?? 'Ketua BUMDES') ?></p>
                    <div style="height: 60px;"></div>
                    <p class="underline"><?= esc($namaPenandatangan ?? '_________________') ?></p>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>