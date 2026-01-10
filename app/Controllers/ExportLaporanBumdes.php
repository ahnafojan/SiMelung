<?php

namespace App\Controllers;

// Ditambahkan: use statement untuk PHPSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Use statement yang sudah ada
use App\Services\RekapKopiService;
use App\Models\AsetKomersialModel;
use App\Models\PengaturanModel;
// Tambahkan baris ini di bawah 'use' statement lainnya
use App\Models\ObjekWisataModel;
use App\Models\AsetPariwisataModel;
use Dompdf\Dompdf;

// Asumsi class LaporanBumdes adalah base controller Anda
class ExportLaporanBumdes extends LaporanBumdes
{
    protected $rekapService;

    public function __construct()
    {
        $this->rekapService = new RekapKopiService();
    }

    // --- FUNGSI-FUNGSI EXPORT EXCEL ---

    public function excelPetani()
    {
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];
        $rekapPetaniController = new \App\Controllers\BumdesRekapPetani(); // Pastikan controller ini benar
        $petaniData = $rekapPetaniController->_getFilteredPetaniQuery($filters)->findAll();

        $title = 'Laporan Data Petani Terdaftar';
        $headers = ['No', 'User ID', 'Nama Petani', 'Alamat', 'No. HP', 'Jenis Kopi'];
        $dataMapping = ['user_id', 'nama', 'alamat', 'no_hp', 'jenis_kopi_list'];

