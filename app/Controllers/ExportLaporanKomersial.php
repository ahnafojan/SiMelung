<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Services\RekapKopiService;
use App\Models\AsetKomersialModel;
use App\Models\PengaturanModel;

class ExportLaporanKomersial extends LaporanKomersial
{

    protected $rekapService;

    public function __construct()
    {
        $this->rekapService = new RekapKopiService();
    }


    // --- Export Excel ---

    public function excelPetani()
    {
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];
        $rekapPetaniController = new KomersialRekapPetani();
        $petaniData = $rekapPetaniController->_getFilteredPetaniQuery($filters)->findAll();

        $title = 'Laporan Data Petani Terdaftar (Komersial)';
        $headers = ['No', 'User ID', 'Nama Petani', 'Alamat', 'No. HP', 'Jenis Kopi'];
        $dataMapping = ['user_id', 'nama', 'alamat', 'no_hp', 'jenis_kopi_list'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->_generateExcelTemplate($sheet, $title, $headers, $petaniData, $dataMapping);

        $filename = 'Laporan_Petani_Komersial_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function excelMasuk()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'petani'     => $this->request->getGet('petani'),
        ];
        $rekapMasuk = $this->rekapService->getRekapKopiMasuk($filter);

        $title = 'Laporan Rekap Kopi Masuk per Petani (Komersial)';
        $headers = ['No', 'Nama Petani', 'Total Masuk (Kg)', 'Tanggal Setor Terakhir', 'Jumlah Transaksi', 'Rata-rata Setoran (Kg)'];
        $dataMapping = ['nama_petani', 'total_masuk', 'tanggal_terakhir', 'jumlah_transaksi', 'rata_rata_setoran'];

        $totalMasuk = array_sum(array_column($rekapMasuk, 'total_masuk'));
        $totalRow = ['cells' => ['Total Kopi Masuk', number_format($totalMasuk, 2)], 'start_column' => 2];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->_generateExcelTemplate($sheet, $title, $headers, $rekapMasuk, $dataMapping, $totalRow, $filter);

