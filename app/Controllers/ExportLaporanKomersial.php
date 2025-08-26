<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class ExportLaporanKomersial extends LaporanKomersial
{

    // --- Export Excel ---

    public function excelMasuk()
    {
        // PERBAIKAN DI SINI
        $filter = $this->request->getGet();
        $rekapMasuk = $this->getRekapKopiMasuk($filter, null, null, false);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Petani');
        $sheet->setCellValue('C1', 'Total Masuk (Kg)');
        $sheet->setCellValue('D1', 'Tanggal Setor Terakhir');
        $sheet->setCellValue('E1', 'Jumlah Transaksi');
        $sheet->setCellValue('F1', 'Rata-rata Setoran (Kg)');

        $no = 1;
        $rowIndex = 2;
        foreach ($rekapMasuk as $row) {
            $sheet->setCellValue('A' . $rowIndex, $no++);
            $sheet->setCellValue('B' . $rowIndex, $row['nama_petani']);
            $sheet->setCellValue('C' . $rowIndex, number_format($row['total_masuk'], 2, '.', ''));
            $sheet->setCellValue('D' . $rowIndex, $row['tanggal_terakhir']);
            $sheet->setCellValue('E' . $rowIndex, $row['jumlah_transaksi']);
            $sheet->setCellValue('F' . $rowIndex, number_format($row['rata_rata_setoran'], 2, '.', ''));
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'rekap_kopi_masuk_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }


    public function excelKeluar()
    {
        // PERBAIKAN DI SINI
        $filter = $this->request->getGet();
        $rekapKeluar = $this->getRekapKopiKeluar($filter, null, null, false);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Jenis Kopi');
        $sheet->setCellValue('D1', 'Tujuan Pembeli');
        $sheet->setCellValue('E1', 'Jumlah (Kg)');
        $sheet->setCellValue('F1', 'Keterangan');

        $no = 1;
        $rowIndex = 2;
        foreach ($rekapKeluar as $row) {
            $sheet->setCellValue('A' . $rowIndex, $no++);
            $sheet->setCellValue('B' . $rowIndex, $row['tanggal']);
            $sheet->setCellValue('C' . $rowIndex, $row['jenis_kopi']);
            $sheet->setCellValue('D' . $rowIndex, $row['tujuan_pembeli']);
            $sheet->setCellValue('E' . $rowIndex, number_format($row['jumlah_kg'], 2, '.', ''));
            $sheet->setCellValue('F' . $rowIndex, $row['keterangan']);
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'rekap_kopi_keluar_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }


    public function excelStok()
    {
        // PERBAIKAN DI SINI
        $filter = $this->request->getGet();
        $stokAkhir = $this->getStokAkhir($filter, null, null, false);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Jenis Kopi');
        $sheet->setCellValue('C1', 'Total Stok (Kg)');

        $no = 1;
        $rowIndex = 2;
        foreach ($stokAkhir as $row) {
            $sheet->setCellValue('A' . $rowIndex, $no++);
            $sheet->setCellValue('B' . $rowIndex, $row['jenis_kopi']);
            $sheet->setCellValue('C' . $rowIndex, number_format($row['stok_akhir'], 2, '.', ''));
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'stok_akhir_kopi_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    // --- Export PDF ---

    public function pdfMasuk()
    {
        $filter = $this->request->getGet();
        $rekapMasuk = $this->getRekapKopiMasuk($filter, null, null, false);

        $html = $this->loadPdfHeader('Laporan Kopi Masuk per Petani', 'A4', 'landscape');
        $html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
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
            <tbody>';
        $no = 1;
        foreach ($rekapMasuk as $row) {
            $html .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . $row['nama_petani'] . '</td>
                <td align="right">' . number_format($row['total_masuk'], 2) . '</td>
                <td>' . $row['tanggal_terakhir'] . '</td>
                <td align="center">' . $row['jumlah_transaksi'] . '</td>
                <td align="right">' . number_format($row['rata_rata_setoran'], 2) . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        $this->generatePdf($html, 'rekap_kopi_masuk_' . date('YmdHis') . '.pdf');
    }

    public function pdfKeluar()
    {
        $filter = $this->request->getGet();
        $rekapKeluar = $this->getRekapKopiKeluar($filter, null, null, false);

        $html = $this->loadPdfHeader('Laporan Kopi Keluar (Penjualan)');
        $html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
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
            <tbody>';
        $no = 1;
        foreach ($rekapKeluar as $row) {
            $html .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . $row['tanggal'] . '</td>
                <td>' . $row['jenis_kopi'] . '</td>
                <td>' . $row['tujuan_pembeli'] . '</td>
                <td align="right">' . number_format($row['jumlah_kg'], 2) . '</td>
                <td>' . $row['keterangan'] . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        $this->generatePdf($html, 'rekap_kopi_keluar_' . date('YmdHis') . '.pdf');
    }

    public function pdfStok()
    {
        $filter = $this->request->getGet();
        $stokAkhir = $this->getStokAkhir($filter, null, null, false);

        $html = $this->loadPdfHeader('Laporan Stok Akhir Kopi');
        $html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Kopi</th>
                    <th>Total Stok (Kg)</th>
                </tr>
            </thead>
            <tbody>';
        $no = 1;
        foreach ($stokAkhir as $row) {
            $html .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . $row['jenis_kopi'] . '</td>
                <td align="right">' . number_format($row['stok_akhir'], 2) . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        // Hitung total stok global
        $totalStokGlobal = array_sum(array_column($stokAkhir, 'stok_akhir'));
        $html .= '<div style="margin-top: 10px; text-align: right;"><strong>Total Stok Akhir Global: ' . number_format($totalStokGlobal, 2) . ' Kg</strong></div>';

        $this->generatePdf($html, 'stok_akhir_kopi_' . date('YmdHis') . '.pdf');
    }

    // --- Helper untuk PDF (untuk menghindari pengulangan kode) ---
    private function loadPdfHeader($title, $paper = 'A4', $orientation = 'portrait')
    {
        $html = '<style>
            body { font-family: sans-serif; }
            h3 { text-align: center; margin-bottom: 20px; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .text-right { text-align: right; }
        </style>';
        $html .= '<h3>' . $title . '</h3>';
        return $html;
    }

    private function generatePdf($html, $filename)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename);
    }
    /**
     * Export data rekap petani terdaftar ke format Excel.
     */
    public function excelPetani()
    {
        $filter = $this->request->getGet();
        // Menggunakan metode baru untuk mendapatkan semua data petani tanpa paginasi
        $rekapPetaniTerdaftar = $this->_getAllRekapPetaniTerdaftar($filter);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Alamat');
        $sheet->setCellValue('D1', 'No HP');
        $sheet->setCellValue('E1', 'Jenis Kopi');

        $no = 1;
        $rowIndex = 2;
        foreach ($rekapPetaniTerdaftar as $row) {
            $sheet->setCellValue('A' . $rowIndex, $no++);
            $sheet->setCellValue('B' . $rowIndex, $row['nama']);
            $sheet->setCellValue('C' . $rowIndex, $row['alamat']);
            $sheet->setCellValue('D' . $rowIndex, $row['no_hp']);
            $sheet->setCellValue('E' . $rowIndex, $row['jenis_kopi']); // Sudah berupa string koma-terpisah
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_petani_terdaftar_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    public function pdfPetani()
    {
        $filter = $this->request->getGet();
        // Menggunakan metode baru untuk mendapatkan semua data petani tanpa paginasi
        $rekapPetaniTerdaftar = $this->_getAllRekapPetaniTerdaftar($filter);

        $html = $this->loadPdfHeader('Laporan Petani Terdaftar');
        $html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Jenis Kopi</th>
                </tr>
            </thead>
            <tbody>';
        $no = 1;
        foreach ($rekapPetaniTerdaftar as $row) {
            $html .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . $row['nama'] . '</td>
                <td>' . $row['alamat'] . '</td>
                <td>' . $row['no_hp'] . '</td>
                <td>' . $row['jenis_kopi'] . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        $this->generatePdf($html, 'laporan_petani_terdaftar_' . date('YmdHis') . '.pdf');
    }
    public function excelAset()
    {
        // Ambil filter tahun dari URL
        $tahun = $this->request->getGet('tahun_aset') ?? 'semua';

        // Panggil model untuk mendapatkan SEMUA data aset sesuai filter
        // Pastikan method getAllAset() ada di AsetKomersialModel
        $dataAset = $this->asetModel->getAllAset($tahun);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Barang / Aset');
        $sheet->setCellValue('C1', 'Kode Aset');
        $sheet->setCellValue('D1', 'NUP');
        $sheet->setCellValue('E1', 'Tahun');
        $sheet->setCellValue('F1', 'Merk / Tipe');
        $sheet->setCellValue('G1', 'Nilai Perolehan (Rp)');
        $sheet->setCellValue('H1', 'Keterangan');

        $no = 1;
        $rowIndex = 2;
        foreach ($dataAset as $item) {
            $sheet->setCellValue('A' . $rowIndex, $no++);
            $sheet->setCellValue('B' . $rowIndex, $item['nama_aset']);
            $sheet->setCellValue('C' . $rowIndex, $item['kode_aset']);
            $sheet->setCellValue('D' . $rowIndex, $item['nup']);
            $sheet->setCellValue('E' . $rowIndex, $item['tahun_perolehan']);
            $sheet->setCellValue('F' . $rowIndex, $item['merk_type']);
            $sheet->setCellValue('G' . $rowIndex, $item['nilai_perolehan']);
            $sheet->setCellValue('H' . $rowIndex, $item['keterangan']);
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_aset_produksi_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
    public function pdfAset()
    {
        // 1. Ambil filter dan data dari model
        $tahun = $this->request->getGet('tahun_aset') ?? 'semua';
        $asetModel = new \App\Models\AsetKomersialModel();
        $dataAset = $asetModel->getAllAset($tahun);

        // 2. Mulai membuat string HTML untuk PDF
        $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Aset Produksi</title>
        <style>
            body { 
                font-family: sans-serif; 
                font-size: 10px;
            }
            .title { 
                text-align: center; 
                font-size: 16px; 
                font-weight: bold;
                margin-bottom: 20px; 
            }
            .table { 
                width: 100%; 
                border-collapse: collapse; 
            }
            .table th, .table td { 
                border: 1px solid #000; 
                padding: 6px; 
                text-align: left;
            }
            .table th { 
                background-color: #f2f2f2; 
                text-align: center;
                font-weight: bold;
            }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
        </style>
    </head>
    <body>
        <div class="title">Laporan Aset Produksi</div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang / Aset</th>
                    <th>Kode Aset</th>
                    <th>NUP</th>
                    <th>Tahun</th>
                    <th>Merk / Tipe</th>
                    <th class="text-right">Nilai Perolehan (Rp)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>';

        // 3. Loop data aset untuk mengisi baris tabel
        if (!empty($dataAset)) {
            $no = 1;
            foreach ($dataAset as $item) {
                $html .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . esc($item['nama_aset']) . '</td>
                    <td class="text-center">' . esc($item['kode_aset']) . '</td>
                    <td class="text-center">' . esc($item['nup']) . '</td>
                    <td class="text-center">' . esc($item['tahun_perolehan']) . '</td>
                    <td>' . esc($item['merk_type']) . '</td>
                    <td class="text-right">' . number_format($item['nilai_perolehan'], 0, ',', '.') . '</td>
                    <td>' . esc($item['keterangan']) . '</td>
                </tr>';
            }
        } else {
            $html .= '
            <tr>
                <td colspan="8" class="text-center">Tidak ada data aset untuk filter ini.</td>
            </tr>';
        }

        // 4. Tutup tag HTML
        $html .= '
            </tbody>
        </table>
    </body>
    </html>';

        // 5. Generate PDF menggunakan Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Gunakan landscape agar tabel muat
        $dompdf->render();
        $dompdf->stream('laporan_aset_produksi_' . date('YmdHis') . '.pdf');
    }
}
