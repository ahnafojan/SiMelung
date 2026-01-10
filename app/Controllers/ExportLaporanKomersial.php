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
        helper(['session']);
    }

    private function _getSetting(array $candidates, $default = '')
    {
        $model = new PengaturanModel();
        foreach ($candidates as $key) {
            $row = $model->where('meta_key', $key)->first();
            if (!empty($row) && isset($row['meta_value']) && $row['meta_value'] !== '') {
                return $row['meta_value'];
            }
        }
        return $default;
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

        // TAMBAHAN: Total row untuk petani
        $totalRow = [
            'cells' => [
                'Total Petani Terdaftar: ' . count($petaniData) . ' Petani',
            ],
            'start_column' => 1,
            'merge_until' => 6 // Merge seluruh kolom
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Format filter untuk Excel
        $filterInfo = [];
        if (!empty($filters['jenis_kopi'])) {
            $filterInfo['jenis_kopi'] = $filters['jenis_kopi'];
        }
        if (!empty($filters['search'])) {
            $filterInfo['search'] = $filters['search'];
        }

        $this->_generateExcelTemplate($sheet, $title, $headers, $petaniData, $dataMapping, $totalRow, $filterInfo);

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

        // ✅ Ambil data tanpa pagination (semua data)
        $rekapMasuk = $this->rekapService->getRekapKopiMasuk($filter, null, null, false);

        $title = 'Laporan Rekap Kopi Masuk (Komersial)';

        // ✅ Header sesuai view dan gambar
        $headers = [
            'No',
            'Nama Petani',
            'Jenis Kopi',
            'Total Masuk (Kg)',
            'Total Harga Masuk (Rp)',
            'Tanggal',
            'Jumlah Transaksi',
            'Rata-rata Setoran (Kg)'
        ];

        // ✅ Mapping sesuai data dari controller
        $dataMapping = [
            'nama_petani',
            'jenis_kopi',
            'total_masuk',
            'total_nilai_masuk',
            'tanggal_transaksi',
            'jumlah_transaksi',
            'rata_rata_setoran'
        ];

        // ✅ Hitung total dari semua data
        $totalMasuk        = array_sum(array_column($rekapMasuk, 'total_masuk'));
        $totalNilaiMasuk   = array_sum(array_column($rekapMasuk, 'total_nilai_masuk'));
        $totalTransaksi    = array_sum(array_column($rekapMasuk, 'jumlah_transaksi'));
        $avgSetoranGlobal  = $totalTransaksi > 0 ? $totalMasuk / $totalTransaksi : 0;

        // ✅ PERBAIKAN FINAL: Hanya 6 elemen (1 label merge + 5 kolom data)
        $totalRow = [
            'cells' => [
                'TOTAL',                                             // Index 0 → Kolom A-B-C (merge)
                number_format($totalMasuk, 2, ',', '.'),            // Index 1 → Kolom D
                $totalNilaiMasuk,                                    // Index 2 → Kolom E
                '-',                                                 // Index 3 → Kolom F
                $totalTransaksi,                                     // Index 4 → Kolom G
                number_format($avgSetoranGlobal, 2, ',', '.')       // Index 5 → Kolom H
            ],
            'merge_until' => 3 // merge kolom A, B, C (1-3)
        ];

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
        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'petani'     => $this->request->getGet('petani'),
        ];

        // ✅ Ambil SEMUA data tanpa pagination
        $rekapKeluar = $this->rekapService->getRekapKopiKeluar($filter, null, null, false);

        $title = 'Laporan Rekap Kopi Keluar (Komersial)';

        // ✅ Header sesuai view
        $headers = [
            'No',
            'Tanggal',
            'Nama Petani',
            'Jenis Kopi',
            'Tujuan Pembeli',
            'Jumlah (Kg)',
            'Harga Jual (Rp/Kg)',
            'Keuntungan BUMDes (Rp)',
            'Total Harga Jual Petani (Rp)',
            'Keterangan'
        ];

        // ✅ Mapping sesuai data dari controller
        $dataMapping = [
            'tanggal',
            'nama_petani',
            'jenis_kopi',
            'tujuan_pembeli',
            'jumlah_kg',
            'harga_jual_per_kg',
            'keuntungan_bumdes',
            'total_harga_petani',
            'keterangan'
        ];

        // ✅ Hitung total sesuai view
        $totalKeluar       = array_sum(array_column($rekapKeluar, 'jumlah_kg'));
        $totalKeuntungan   = array_sum(array_column($rekapKeluar, 'keuntungan_bumdes'));
        $totalHargaPetani  = array_sum(array_column($rekapKeluar, 'total_harga_petani'));

        // ✅ PERBAIKAN: Total row dengan 6 elemen saja (merge 5 kolom pertama)
        $totalRow = [
            'cells' => [
                'TOTAL KOPI KELUAR',                              // Index 0 → Kolom A-E (merge)
                number_format($totalKeluar, 2, ',', '.'),        // Index 1 → Kolom F (Jumlah Kg)
                '-',                                              // Index 2 → Kolom G (Harga Jual/Kg)
                $totalKeuntungan,                                 // Index 3 → Kolom H (Keuntungan)
                $totalHargaPetani,                                // Index 4 → Kolom I (Total Harga Petani)
                '-'                                               // Index 5 → Kolom J (Keterangan)
            ],
            'merge_until' => 5 // merge kolom A-E (1-5)
        ];

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
        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'petani'     => $this->request->getGet('petani'),
        ];

        // ✅ Stok hanya ikut filter tanggal, petani diabaikan
        $filter['petani'] = '';

        $rekapKopiController = new \App\Controllers\KomersialRekapKopi();
        $stokAkhirData = $rekapKopiController->getStokAkhir($filter, null, null, false);
        $stokAkhir = is_array($stokAkhirData) ? $stokAkhirData : [];

        $title = 'Laporan Stok Akhir Jenis Kopi (Komersial)';

        // ✅ Header sesuai view (tanpa harga)
        $headers = [
            'No',
            'Jenis Kopi',
            'Total Stok Kopi (Kg)',
        ];

        // ✅ Mapping sesuai data (tanpa harga)
        $dataMapping = [
            'jenis_kopi',
            'stok_akhir',
        ];

        // ✅ Total hanya stok
        $totalStok = array_sum(array_column($stokAkhir, 'stok_akhir'));

        // ✅ Total row: merge A-B, isi C
        $totalRow = [
            'cells' => [
                'TOTAL STOK GLOBAL',
                number_format($totalStok, 2, ',', '.'),
            ],
            'merge_until' => 2 // merge kolom A-B
        ];

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
        $namaKetua = $this->_getSetting(['ketua_bumdes', 'nama_ketua_bumdes', 'ketua'], 'NAMA KETUA BUMDES');
        $lokasi    = $this->_getSetting(['lokasi_laporan', 'lokasi_bumdes', 'alamat_bumdes', 'lokasi'], 'LOKASI');

        $jabatanKanan = 'Admin Komersial';
        $namaKanan    = session()->get('username') ?: 'NAMA ADMIN';

        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $endCol = count($headers);
        $endColStr = Coordinate::stringFromColumnIndex($endCol);

        // Header
        $sheet->mergeCells('A1:' . $endColStr . '1')->setCellValue('A1', 'UNIT USAHA KOMERSIAL - BUMDES "ALAM LESTARI"');
        $sheet->mergeCells('A2:' . $endColStr . '2')->setCellValue('A2', strtoupper($title));

        // Filter subtitle
        $periode = 'Keseluruhan';
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $start = $this->_formatTanggalIndonesia($filter['start_date']);
            $end   = $this->_formatTanggalIndonesia($filter['end_date']);
            $periode = $start . ' s/d ' . $end;
        } elseif (!empty($filter['tahun_aset']) && $filter['tahun_aset'] != 'semua') {
            $periode = 'Tahun ' . $filter['tahun_aset'];
        } elseif (!empty($filter['jenis_kopi']) || !empty($filter['search'])) {
            $filterParts = [];
            if (!empty($filter['jenis_kopi'])) $filterParts[] = 'Jenis Kopi: ' . $filter['jenis_kopi'];
            if (!empty($filter['search']))     $filterParts[] = 'Pencarian: "' . $filter['search'] . '"';
            $periode = implode(' | ', $filterParts);
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
        $dataEndRow = $currentRow - 1;

        // Total Row (tetap sama seperti implementasi Anda)
        // Total Row
        if ($totalRow) {
            $mergeUntil = $totalRow['merge_until'] ?? 1;
            $labelStartColStr = 'A';
            $labelEndColStr = Coordinate::stringFromColumnIndex($mergeUntil);

            // Merge cells untuk label
            $sheet->mergeCells($labelStartColStr . $currentRow . ':' . $labelEndColStr . $currentRow);
            $sheet->setCellValue($labelStartColStr . $currentRow, $totalRow['cells'][0]);
            $sheet->getStyle($labelStartColStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // ✅ PERBAIKAN: Loop mulai dari index 1, tapi isi ke kolom setelah merge
            // Karena cells[0] sudah dipakai untuk label merge, cells[1] dst untuk kolom setelah merge
            for ($i = 1; $i < count($totalRow['cells']); $i++) {
                // ✅ PERBAIKAN CRITICAL: Kolom dimulai dari (mergeUntil + 1), bukan (mergeUntil + i)
                $colIndex = $mergeUntil + $i; // Ini yang benar: merge sampai kolom 3, maka mulai dari 4, 5, 6...
                $colStr = Coordinate::stringFromColumnIndex($colIndex);
                $cellValue = $totalRow['cells'][$i];

                // Set value
                if (is_numeric($cellValue) && $cellValue !== '-') {
                    $sheet->getCell($colStr . $currentRow)->setValueExplicit($cellValue, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);

                    // Format berdasarkan header kolom
                    $headerText = $headers[$colIndex - 1] ?? '';
                    if (strpos(strtolower($headerText), '(rp)') !== false) {
                        $sheet->getStyle($colStr . $currentRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    } elseif (strpos(strtolower($headerText), '(kg)') !== false || strpos(strtolower($headerText), 'rata-rata') !== false) {
                        $sheet->getStyle($colStr . $currentRow)->getNumberFormat()->setFormatCode('#,##0.00');
                    }
                    $sheet->getStyle($colStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                } else {
                    $sheet->setCellValue($colStr . $currentRow, $cellValue);

                    if (is_string($cellValue) && strpos($cellValue, 'Rp') !== false) {
                        $sheet->getStyle($colStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    } elseif ($cellValue === '-') {
                        $sheet->getStyle($colStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    } elseif (is_numeric(str_replace(['.', ','], '', (string)$cellValue))) {
                        $sheet->getStyle($colStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    } else {
                        $sheet->getStyle($colStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }
            }

            $sheet->getStyle('A' . $currentRow . ':' . $endColStr . $currentRow)->getFont()->setBold(true);
            $currentRow++;
        }

        // Border + styling (tetap)
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT
            ]
        ];
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $dataEndRow)->applyFromArray($styleArray);
        if ($totalRow) {
            $sheet->getStyle('A' . ($dataEndRow + 1) . ':' . $endColStr . ($dataEndRow + 1))->applyFromArray($styleArray);
        }
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getAlignment()->setWrapText(true);

        // Auto size
        for ($i = 1; $i <= $endCol; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        // Signature Section – gunakan BUMDES + session
        $rowTtd = $currentRow + 2;
        $colTtdKanan = max(2, $endCol - 2);
        $colTtdKananStr = Coordinate::stringFromColumnIndex($colTtdKanan);

        $sheet->setCellValue('A' . $rowTtd, 'Mengetahui,');
        $sheet->setCellValue('A' . ($rowTtd + 1), 'Ketua BUMDES');
        $sheet->setCellValue('A' . ($rowTtd + 5), $namaKetua);
        $sheet->getStyle('A' . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle('A' . $rowTtd . ':A' . ($rowTtd + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $bulanIndonesia2 = $bulanIndonesia[(int)date('n')];
        $sheet->mergeCells($colTtdKananStr . $rowTtd . ':' . $endColStr . $rowTtd)
            ->setCellValue($colTtdKananStr . $rowTtd, $lokasi . ', ' . date('d ') . $bulanIndonesia2 . date(' Y'));
        $sheet->mergeCells($colTtdKananStr . ($rowTtd + 1) . ':' . $endColStr . ($rowTtd + 1))
            ->setCellValue($colTtdKananStr . ($rowTtd + 1), $jabatanKanan);
        $sheet->mergeCells($colTtdKananStr . ($rowTtd + 5) . ':' . $endColStr . ($rowTtd + 5))
            ->setCellValue($colTtdKananStr . ($rowTtd + 5), $namaKanan);

        $sheet->getStyle($colTtdKananStr . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle($colTtdKananStr . $rowTtd . ':' . $endColStr . ($rowTtd + 5))
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    /**
     * Generator PDF menggunakan Dompdf
     */
    private function generatePdf(string $html, string $filename)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => true]);
        exit;
    }
    private function _getSignatureData(): array
    {
        $namaKetua    = $this->_getSetting(['ketua_bumdes', 'nama_ketua_bumdes', 'ketua'], 'NAMA KETUA BUMDES');
        $lokasi       = $this->_getSetting(['lokasi_laporan', 'lokasi_bumdes', 'alamat_bumdes', 'lokasi'], 'LOKASI');

        $jabatanKanan = 'Admin Komersial';
        $namaKanan    = session()->get('username') ?: 'NAMA ADMIN';

        return [
            'namaKetua'    => $namaKetua,
            'jabatanKanan' => $jabatanKanan,
            'namaKanan'    => $namaKanan,
            'lokasi'       => $lokasi,
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
    /**
     * Format tanggal ke format Indonesia (dd Bulan yyyy)
     * 
     * @param string $date Format Y-m-d
     * @return string
     */
    private function _formatTanggalIndonesia($date)
    {
        if (empty($date)) {
            return '';
        }

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

        $timestamp = strtotime($date);
        $hari = date('d', $timestamp);
        $bulan = $bulanIndonesia[(int)date('n', $timestamp)];
        $tahun = date('Y', $timestamp);

        return $hari . ' ' . $bulan . ' ' . $tahun;
    }

    /**
     * Format periode untuk subtitle PDF
     * 
     * @param array $filter
     * @return string
     */
    private function _formatPeriodePDF($filter)
    {
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $start = $this->_formatTanggalIndonesia($filter['start_date']);
            $end = $this->_formatTanggalIndonesia($filter['end_date']);
            return $start . ' s/d ' . $end;
        } elseif (!empty($filter['start_date'])) {
            return 'Sejak ' . $this->_formatTanggalIndonesia($filter['start_date']);
        } elseif (!empty($filter['end_date'])) {
            return 'Sampai ' . $this->_formatTanggalIndonesia($filter['end_date']);
        } elseif (!empty($filter['tahun_aset']) && $filter['tahun_aset'] != 'semua') {
            return 'Tahun ' . $filter['tahun_aset'];
        }

        return 'Semua Periode';
    }

    // --- SEMUA FUNGSI PDF YANG SUDAH DISESUAIKAN ---

    public function exportRekapMasukPdf()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'petani'     => $this->request->getGet('petani'),
        ];

        // ✅ Ambil semua data tanpa pagination
        $rekapMasuk = $this->rekapService->getRekapKopiMasuk($filter, null, null, false);

        $data = [
            'title'    => 'Laporan Rekap Kopi Masuk per Petani (Komersial)',
            'subtitle' => 'Periode: ' . $this->_formatPeriodePDF($filter),
            'type'     => 'masuk_dengan_harga',
            'data'     => $rekapMasuk,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_kopi_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_kopi_masuk_dengan_harga_' . date('YmdHis') . '.pdf');
    }

    public function pdfKeluar()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'petani'     => $this->request->getGet('petani'),
        ];

        // ✅ Ambil SEMUA data tanpa pagination
        $rekapKeluar = $this->rekapService->getRekapKopiKeluar($filter, null, null, false);

        $data = [
            'title'    => 'Laporan Rekap Kopi Keluar (Komersial)',
            'subtitle' => 'Periode: ' . $this->_formatPeriodePDF($filter),
            'type'     => 'keluar_dengan_harga',
            'data'     => $rekapKeluar,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_kopi_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_kopi_keluar_dengan_harga_' . date('YmdHis') . '.pdf');
    }

    public function pdfStok()
    {
        $filter = $this->request->getGet();

        // ✅ Stok hanya ikut filter tanggal, petani diabaikan
        $filter['petani'] = '';

        $rekapKopiController = new \App\Controllers\KomersialRekapKopi();
        $rekapStokData = $rekapKopiController->getStokAkhir($filter, null, null, false);
        $rekapStok = is_array($rekapStokData) ? $rekapStokData : [];

        $data = [
            'title'    => 'Laporan Stok Akhir Kopi (Komersial)',
            'subtitle' => 'Periode: ' . $this->_formatPeriodePDF($filter),
            'type'     => 'stok', // ✅ penting: stok tanpa harga (sesuai view)
            'data'     => $rekapStok,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_kopi_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_stok_kopi_' . date('YmdHis') . '.pdf');
    }


    public function pdfPetani()
    {
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];
        $rekapPetaniController = new \App\Controllers\KomersialRekapPetani();
        $petaniData = $rekapPetaniController->_getFilteredPetaniQuery($filters)->findAll();

        // Format subtitle dengan filter yang diterapkan
        $subtitleParts = ['Unit Usaha Komersial'];

        if (!empty($filters['jenis_kopi'])) {
            $subtitleParts[] = 'Jenis Kopi: ' . esc($filters['jenis_kopi']);
        }

        if (!empty($filters['search'])) {
            $subtitleParts[] = 'Pencarian: "' . esc($filters['search']) . '"';
        }

        $subtitle = implode(' | ', $subtitleParts);

        $data = [
            'title'      => 'Laporan Data Petani Terdaftar',
            'subtitle'   => $subtitle,
            'petaniData' => $petaniData,
            'filters'    => $filters, // Kirim filter untuk info tambahan
            'totalPetani' => count($petaniData), // Total petani
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_petani_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_petani_' . date('YmdHis') . '.pdf');
    }

    public function pdfAset()
    {
        $asetModel = new AsetKomersialModel();
        $filterTahun = $this->request->getGet('tahun_aset') ?? 'semua';
        $query = $asetModel;
        if ($filterTahun !== 'semua') {
            $query->where('tahun_perolehan', $filterTahun);
        }
        $dataAset = $query->orderBy('tahun_perolehan', 'DESC')->findAll();

        $data = [
            'title'    => 'Laporan Aset Produksi (Komersial)',
            'subtitle' => 'Filter: ' . ($filterTahun == 'semua' ? 'Semua Tahun' : 'Tahun ' . $filterTahun),
            'asetData' => $dataAset,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('admin_komersial/laporan/_aset_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_aset_produksi_' . date('YmdHis') . '.pdf');
    }
}