        $filename = 'Laporan_Kopi_Masuk_Komersial_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function exportRekapKeluarExcel()
    {
        $filter = $this->request->getGet();
        $rekapKeluar = $this->rekapService->getRekapKopiKeluar($filter);

        $title = 'Laporan Rekap Kopi Keluar (Komersial)';
        $headers = ['No', 'Tanggal', 'Jenis Kopi', 'Tujuan Pembeli', 'Jumlah (Kg)', 'Keterangan'];
        $dataMapping = ['tanggal', 'jenis_kopi', 'tujuan', 'jumlah', 'keterangan'];

        $totalKeluar = array_sum(array_column($rekapKeluar, 'jumlah'));
        $totalRow = ['cells' => ['Total Kopi Keluar', number_format($totalKeluar, 2)], 'start_column' => 4];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->_generateExcelTemplate($sheet, $title, $headers, $rekapKeluar, $dataMapping, $totalRow, $filter);

        $filename = 'Laporan_Kopi_Keluar_Komersial_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function excelStok()
    {
        $filter = $this->request->getGet();
        $rekapKopiController = new \App\Controllers\KomersialRekapKopi();
        $stokAkhirData = $rekapKopiController->getStokAkhir($filter, null, null, false);
        $stokAkhir = is_array($stokAkhirData) ? $stokAkhirData : [];

        $title = 'Laporan Stok Akhir Kopi (Komersial)';
        $headers = ['No', 'Jenis Kopi', 'Total Stok (Kg)'];
        $dataMapping = ['jenis_kopi', 'stok_akhir'];

        $totalStok = array_sum(array_column($stokAkhir, 'stok_akhir'));
        $totalRow = ['cells' => ['Total Stok Global', number_format($totalStok, 2)], 'start_column' => 2];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->_generateExcelTemplate($sheet, $title, $headers, $stokAkhir, $dataMapping, $totalRow, $filter);

        $filename = 'Laporan_Stok_Kopi_Komersial_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function excelAset()
    {
        $asetModel = new AsetKomersialModel();
        $filterTahun = $this->request->getGet('tahun_aset') ?? 'semua';
        $query = $asetModel;
        if ($filterTahun !== 'semua') {
            $query->where('tahun_perolehan', $filterTahun);
        }
        $dataAset = $query->orderBy('tahun_perolehan', 'DESC')->findAll();

        $title = 'Laporan Aset Produksi (Komersial)';
        $headers = ['No', 'Nama Aset', 'Kode Aset', 'NUP', 'Tahun', 'Merk/Tipe', 'Nilai Perolehan (Rp)', 'Keterangan'];
        $dataMapping = ['nama_aset', 'kode_aset', 'nup', 'tahun_perolehan', 'merk_type', 'nilai_perolehan', 'keterangan'];

        $totalNilai = array_sum(array_column($dataAset, 'nilai_perolehan'));
        $totalRow = ['cells' => ['Total Nilai Perolehan', 'Rp ' . number_format($totalNilai, 0, ',', '.')], 'start_column' => 6];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->_generateExcelTemplate($sheet, $title, $headers, $dataAset, $dataMapping, $totalRow, ['tahun_aset' => $filterTahun]);

        $filename = 'Laporan_Aset_Komersial_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    // =================================================================================
    // TEMPLATE GENERATOR EXCEL (DISALIN DARI VERSI BUMDES)
    // =================================================================================
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

        // --- PENYESUAIAN DIMULAI DI SINI ---

        // 1. Ambil data nama Ketua BUMDES dari form
        $namaKetua = $pengaturanModel->where('meta_key', 'ketua_komersial')->first()['meta_value'] ?? 'NAMA KETUA BUMDES';

        // 2. Ambil data Admin Komersial dari form
        $jabatanKanan = $pengaturanModel->where('meta_key', 'jabatan_kanan_komersial')->first()['meta_value'] ?? 'Admin Komersial';
        $namaKanan = $pengaturanModel->where('meta_key', 'nama_kanan_komersial')->first()['meta_value'] ?? 'NAMA ADMIN';

        // 3. Ambil lokasi dari form
        $lokasi = $pengaturanModel->where('meta_key', 'lokasi_komersial')->first()['meta_value'] ?? 'LOKASI';

        // --- AKHIR PENYESUAIAN ---

        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $endCol = count($headers);
        $endColStr = Coordinate::stringFromColumnIndex($endCol);

        $sheet->mergeCells('A1:' . $endColStr . '1')->setCellValue('A1', 'UNIT USAHA KOMERSIAL - BUMDES "ALAM LESTARI"');
        $sheet->mergeCells('A2:' . $endColStr . '2')->setCellValue('A2', strtoupper($title));

        $periode = 'Keseluruhan';
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $periode = date('d/m/Y', strtotime($filter['start_date'])) . ' s/d ' . date('d/m/Y', strtotime($filter['end_date']));
        } elseif (!empty($filter['tahun_aset']) && $filter['tahun_aset'] != 'semua') {
            $periode = 'Tahun ' . $filter['tahun_aset'];
        }
        $sheet->mergeCells('A3:' . $endColStr . '3')->setCellValue('A3', 'PERIODE: ' . $periode);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headerRow = 5;
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
                if (is_numeric($value)) {
                    if (strpos(strtolower($headers[$colIndex + 1]), '(kg)') !== false || strpos(strtolower($headers[$colIndex + 1]), 'rata-rata') !== false) {
                        $sheet->getCell($targetCol . $currentRow)->setValueExplicit($value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                        $sheet->getStyle($targetCol . $currentRow)->getNumberFormat()->setFormatCode('#,##0.00');
                    } else if (strpos(strtolower($headers[$colIndex + 1]), '(rp)') !== false) {
                        $sheet->getCell($targetCol . $currentRow)->setValueExplicit($value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                        $sheet->getStyle($targetCol . $currentRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    } else {
                        $sheet->setCellValue($targetCol . $currentRow, $value);
                    }
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
        $colTtdKanan = max(2, $endCol - 2);
        $colTtdKananStr = Coordinate::stringFromColumnIndex($colTtdKanan);

        // --- PENYESUAIAN TANDA TANGAN ---
        // TTD Kiri (Ketua BUMDES)
        $sheet->setCellValue('A' . $rowTtd, 'Mengetahui,');
        $sheet->setCellValue('A' . ($rowTtd + 1), 'Ketua BUMDES'); // Jabatan ditulis langsung
        $sheet->setCellValue('A' . ($rowTtd + 5), $namaKetua); // Nama diambil dari variabel
        $sheet->getStyle('A' . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle('A' . $rowTtd . ':A' . ($rowTtd + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // TTD Kanan (Admin Komersial)
        $sheet->mergeCells($colTtdKananStr . $rowTtd . ':' . $endColStr . $rowTtd)->setCellValue($colTtdKananStr . $rowTtd, $lokasi . ', ' . date('d ') . $bulanIndonesia[(int)date('n')] . date(' Y'));
        $sheet->mergeCells($colTtdKananStr . ($rowTtd + 1) . ':' . $endColStr . ($rowTtd + 1))->setCellValue($colTtdKananStr . ($rowTtd + 1), $jabatanKanan); // Jabatan dari variabel
        $sheet->mergeCells($colTtdKananStr . ($rowTtd + 5) . ':' . $endColStr . ($rowTtd + 5))->setCellValue($colTtdKananStr . ($rowTtd + 5), $namaKanan); // Nama dari variabel
        $sheet->getStyle($colTtdKananStr . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle($colTtdKananStr . $rowTtd . ':' . $endColStr . ($rowTtd + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $dataEndRow = $totalRow ? $currentRow - 2 : $currentRow - 1;
        $styleArray = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]], 'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_LEFT]];
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $dataEndRow)->applyFromArray($styleArray);
        if ($totalRow) {
            $sheet->getStyle('A' . ($dataEndRow + 1) . ':' . $endColStr . ($dataEndRow + 1))->applyFromArray($styleArray);
        }
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getAlignment()->setWrapText(true);
        for ($i = 1; $i <= $endCol; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }
    }

    /**
     * Generator PDF menggunakan Dompdf
     */
    private function generatePdf(string $html, string $filename)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // orientasi
        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => true]);
        exit;
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

    /**
     * Mengambil data logo dan mengubahnya ke Base64.
     */
    private function _getLogoData(): array
    {
        $logoBase64 = '';

        // GUNAKAN ROOTPATH UNTUK MEMBUAT PATH YANG PASTI BENAR
        $pathToLogo = ROOTPATH . 'public/img/Bumdesfix.png';



        if (file_exists($pathToLogo)) {
            $logoType = pathinfo($pathToLogo, PATHINFO_EXTENSION);
            $logoData = file_get_contents($pathToLogo);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }

        return ['logoBase64' => $logoBase64];
    }

    // --- SEMUA FUNGSI PDF YANG SUDAH DISESUAIKAN ---

    public function exportRekapMasukPdf()
    {
        // ... (kode filter dan get data rekapMasuk) ...
        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'petani'     => $this->request->getGet('petani'),
        ];
        $rekapMasuk = $this->rekapService->getRekapKopiMasuk($filter);

        $data = [
            'title'    => 'Laporan Kopi Masuk per Petani',
            'subtitle' => 'Periode: ' . (($filter['start_date'] ?? '-') . ' s/d ' . ($filter['end_date'] ?? '-')),
            'type'     => 'masuk',
            'data'     => $rekapMasuk,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_kopi_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_kopi_masuk_' . date('YmdHis') . '.pdf');
    }

    public function pdfKeluar()
    {
        // ... (kode filter dan get data rekapKeluar) ...
        $filter = $this->request->getGet();
        $rekapKeluar = $this->rekapService->getRekapKopiKeluar($filter);

        $data = [
            'title'    => 'Laporan Kopi Keluar',
            'subtitle' => 'Periode: ' . ($filter['periode'] ?? date('d/m/Y')),
            'type'     => 'keluar',
            'data'     => $rekapKeluar,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_kopi_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_kopi_keluar_' . date('YmdHis') . '.pdf');
    }

    public function pdfStok()
    {
        // ... (kode filter dan get data rekapStok) ...
        $filter = $this->request->getGet();
        $rekapKopiController = new \App\Controllers\KomersialRekapKopi();
        $rekapStokData = $rekapKopiController->getStokAkhir($filter, null, null, false);
        $rekapStok = is_array($rekapStokData) ? $rekapStokData : [];

        $data = [
            'title'    => 'Laporan Stok Akhir Kopi',
            'subtitle' => 'Periode: ' . (($filter['start_date'] ?? 'Semua') . ' s/d ' . ($filter['end_date'] ?? 'Sekarang')),
            'type'     => 'stok',
            'data'     => $rekapStok,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_kopi_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_stok_kopi_' . date('YmdHis') . '.pdf');
    }

    public function pdfPetani()
    {
        // ... (kode filter dan get data petaniData) ...
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];
        $rekapPetaniController = new \App\Controllers\KomersialRekapPetani();
        $petaniData = $rekapPetaniController->_getFilteredPetaniQuery($filters)->findAll();

        $data = [
            'title'      => 'Laporan Data Petani Terdaftar',
            'subtitle'   => 'Unit Usaha Komersial',
            'petaniData' => $petaniData,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_petani_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_petani_' . date('YmdHis') . '.pdf');
    }

    public function pdfAset()
    {
        // ... (kode filter dan get data dataAset) ...
        $asetModel = new AsetKomersialModel();
        $filterTahun = $this->request->getGet('tahun_aset') ?? 'semua';
        $query = $asetModel;
        if ($filterTahun !== 'semua') {
            $query->where('tahun_perolehan', $filterTahun);
        }
        $dataAset = $query->orderBy('tahun_perolehan', 'DESC')->findAll();

        $data = [
            'title'    => 'Laporan Aset Produksi',
            'subtitle' => 'Filter Tahun: ' . ($filterTahun == 'semua' ? 'Semua Tahun' : $filterTahun),
            'asetData' => $dataAset,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_aset_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_aset_produksi_' . date('YmdHis') . '.pdf');
    }
}
