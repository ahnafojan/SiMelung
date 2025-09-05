<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetPariwisataModel;
use App\Models\ObjekWisataModel;
use App\Models\PengaturanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanAsetPariwisata extends BaseController
{
    /**
     * Menampilkan halaman utama untuk memilih laporan.
     */
    public function index()
    {
        $objekWisataModel = new ObjekWisataModel();
        $data = [
            'title'      => 'Laporan Aset Pariwisata',
            'pariwisata' => $objekWisataModel->orderBy('nama_wisata', 'ASC')->findAll(),
        ];
        return view('admin_pariwisata/laporan', $data);
    }

    /**
     * Mengekspor data Aset Pariwisata ke format Excel berdasarkan ID Wisata.
     */
    public function exportExcel($wisataId)
    {
        $asetModel = new AsetPariwisataModel();
        $objekWisataModel = new ObjekWisataModel();

        // Ambil data aset yang berelasi dengan wisataId
        $dataAset = $asetModel->whereIn('aset_pariwisata.id', function ($builder) use ($wisataId) {
            return $builder->select('aset_id')->from('aset_wisata')->where('wisata_id', $wisataId);
        })->orderBy('tahun_perolehan', 'DESC')->findAll();

        $wisata = $objekWisataModel->find($wisataId);
        $namaWisata = $wisata ? $wisata['nama_wisata'] : 'Semua';

        $title = 'Laporan Aset Pariwisata - ' . $namaWisata;
        $headers = ['No', 'Nama Aset', 'Kode Aset', 'NUP', 'Tahun Perolehan', 'Nilai Perolehan (Rp)', 'Keterangan', 'Sumber Pengadaan', 'Metode Pengadaan'];
        $dataMapping = ['nama_pariwisata', 'kode_aset', 'nup', 'tahun_perolehan', 'nilai_perolehan', 'keterangan', 'sumber_pengadaan', 'metode_pengadaan'];

        $totalNilai = array_sum(array_column($dataAset, 'nilai_perolehan'));
        $totalRow = ['cells' => ['Total Nilai Perolehan', 'Rp ' . number_format($totalNilai, 0, ',', '.')], 'start_column' => 6];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->_generateExcelTemplate($sheet, $title, $headers, $dataAset, $dataMapping, $totalRow);

        $filename = 'Laporan_Aset_' . url_title($namaWisata, '_', true) . '_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    /**
     * Mengekspor data Aset Pariwisata ke format PDF berdasarkan ID Wisata.
     */
    public function exportPDF($wisataId)
    {
        $asetModel = new AsetPariwisataModel();
        $objekWisataModel = new ObjekWisataModel();

        $dataAset = $asetModel->whereIn('aset_pariwisata.id', function ($builder) use ($wisataId) {
            return $builder->select('aset_id')->from('aset_wisata')->where('wisata_id', $wisataId);
        })->orderBy('tahun_perolehan', 'DESC')->findAll();

        $wisata = $objekWisataModel->find($wisataId);
        $namaWisata = $wisata ? $wisata['nama_wisata'] : 'Tidak Ditemukan';

        $data = [
            'title'      => 'Laporan Aset Pariwisata',
            'subtitle'   => 'Lokasi: ' . $namaWisata,
            'asetData'   => $dataAset,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());

        $html = view('admin_pariwisata/pariwisata_pdf', $data);
        return $this->generatePdf($html, 'laporan_aset_' . url_title($namaWisata, '_', true) . '_' . date('YmdHis') . '.pdf');
    }

    // --- FUNGSI HELPER ---

    private function _generateExcelTemplate(&$sheet, string $title, array $headers, array $data, array $dataMapping, ?array $totalRow = null)
    {
        $pengaturanModel = new PengaturanModel();
        $pengaturanDb = $pengaturanModel->whereIn('meta_key', ['ketua_pokdarwis', 'lokasi_laporan', 'nama_pokdarwis'])->findAll();
        $pengaturan = array_column($pengaturanDb, 'meta_value', 'meta_key');

        $ketua = $pengaturan['ketua_pokdarwis'] ?? 'NAMA KETUA';
        $lokasi = $pengaturan['lokasi_laporan'] ?? 'LOKASI';
        $namaPokdarwis = $pengaturan['nama_pokdarwis'] ?? '"NAMA POKDARWIS"';

        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $endCol = count($headers);
        $endColStr = Coordinate::stringFromColumnIndex($endCol);

        $sheet->mergeCells('A1:' . $endColStr . '1')->setCellValue('A1', 'KELOMPOK SADAR WISATA (POKDARWIS)');
        $sheet->mergeCells('A2:' . $endColStr . '2')->setCellValue('A2', strtoupper($namaPokdarwis));
        $sheet->mergeCells('A3:' . $endColStr . '3')->setCellValue('A3', 'DESA MELUNG KECAMATAN KEDUNGBANTENG KABUPATEN BANYUMAS');
        $sheet->mergeCells('A4:' . $endColStr . '4')->setCellValue('A4', strtoupper($title));

        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getFont()->setSize(16);

        $headerRow = 6;
        foreach ($headers as $index => $header) {
            $colStr = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue($colStr . $headerRow, $header);
        }
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $currentRow = $headerRow + 1;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $currentRow, $index + 1);
            foreach ($dataMapping as $colIndex => $key) {
                $targetCol = Coordinate::stringFromColumnIndex($colIndex + 2);
                $value = $item[$key] ?? '';
                if ($key == 'nilai_perolehan') {
                    $sheet->getCell($targetCol . $currentRow)->setValueExplicit($value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->getStyle($targetCol . $currentRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
                } else {
                    $sheet->setCellValue($targetCol . $currentRow, $value);
                }
            }
            $currentRow++;
        }

        if ($totalRow) {
            $labelColIndex = $totalRow['start_column'] - 1;
            if ($labelColIndex < 1) $labelColIndex = 1;
            $labelStartColStr = Coordinate::stringFromColumnIndex(1);
            $labelEndColStr = Coordinate::stringFromColumnIndex($labelColIndex);
            $valueColStr = Coordinate::stringFromColumnIndex($totalRow['start_column']);

            $sheet->mergeCells($labelStartColStr . $currentRow . ':' . $labelEndColStr . $currentRow);
            $sheet->setCellValue($labelStartColStr . $currentRow, $totalRow['cells'][0]);
            $sheet->setCellValue($valueColStr . $currentRow, $totalRow['cells'][1]);
            $sheet->getStyle('A' . $currentRow . ':' . $endColStr . $currentRow)->getFont()->setBold(true);
            $currentRow++;
        }

        $rowTtd = $currentRow + 2;
        $colTtdStart = max(2, $endCol - 2);
        $colTtdStartStr = Coordinate::stringFromColumnIndex($colTtdStart);

        $sheet->mergeCells($colTtdStartStr . $rowTtd . ':' . $endColStr . $rowTtd)->setCellValue($colTtdStartStr . $rowTtd, $lokasi . ', ' . date('d ') . $bulanIndonesia[(int)date('n')] . date(' Y'));
        $sheet->mergeCells($colTtdStartStr . ($rowTtd + 1) . ':' . $endColStr . ($rowTtd + 1))->setCellValue($colTtdStartStr . ($rowTtd + 1), 'Ketua POKDARWIS');
        $sheet->mergeCells($colTtdStartStr . ($rowTtd + 5) . ':' . $endColStr . ($rowTtd + 5))->setCellValue($colTtdStartStr . ($rowTtd + 5), $ketua);

        $signatureRange = $colTtdStartStr . $rowTtd . ':' . $endColStr . ($rowTtd + 5);
        $sheet->getStyle($signatureRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($colTtdStartStr . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);

        $dataEndRow = $totalRow ? $currentRow - 2 : $currentRow - 1;
        $styleArray = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_LEFT],
        ];
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $dataEndRow)->applyFromArray($styleArray);
        if ($totalRow) {
            $sheet->getStyle('A' . ($dataEndRow + 1) . ':' . $endColStr . ($dataEndRow + 1))->applyFromArray($styleArray);
        }
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getAlignment()->setWrapText(true);
        for ($i = 1; $i <= $endCol; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }
    }

    protected function _getSignatureData(): array
    {
        $pengaturanModel = new PengaturanModel();
        $pengaturanDb = $pengaturanModel->whereIn('meta_key', ['ketua_pokdarwis', 'lokasi_laporan', 'nama_pokdarwis'])->findAll();

        $pengaturan = array_column($pengaturanDb, 'meta_value', 'meta_key');

        return [
            'namaPenandatangan'    => $pengaturan['ketua_pokdarwis'] ?? '_________________',
            'jabatanPenandatangan' => 'Ketua POKDARWIS',
            'lokasi'               => $pengaturan['lokasi_laporan'] ?? 'Lokasi',
            'namaPokdarwis'        => $pengaturan['nama_pokdarwis'] ?? '"PAGUBUGAN"',
        ];
    }

    private function generatePdf(string $html, string $filename)
    {
        $options = new Options();
        $options->set('chroot', FCPATH);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => false]);
        exit;
    }

    private function _getLogoData(): array
    {
        $pathToLogo = FCPATH . 'img/pariwisata.jpg';

        if (file_exists($pathToLogo)) {
            return ['logoPath' => $pathToLogo];
        }

        return ['logoPath' => ''];
    }
}