        // TAMBAHAN: Total row untuk petani
        $totalRow = [
            'cells' => [
                'Total Petani Terdaftar: ' . count($petaniData) . ' Petani',
            ],
            'start_column' => 1, // Kolom A
            'merge_until' => 6 // Merge kolom A hingga F (ada 6 header)
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

        $filename = 'Laporan_Petani_Bumdes_' . date('Ymd') . '.xlsx';
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

        $title = 'Laporan Rekap Kopi Masuk per Petani';

        // ✅ Header sesuai dengan versi komersial yang lebih detail
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

        // ✅ Mapping sesuai data dari service
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

        // ✅ PERBAIKAN: Hanya 6 elemen (1 label merge + 5 kolom data setelah merge)
        // Kolom A, B, C (merge) -> Index 0, lalu D, E, F, G, H (kolom 4-8) -> Index 1-5
        $totalRow = [
            'cells' => [
                'TOTAL',                                             // Index 0 → Kolom A-B-C (merge)
                number_format($totalMasuk, 2, ',', '.'),            // Index 1 → Kolom D (Total Masuk)
                $totalNilaiMasuk,                                    // Index 2 → Kolom E (Total Harga)
                '-',                                                 // Index 3 → Kolom F (Tanggal)
                $totalTransaksi,                                     // Index 4 → Kolom G (Jumlah Transaksi)
                number_format($avgSetoranGlobal, 2, ',', '.')       // Index 5 → Kolom H (Rata-rata)
            ],
            'merge_until' => 3 // merge kolom A, B, C (indeks 1, 2, 3)
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->_generateExcelTemplate($sheet, $title, $headers, $rekapMasuk, $dataMapping, $totalRow, $filter);

        $filename = 'Laporan_Kopi_Masuk_Bumdes_' . date('Ymd') . '.xlsx';
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

        $title = 'Laporan Rekap Kopi Keluar (Penjualan)';

        // ✅ Header sesuai versi komersial yang lebih detail
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

        // ✅ Mapping sesuai data dari service
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

        // ✅ Hitung total sesuai data
        $totalKeluar       = array_sum(array_column($rekapKeluar, 'jumlah_kg'));
        $totalKeuntungan   = array_sum(array_column($rekapKeluar, 'keuntungan_bumdes'));
        $totalHargaPetani  = array_sum(array_column($rekapKeluar, 'total_harga_petani'));

        // ✅ PERBAIKAN: Total row dengan 6 elemen (merge 5 kolom pertama A-E)
        // Kolom A-E (merge) -> Index 0, lalu F, G, H, I, J -> Index 1-5
        $totalRow = [
            'cells' => [
                'TOTAL KOPI KELUAR',                              // Index 0 → Kolom A-E (merge)
                number_format($totalKeluar, 2, ',', '.'),        // Index 1 → Kolom F (Jumlah Kg)
                '-',                                              // Index 2 → Kolom G (Harga Jual/Kg)
                $totalKeuntungan,                                 // Index 3 → Kolom H (Keuntungan)
                $totalHargaPetani,                                // Index 4 → Kolom I (Total Harga Petani)
                '-'                                               // Index 5 → Kolom J (Keterangan)
            ],
            'merge_until' => 5 // merge kolom A-E (indeks 1-5)
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->_generateExcelTemplate($sheet, $title, $headers, $rekapKeluar, $dataMapping, $totalRow, $filter);

        $filename = 'Laporan_Kopi_Keluar_Bumdes_' . date('Ymd') . '.xlsx';
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

        $rekapKopiController = new \App\Controllers\BumdesRekapKopi(); // Pastikan controller ini benar
        $stokAkhirData = $rekapKopiController->getStokAkhir($filter, null, null, false);
        $stokAkhir = is_array($stokAkhirData) ? $stokAkhirData : [];

        $title = 'Laporan Stok Akhir Jenis Kopi';

        // Header sesuai versi komersial
        $headers = [
            'No',
            'Jenis Kopi',
            'Total Stok Kopi (Kg)',
        ];

        $dataMapping = [
            'jenis_kopi',
            'stok_akhir',
        ];


        // Hitung total dari semua data
        $totalStok = array_sum(array_column($stokAkhir, 'stok_akhir'));

        // Total row dengan 4 elemen (merge A-B, lalu C dan D)
        $totalRow = [
            'cells' => [
                'TOTAL STOK GLOBAL',                            // Index 0 → Kolom A-B (merge)
                number_format($totalStok, 2, ',', '.'),        // Index 1 → Kolom C (Total Stok Kg)                                 // Index 2 → Kolom D (Total Nilai/Harga)
            ],
            'merge_until' => 2 // merge kolom A-B (indeks 1-2)
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->_generateExcelTemplate($sheet, $title, $headers, $stokAkhir, $dataMapping, $totalRow, $filter);

        $filename = 'Laporan_Stok_Kopi_Bumdes_' . date('Ymd') . '.xlsx';
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

        $title = 'Laporan Aset Produksi BUMDes';
        $headers = ['No', 'Nama Aset', 'Kode Aset', 'NUP', 'Tahun', 'Merk/Tipe', 'Nilai Perolehan (Rp)', 'Keterangan'];
        $dataMapping = ['nama_aset', 'kode_aset', 'nup', 'tahun_perolehan', 'merk_type', 'nilai_perolehan', 'keterangan'];

        $totalNilai = array_sum(array_column($dataAset, 'nilai_perolehan'));
        $totalRow = [
            'cells' => [
                'Total Nilai Perolehan',
                'Rp ' . number_format($totalNilai, 0, ',', '.')
            ],
            'merge_until' => 6 // Kolom A-F (indeks 1-6) -> merge A-F, nilai di kolom G
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->_generateExcelTemplate($sheet, $title, $headers, $dataAset, $dataMapping, $totalRow, ['tahun_aset' => $filterTahun]);

        $filename = 'Laporan_Aset_Bumdes_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    // --- FUNGSI EXPORT PARIWISATA (BARU) ---

    public function excelPariwisata()
    {
        $wisataId = $this->request->getGet('id');
        if (!$wisataId) {
            die('ID Objek Wisata tidak ditemukan.');
        }

        $objekWisataModel = new ObjekWisataModel();
        $asetPariwisataModel = new AsetPariwisataModel();

        $wisata = $objekWisataModel->find($wisataId);

        $dataAset = $asetPariwisataModel
            ->select('aset_pariwisata.*') // Pilih semua kolom dari tabel aset
            ->join('aset_wisata', 'aset_wisata.aset_id = aset_pariwisata.id')
            ->where('aset_wisata.wisata_id', $wisataId)
            ->findAll();
        // ====================================================================

        $title = 'Laporan Aset Pariwisata';
        $headers = ['No', 'Nama Aset', 'Jumlah', 'Kondisi', 'Tgl Perolehan', 'Keterangan'];

        // Sesuaikan mapping jika nama kolom di DB berbeda
        $dataMapping = ['nama_aset', 'jumlah', 'kondisi', 'tanggal_perolehan', 'keterangan'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->_generateExcelTemplate($sheet, $title, $headers, $dataAset, $dataMapping, null, ['lokasi_wisata' => $wisata['nama_wisata']]);

        $filename = 'Laporan_Aset_Pariwisata_' . url_title($wisata['nama_wisata'], '_', true) . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function pdfPariwisata()
    {
        $wisataId = $this->request->getGet('id');
        if (!$wisataId) {
            die('ID Objek Wisata tidak ditemukan.');
        }

        $objekWisataModel = new ObjekWisataModel();
        $asetPariwisataModel = new AsetPariwisataModel();

        $wisata = $objekWisataModel->find($wisataId);

        // KODE LAMA (DIHAPUS):
        // $dataAset = $asetPariwisataModel->where('wisata_id', $wisataId)->findAll();

        // ====================================================================
        // KODE BARU DENGAN JOIN (diterapkan juga di sini):
        $dataAset = $asetPariwisataModel
            ->select('aset_pariwisata.*')
            ->join('aset_wisata', 'aset_wisata.aset_id = aset_pariwisata.id')
            ->where('aset_wisata.wisata_id', $wisataId)
            ->findAll();
        // ====================================================================

        $data = [
            'title'     => 'Laporan Aset Pariwisata',
            'subtitle'  => 'Lokasi: ' . $wisata['nama_wisata'],
            'asetData'  => $dataAset, // Nama variabel disamakan dengan view _pariwisata_export_pdf.php
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());

        $html = view('bumdes/laporan/_pariwisata_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_aset_pariwisata_' . url_title($wisata['nama_wisata'], '_', true) . '.pdf');
    }


    // --- FUNGSI-FUNGSI EXPORT PDF ---

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
            'title'    => 'Laporan Rekap Kopi Masuk per Petani',
            'subtitle' => 'Periode: ' . $this->_formatPeriodePDF($filter), // Gunakan fungsi format dari komersial
            'type'     => 'masuk_dengan_harga', // Type untuk view
            'data'     => $rekapMasuk,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('bumdes/laporan/_kopi_export_pdf', $data); // Pastikan view ini mendukung type 'masuk_dengan_harga'
        return $this->generatePdf($html, 'laporan_kopi_masuk_bumdes_' . date('YmdHis') . '.pdf');
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
            'title'    => 'Laporan Rekap Kopi Keluar (Penjualan)',
            'subtitle' => 'Periode: ' . $this->_formatPeriodePDF($filter), // Gunakan fungsi format dari komersial
            'type'     => 'keluar_dengan_harga', // Type untuk view
            'data'     => $rekapKeluar,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('bumdes/laporan/_kopi_export_pdf', $data); // Pastikan view ini mendukung type 'keluar_dengan_harga'
        return $this->generatePdf($html, 'laporan_kopi_keluar_bumdes_' . date('YmdHis') . '.pdf');
    }

    public function pdfStok()
    {
        $filter = $this->request->getGet();
        $rekapKopiController = new \App\Controllers\BumdesRekapKopi(); // Pastikan controller ini benar
        $rekapStokData = $rekapKopiController->getStokAkhir($filter, null, null, false);
        $rekapStok = is_array($rekapStokData) ? $rekapStokData : [];

        $data = [
            'title'    => 'Laporan Stok Akhir Kopi',
            'subtitle' => 'Periode: ' . $this->_formatPeriodePDF($filter), // Gunakan fungsi format dari komersial
            'type'     => 'stok_dengan_nilai', // Type untuk view
            'data'     => $rekapStok,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());
        $html = view('bumdes/laporan/_kopi_export_pdf', $data); // Pastikan view ini mendukung type 'stok_dengan_nilai'
        return $this->generatePdf($html, 'laporan_stok_kopi_dengan_nilai_bumdes_' . date('YmdHis') . '.pdf');
    }

    public function pdfPetani()
    {
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];
        $rekapPetaniController = new \App\Controllers\BumdesRekapPetani(); // Pastikan controller ini benar
        $petaniData = $rekapPetaniController->_getFilteredPetaniQuery($filters)->findAll();

        // Format subtitle dengan filter yang diterapkan
        $subtitleParts = ['Diawasi oleh BUMDES Pusat'];

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
        $html = view('bumdes/laporan/_petani_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_petani_bumdes_' . date('YmdHis') . '.pdf');
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
            'title'    => 'Laporan Aset Produksi',
            'subtitle' => 'Filter Tahun: ' . ($filterTahun == 'semua' ? 'Semua Tahun' : $filterTahun),
            'asetData' => $dataAset,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());

        $html = view('bumdes/laporan/_aset_export_pdf', $data);
        return $this->generatePdf($html, 'laporan_aset_produksi_bumdes_' . date('YmdHis') . '.pdf');
    }


    // --- FUNGSI HELPER ---

    private function _generateExcelTemplate(
        &$sheet,
        string $title,
        array $headers,
        array $data,
        array $dataMapping,
        ?array $totalRow = null,
        ?array $filter = null
    ) {
        // Gunakan fungsi helper dari komersial untuk setting
        $namaKetua = $this->_getSetting(['ketua_bumdes', 'nama_ketua_bumdes', 'ketua'], 'NAMA KETUA BUMDES');
        $lokasi    = $this->_getSetting(['lokasi_laporan', 'lokasi_bumdes', 'alamat_bumdes', 'lokasi'], 'LOKASI');

        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $endCol = count($headers);
        $endColStr = Coordinate::stringFromColumnIndex($endCol);

        // Header
        $sheet->mergeCells('A1:' . $endColStr . '1')->setCellValue('A1', 'BADAN USAHA MILIK DESA "ALAM LESTARI"');
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
        } elseif (!empty($filter['lokasi_wisata'])) {
            $periode = 'Lokasi: ' . $filter['lokasi_wisata'];
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

        // Total Row
        if ($totalRow) {
            $mergeUntil = $totalRow['merge_until'] ?? 1;
            $labelStartColStr = 'A';
            $labelEndColStr = Coordinate::stringFromColumnIndex($mergeUntil);

            // Merge cells untuk label
            $sheet->mergeCells($labelStartColStr . $currentRow . ':' . $labelEndColStr . $currentRow);
            $sheet->setCellValue($labelStartColStr . $currentRow, $totalRow['cells'][0]);
            $sheet->getStyle($labelStartColStr . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Loop mulai dari index 1, tapi isi ke kolom setelah merge
            for ($i = 1; $i < count($totalRow['cells']); $i++) {
                // Kolom dimulai dari (mergeUntil + 1), bukan (mergeUntil + i)
                $colIndex = $mergeUntil + $i; // Misal mergeUntil = 3, maka isi ke kolom 4, 5, 6...
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

        // Border + styling
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

        // Signature Section - Tetap menggunakan signature BUMDES (satu tanda tangan)
        $rowTtd = $currentRow + 2;
        $colTtdStart = max(2, $endCol - 2);
        $colTtdStartStr = Coordinate::stringFromColumnIndex($colTtdStart);

        $sheet->mergeCells($colTtdStartStr . $rowTtd . ':' . $endColStr . $rowTtd)
            ->setCellValue($colTtdStartStr . $rowTtd, $lokasi . ', ' . date('d ') . $bulanIndonesia[(int)date('n')] . date(' Y'));
        $sheet->mergeCells($colTtdStartStr . ($rowTtd + 1) . ':' . $endColStr . ($rowTtd + 1))
            ->setCellValue($colTtdStartStr . ($rowTtd + 1), 'Ketua BUMDES');
        $sheet->mergeCells($colTtdStartStr . ($rowTtd + 5) . ':' . $endColStr . ($rowTtd + 5))
            ->setCellValue($colTtdStartStr . ($rowTtd + 5), $namaKetua);

        $signatureRange = $colTtdStartStr . $rowTtd . ':' . $endColStr . ($rowTtd + 5);
        $sheet->getStyle($signatureRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($colTtdStartStr . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);
    }

    private function generatePdf(string $html, string $filename)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => false]); // Tetap false jika ingin inline
        exit;
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

    // Helper untuk format tanggal Indonesia
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

    // Helper untuk format periode PDF
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

    protected function _getSignatureData(): array
    {
        $pengaturanModel = new PengaturanModel();
        $ketuaBumdes = $pengaturanModel->where('meta_key', 'ketua_bumdes')->first()['meta_value'] ?? 'NAMA KETUA BUMDES';
        $lokasiLaporan = $pengaturanModel->where('meta_key', 'lokasi_laporan')->first()['meta_value'] ?? 'LOKASI';

        return [
            'namaPenandatangan'    => $ketuaBumdes,
            'jabatanPenandatangan' => 'Ketua BUMDES',
            'lokasi'               => $lokasiLaporan,
        ];
    }
}
