<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\KopiKeluarModel;
use App\Models\HargaJenisKopiModel;
use App\Models\PengaturanModel;

class ExportPendapatan extends BaseController
{
    protected $kopiKeluarModel;
    protected $hargaJenisKopiModel;

    public function __construct()
    {
        $this->kopiKeluarModel = new KopiKeluarModel();
        $this->hargaJenisKopiModel = new HargaJenisKopiModel();
    }

    // =================================================================================
    // EXPORT EXCEL
    // =================================================================================

    public function excel()
    {
        $filter = [
            'tanggal_awal' => $this->request->getGet('tanggal_awal') ?? date('Y-m-01'),
            'tanggal_akhir' => $this->request->getGet('tanggal_akhir') ?? date('Y-m-d'),
        ];

        // Normalisasi & validasi tanggal
        $filter['tanggal_awal'] = $this->sanitizeDate($filter['tanggal_awal']);
        $filter['tanggal_akhir'] = $this->sanitizeDate($filter['tanggal_akhir']);
        if ($filter['tanggal_awal'] > $filter['tanggal_akhir']) {
            [$filter['tanggal_awal'], $filter['tanggal_akhir']] = [$filter['tanggal_akhir'], $filter['tanggal_awal']];
        }

        // Ambil SEMUA data tanpa pagination
        $transaksi = $this->kopiKeluarModel->getTransaksiForCalculation($filter['tanggal_awal'], $filter['tanggal_akhir']);

        // Tambahkan harga_beli_per_kg dari HargaJenisKopiModel
        foreach ($transaksi as &$t) {
            $hargaBeli = $this->kopiKeluarModel->getHargaBeliPerKg($t['jenis_pohon_id'], $t['tanggal']) ?? 0;
            $t['harga_beli_per_kg'] = $hargaBeli;
            $t['_total_jual'] = (float)($t['jumlah'] * $t['harga_jual_per_kg']);
            $t['_total_beli'] = (float)($t['jumlah'] * $t['harga_beli_per_kg']);
            $t['_laba'] = $t['_total_jual'] - $t['_total_beli'];
        }
        unset($t);

        $title = 'Laporan Pendapatan Kopi (Komersial)';
        $headers = [
            'No',
            'Tanggal',
            'Jenis Pohon',
            'Tujuan',
            'Jumlah (Kg)',
            'Harga Jual/Kg (Rp)',
            'Harga Beli/Kg (Rp)',
            'Total Jual (Rp)',
            'Total Beli (Rp)',
            'Laba (Rp)'
        ];

        // Hitung subtotal
        $totalPendapatan = array_sum(array_column($transaksi, '_total_jual'));
        $totalBiaya = array_sum(array_column($transaksi, '_total_beli'));
        $totalLaba = array_sum(array_column($transaksi, '_laba'));

        // Format total row
        $totalRow = [
            'cells' => [
                'TOTAL',
                '',
                '',
                '',
                '',
                '',
                '',
                'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
                'Rp ' . number_format($totalBiaya, 0, ',', '.'),
                'Rp ' . number_format($totalLaba, 0, ',', '.')
            ],
            'start_column' => 8, // kolom H (Total Jual)
            'merge_until' => 7   // merge A–G
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->_generateExcelTemplate($sheet, $title, $headers, $transaksi, [], $totalRow, $filter);

        $filename = 'Laporan_Pendapatan_Kopi_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    // =================================================================================
    // EXPORT PDF
    // =================================================================================

    public function pdf()
    {
        $filter = [
            'tanggal_awal' => $this->request->getGet('tanggal_awal') ?? date('Y-m-01'),
            'tanggal_akhir' => $this->request->getGet('tanggal_akhir') ?? date('Y-m-d'),
        ];

        $filter['tanggal_awal'] = $this->sanitizeDate($filter['tanggal_awal']);
        $filter['tanggal_akhir'] = $this->sanitizeDate($filter['tanggal_akhir']);
        if ($filter['tanggal_awal'] > $filter['tanggal_akhir']) {
            [$filter['tanggal_awal'], $filter['tanggal_akhir']] = [$filter['tanggal_akhir'], $filter['tanggal_awal']];
        }

        $transaksi = $this->kopiKeluarModel->getTransaksiForCalculation($filter['tanggal_awal'], $filter['tanggal_akhir']);

        // Gunakan array_map untuk menghindari reference & duplikasi
        $transaksi = array_map(function ($item) {
            $hargaBeli = $this->hargaJenisKopiModel->getLatestPrice($item['jenis_pohon_id'], $item['tanggal'])['harga_beli_per_kg'] ?? 0;
            $item['harga_beli_per_kg'] = $hargaBeli;
            $item['_total_jual'] = (float)($item['jumlah'] * $item['harga_jual_per_kg']);
            $item['_total_beli'] = (float)($item['jumlah'] * $item['harga_beli_per_kg']);
            $item['_laba'] = $item['_total_jual'] - $item['_total_beli'];
            return $item;
        }, $transaksi);

        $summary = [
            'total_pendapatan' => array_sum(array_column($transaksi, '_total_jual')),
            'total_biaya'      => array_sum(array_column($transaksi, '_total_beli')),
            'laba_bersih'      => array_sum(array_column($transaksi, '_laba')),
            'jumlah_transaksi' => count($transaksi),
        ];

        $data = [
            'title'    => 'Laporan Pendapatan Kopi (Komersial)',
            'subtitle' => 'Periode: ' . $this->_formatPeriodePDF($filter),
            'type'     => 'pendapatan',
            'data'     => $transaksi,
            'summary'  => $summary,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_kopi_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_pendapatan_kopi_' . date('YmdHis') . '.pdf');
    }

    // =================================================================================
    // HELPER METHODS (konsisten dengan ExportLaporanKomersial)
    // =================================================================================

    private function sanitizeDate($date)
    {
        if (empty($date)) return date('Y-m-d');
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date ? $date : date('Y-m-d');
    }

    private function _generateExcelTemplate(
        &$sheet,
        string $title,
        array $headers,
        array $data,
        array $dataMapping,
        ?array $totalRow = null,
        ?array $filter = null
    ) {
        $pengaturanModel = new PengaturanModel();
        $namaKetua = $pengaturanModel->where('meta_key', 'ketua_komersial')->first()['meta_value'] ?? 'NAMA KETUA BUMDES';
        $jabatanKanan = $pengaturanModel->where('meta_key', 'jabatan_kanan_komersial')->first()['meta_value'] ?? 'Admin Komersial';
        $namaKanan = $pengaturanModel->where('meta_key', 'nama_kanan_komersial')->first()['meta_value'] ?? 'NAMA ADMIN';
        $lokasi = $pengaturanModel->where('meta_key', 'lokasi_komersial')->first()['meta_value'] ?? 'LOKASI';

        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $endCol = count($headers);
        $endColStr = Coordinate::stringFromColumnIndex($endCol);

        // Header
        $sheet->mergeCells('A1:' . $endColStr . '1')->setCellValue('A1', 'UNIT USAHA KOMERSIAL - BUMDES "ALAM LESTARI"');
        $sheet->mergeCells('A2:' . $endColStr . '2')->setCellValue('A2', strtoupper($title));
        $periode = 'Semua Periode';
        if (!empty($filter['tanggal_awal']) || !empty($filter['tanggal_akhir'])) {
            $start = $this->_formatTanggalIndonesia($filter['tanggal_awal']);
            $end = $this->_formatTanggalIndonesia($filter['tanggal_akhir']);
            $periode = $start . ' s/d ' . $end;
        }
        $sheet->mergeCells('A3:' . $endColStr . '3')->setCellValue('A3', 'FILTER: ' . $periode);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Table Headers
        $headerRow = 5;
        foreach ($headers as $index => $header) {
            $colStr = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue($colStr . $headerRow, $header);
        }
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data Rows
        $currentRow = $headerRow + 1;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $currentRow, $index + 1);
            $sheet->setCellValue('B' . $currentRow, $item['tanggal'] ?? '');
            $sheet->setCellValue('C' . $currentRow, $item['nama_jenis_pohon'] ?? '');
            $sheet->setCellValue('D' . $currentRow, $item['tujuan'] ?? '');
            $sheet->setCellValueExplicit('E' . $currentRow, $item['jumlah'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('F' . $currentRow, $item['harga_jual_per_kg'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('G' . $currentRow, $item['harga_beli_per_kg'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('H' . $currentRow, $item['_total_jual'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('I' . $currentRow, $item['_total_beli'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('J' . $currentRow, $item['_laba'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);

            // Format number
            $sheet->getStyle('E' . $currentRow)->getNumberFormat()->setFormatCode('#,##0.00');
            foreach (['F', 'G', 'H', 'I', 'J'] as $col) {
                $sheet->getStyle($col . $currentRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            }

            $currentRow++;
        }

        $dataEndRow = $currentRow - 1;

        // Total Row
        if ($totalRow) {
            $mergeUntil = $totalRow['merge_until'] ?? 1;
            $labelStartColStr = 'A';
            $labelEndColStr = Coordinate::stringFromColumnIndex($mergeUntil);
            $sheet->mergeCells($labelStartColStr . $currentRow . ':' . $labelEndColStr . $currentRow);
            $sheet->setCellValue($labelStartColStr . $currentRow, $totalRow['cells'][0]);
            $sheet->getStyle($labelStartColStr . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle($labelStartColStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            for ($i = 1; $i < count($totalRow['cells']); $i++) {
                $colStr = Coordinate::stringFromColumnIndex($mergeUntil + $i);
                $sheet->setCellValue($colStr . $currentRow, $totalRow['cells'][$i]);
                $sheet->getStyle($colStr . $currentRow)->getFont()->setBold(true);
                if (strpos($totalRow['cells'][$i], 'Rp') !== false) {
                    $sheet->getStyle($colStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }
            }
            $currentRow++;
        }

        // Border & styling
        $styleArray = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $dataEndRow)->applyFromArray($styleArray);
        if ($totalRow) {
            $sheet->getStyle('A' . ($dataEndRow + 1) . ':' . $endColStr . ($dataEndRow + 1))->applyFromArray($styleArray);
        }
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getAlignment()->setWrapText(true);

        // Auto-size
        for ($i = 1; $i <= $endCol; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        // Tanda tangan
        $rowTtd = $currentRow + 2;
        $colTtdKanan = max(2, $endCol - 2);
        $colTtdKananStr = Coordinate::stringFromColumnIndex($colTtdKanan);

        $sheet->setCellValue('A' . $rowTtd, 'Mengetahui,');
        $sheet->setCellValue('A' . ($rowTtd + 1), 'Ketua BUMDES');
        $sheet->setCellValue('A' . ($rowTtd + 5), $namaKetua);
        $sheet->getStyle('A' . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle('A' . $rowTtd . ':A' . ($rowTtd + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells($colTtdKananStr . $rowTtd . ':' . $endColStr . $rowTtd)
            ->setCellValue($colTtdKananStr . $rowTtd, $lokasi . ', ' . date('d ') . $bulanIndonesia[(int)date('n')] . date(' Y'));
        $sheet->mergeCells($colTtdKananStr . ($rowTtd + 1) . ':' . $endColStr . ($rowTtd + 1))
            ->setCellValue($colTtdKananStr . ($rowTtd + 1), $jabatanKanan);
        $sheet->mergeCells($colTtdKananStr . ($rowTtd + 5) . ':' . $endColStr . ($rowTtd + 5))
            ->setCellValue($colTtdKananStr . ($rowTtd + 5), $namaKanan);
        $sheet->getStyle($colTtdKananStr . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle($colTtdKananStr . $rowTtd . ':' . $endColStr . ($rowTtd + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function generatePdf(string $html, string $filename)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => true]);
        exit();
    }

    private function _getSignatureData(): array
    {
        $pengaturanModel = new PengaturanModel();
        return [
            'namaKetua'    => $pengaturanModel->where('meta_key', 'ketua_komersial')->first()['meta_value'] ?? 'NAMA KETUA BUMDES',
            'jabatanKanan' => $pengaturanModel->where('meta_key', 'jabatan_kanan_komersial')->first()['meta_value'] ?? 'Admin Komersial',
            'namaKanan'    => $pengaturanModel->where('meta_key', 'nama_kanan_komersial')->first()['meta_value'] ?? 'NAMA ADMIN',
            'lokasi'       => $pengaturanModel->where('meta_key', 'lokasi_komersial')->first()['meta_value'] ?? 'LOKASI',
        ];
    }

    private function _getLogoData(): array
    {
        $logoBase64 = '';
        $pathToLogo = ROOTPATH . 'public/img/Bumdesfix.png';
        if (file_exists($pathToLogo)) {
            $logoType = pathinfo($pathToLogo, PATHINFO_EXTENSION);
            $logoData = file_get_contents($pathToLogo);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        return ['logoBase64' => $logoBase64];
    }

    private function _formatTanggalIndonesia($date)
    {
        if (empty($date)) return '';
        $bulan = [
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
        $ts = strtotime($date);
        return date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }

    private function _formatPeriodePDF($filter)
    {
        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
            return $this->_formatTanggalIndonesia($filter['tanggal_awal']) . ' s/d ' . $this->_formatTanggalIndonesia($filter['tanggal_akhir']);
        } elseif (!empty($filter['tanggal_awal'])) {
            return 'Sejak ' . $this->_formatTanggalIndonesia($filter['tanggal_awal']);
        } elseif (!empty($filter['tanggal_akhir'])) {
            return 'Sampai ' . $this->_formatTanggalIndonesia($filter['tanggal_akhir']);
        }
        return 'Semua Periode';
    }
}
