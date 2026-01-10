<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\KopiMasukModel;
use App\Models\PengaturanModel;

class ExportBiaya extends BaseController
{
    protected $kopiMasukModel;

    public function __construct()
    {
        $this->kopiMasukModel = new KopiMasukModel();
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

        // Ambil data transaksi pembelian
        $transaksi = $this->kopiMasukModel
            ->select('
                kopi_masuk.id,
                kopi_masuk.tanggal,
                kopi_masuk.jumlah,
                kopi_masuk.harga_saat_transaksi,
                kopi_masuk.total_harga,
                kopi_masuk.keterangan,
                petani.nama as nama_petani,
                jenis_pohon.nama_jenis as nama_jenis_pohon
            ')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->where('kopi_masuk.tanggal >=', $filter['tanggal_awal'])
            ->where('kopi_masuk.tanggal <=', $filter['tanggal_akhir'])
            ->orderBy('kopi_masuk.tanggal', 'ASC')
            ->orderBy('kopi_masuk.id', 'ASC')
            ->findAll();

        $title = 'Laporan Biaya Pembelian Kopi (Komersial)';
        $headers = [
            'No',
            'Tanggal',
            'Nama Petani',
            'Jenis Pohon',
            'Jumlah (Kg)',
            'Harga Beli/Kg (Rp)',
            'Total Harga (Rp)',
            'Keterangan'
        ];

        // Hitung subtotal
        $totalKg = array_sum(array_column($transaksi, 'jumlah'));
        $totalBiaya = array_sum(array_column($transaksi, 'total_harga'));

        // Format total row
        $totalRow = [
            'cells' => [
                'TOTAL',
                '',
                '',
                '',
                number_format($totalKg, 2, ',', '.') . ' Kg',
                '',
                'Rp ' . number_format($totalBiaya, 0, ',', '.'),
                ''
            ],
            'start_column' => 5, // kolom E (Jumlah Kg)
            'merge_until' => 4   // merge A–D
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->_generateExcelTemplate($sheet, $title, $headers, $transaksi, [], $totalRow, $filter);

        $filename = 'Laporan_Biaya_Pembelian_Kopi_' . date('Ymd') . '.xlsx';
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

        // Ambil data transaksi pembelian
        $transaksi = $this->kopiMasukModel
            ->select('
                kopi_masuk.id,
                kopi_masuk.tanggal,
                kopi_masuk.jumlah,
                kopi_masuk.harga_saat_transaksi,
                kopi_masuk.total_harga,
                kopi_masuk.keterangan,
                petani.nama as nama_petani,
                jenis_pohon.nama_jenis as nama_jenis_pohon
            ')
            ->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left')
            ->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left')
            ->where('kopi_masuk.tanggal >=', $filter['tanggal_awal'])
            ->where('kopi_masuk.tanggal <=', $filter['tanggal_akhir'])
            ->orderBy('kopi_masuk.tanggal', 'ASC')
            ->orderBy('kopi_masuk.id', 'ASC')
            ->findAll();

        $summary = [
            'total_kg'    => array_sum(array_column($transaksi, 'jumlah')),
            'total_biaya' => array_sum(array_column($transaksi, 'total_harga')),
            'jumlah_transaksi' => count($transaksi),
        ];

        $data = [
            'title'    => 'Laporan Biaya Pembelian Kopi (Komersial)',
            'subtitle' => 'Periode: ' . $this->_formatPeriodePDF($filter),
            'type'     => 'biaya_pembelian',
            'data'     => $transaksi,
            'summary'  => $summary,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_kopi_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_biaya_pembelian_kopi_' . date('YmdHis') . '.pdf');
    }

    // =================================================================================
    // HELPER METHODS
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
            $sheet->setCellValue('C' . $currentRow, $item['nama_petani'] ?? '');
            $sheet->setCellValue('D' . $currentRow, $item['nama_jenis_pohon'] ?? '');
            $sheet->setCellValueExplicit('E' . $currentRow, $item['jumlah'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('F' . $currentRow, $item['harga_saat_transaksi'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('G' . $currentRow, $item['total_harga'] ?? 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValue('H' . $currentRow, $item['keterangan'] ?? '-');

            // Format number
            $sheet->getStyle('E' . $currentRow)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('F' . $currentRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('G' . $currentRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');

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
                if (strpos($totalRow['cells'][$i], 'Rp') !== false || strpos($totalRow['cells'][$i], 'Kg') !== false) {
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
