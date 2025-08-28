<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use App\Services\RekapKopiService;
use App\Models\AsetKomersialModel;

class ExportLaporanKomersial extends LaporanKomersial
{

    protected $rekapService;

    public function __construct()
    {
        $this->rekapService = new RekapKopiService();
    }


    // --- Export Excel ---

    public function excelMasuk()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'petani'     => $this->request->getGet('petani'),
        ];

        $rekapMasuk = $this->rekapService->getRekapKopiMasuk($filter);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Nama Petani');
        $sheet->setCellValue('B1', 'Total Masuk');
        $sheet->setCellValue('C1', 'Tanggal Terakhir');
        $sheet->setCellValue('D1', 'Jumlah Transaksi');
        $sheet->setCellValue('E1', 'Rata-rata Setoran');

        // Data
        $row = 2;
        foreach ($rekapMasuk as $data) {
            $sheet->setCellValue('A' . $row, $data['nama_petani']);
            $sheet->setCellValue('B' . $row, $data['total_masuk']);
            $sheet->setCellValue('C' . $row, $data['tanggal_terakhir']);
            $sheet->setCellValue('D' . $row, $data['jumlah_transaksi']);
            $sheet->setCellValue('E' . $row, number_format($data['rata_rata_setoran'], 2));
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'rekap_kopi_masuk.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }

    public function exportRekapKeluarExcel()
    {
        $filter = $this->request->getGet();
        $rekapKeluar = $this->rekapService->getRekapKopiKeluar($filter);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Jenis Kopi');
        $sheet->setCellValue('C1', 'Tujuan');
        $sheet->setCellValue('D1', 'Jumlah (Kg)');
        $sheet->setCellValue('E1', 'Keterangan');

        // Isi data
        $row = 2;
        foreach ($rekapKeluar as $item) {
            $sheet->setCellValue('A' . $row, $item['tanggal']);
            $sheet->setCellValue('B' . $row, $item['jenis_kopi']);
            $sheet->setCellValue('C' . $row, $item['tujuan']);
            $sheet->setCellValue('D' . $row, $item['jumlah']);
            $sheet->setCellValue('E' . $row, $item['keterangan']);
            $row++;
        }

        // Download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_kopi_keluar_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }



    public function excelStok()
    {
        $filter = $this->request->getGet();

        // 1. Buat instance dari controller yang logikanya sudah benar
        $rekapKopiController = new \App\Controllers\KomersialRekapKopi();

        // 2. Panggil method getStokAkhir yang sudah kita perbaiki sebelumnya.
        //    Parameter 'paginate' diatur ke 'false' agar mendapatkan semua data.
        $stokAkhirData = $rekapKopiController->getStokAkhir($filter, null, null, false);

        // Pastikan kita mengambil array data yang benar dari hasil pemanggilan
        $stokAkhir = is_array($stokAkhirData) ? $stokAkhirData : [];


        // 3. Sisa kode untuk membuat Excel tetap sama
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


    public function exportRekapMasukPdf()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
            'petani'     => $this->request->getGet('petani'),
        ];

        // Ambil data TANPA paginate
        $rekapMasuk = $this->rekapService->getRekapKopiMasuk($filter);

        // Render view ke HTML
        $html = view('admin_komersial/laporan/_kopi_export_pdf', [
            'title'    => 'Laporan Kopi Masuk per Petani',
            'subtitle' => 'Periode: ' .
                (($filter['start_date'] ?? '-') . ' s/d ' . ($filter['end_date'] ?? '-')),
            'type'     => 'masuk',
            'data'     => $rekapMasuk,
            'filter'   => $filter,
        ]);

        // Generate PDF
        return $this->generatePdf($html, 'laporan_kopi_masuk_' . date('YmdHis') . '.pdf');
    }



    /**
     * Export PDF Laporan Kopi Keluar
     */
    public function pdfKeluar()
    {
        $filter = $this->request->getGet();
        $rekapKeluar = $this->rekapService->getRekapKopiKeluar($filter);

        $html = view('admin_komersial/laporan/_kopi_export_pdf', [
            'title'    => 'Laporan Kopi Keluar',
            'subtitle' => 'Periode: ' . ($filter['periode'] ?? date('d/m/Y')),
            'type'     => 'keluar',
            'data'     => $rekapKeluar
        ]);

        return $this->generatePdf($html, 'laporan_kopi_keluar_' . date('YmdHis') . '.pdf');
    }

    /**
     * Export PDF Stok Akhir
     */
    public function pdfStok()
    {
        $filter = $this->request->getGet();

        // 1. Buat instance dari controller yang logikanya sudah benar
        $rekapKopiController = new \App\Controllers\KomersialRekapKopi();

        // 2. Panggil method getStokAkhir yang sudah kita perbaiki sebelumnya.
        $rekapStokData = $rekapKopiController->getStokAkhir($filter, null, null, false);
        $rekapStok = is_array($rekapStokData) ? $rekapStokData : [];

        // 3. Sisa kode untuk membuat PDF tetap sama
        $html = view('admin_komersial/laporan/_kopi_export_pdf', [
            'title'    => 'Laporan Stok Akhir Kopi',
            'subtitle' => 'Periode: ' . (($filter['start_date'] ?? 'Semua') . ' s/d ' . ($filter['end_date'] ?? 'Sekarang')),
            'type'     => 'stok',
            'data'     => $rekapStok
        ]);

        return $this->generatePdf($html, 'laporan_stok_kopi_' . date('YmdHis') . '.pdf');
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



    /**
     * Export data rekap petani terdaftar ke format Excel.
     */
    public function excelPetani()
    {
        // 1. Ambil filter dari URL
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];

        // 2. Buat instance dari controller rekap petani untuk memanggil logikanya
        $rekapPetaniController = new KomersialRekapPetani();

        // 3. Panggil method private _getFilteredPetaniQuery untuk mendapatkan data
        //    Kita perlu mengubah akses method tersebut dari private menjadi public atau protected
        //    Untuk sementara, kita asumsikan sudah diubah menjadi public.
        $petaniData = $rekapPetaniController->_getFilteredPetaniQuery($filters)->findAll();

        // 4. Buat file Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'User ID')
            ->setCellValue('C1', 'Nama Petani')
            ->setCellValue('D1', 'Alamat')
            ->setCellValue('E1', 'No. HP')
            ->setCellValue('F1', 'Jenis Kopi');

        $row = 2;
        foreach ($petaniData as $no => $petani) {
            $sheet->setCellValue('A' . $row, $no + 1);
            $sheet->setCellValue('B' . $row, $petani['user_id']);
            $sheet->setCellValue('C' . $row, $petani['nama']);
            $sheet->setCellValue('D' . $row, $petani['alamat']);
            $sheet->setCellValue('E' . $row, $petani['no_hp']);
            $sheet->setCellValue('F' . $row, $petani['jenis_kopi_list'] ?? 'Tidak Terdata');
            $row++;
        }

        // 5. Kirim file ke browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_petani_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    public function pdfPetani()
    {
        // 1. Ambil filter dari URL
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];

        // 2. Panggil logika yang sama untuk mendapatkan data
        $rekapPetaniController = new KomersialRekapPetani();
        $petaniData = $rekapPetaniController->_getFilteredPetaniQuery($filters)->findAll();

        // 3. Siapkan data untuk dikirim ke view PDF
        $data = [
            'title'      => 'Laporan Data Petani Terdaftar',
            'petaniData' => $petaniData
        ];

        // 4. Render view ke dalam string HTML
        $html = view('admin_komersial/laporan/_petani_export_pdf', $data);

        // 5. Tentukan nama file
        $filename = 'laporan_petani_' . date('YmdHis') . '.pdf';

        // 6. Panggil helper function untuk membuat dan mengirim PDF
        return $this->generatePdf($html, $filename);
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Nama Barang / Aset')
            ->setCellValue('C1', 'Kode Aset')
            ->setCellValue('D1', 'NUP')
            ->setCellValue('E1', 'Tahun')
            ->setCellValue('F1', 'Merk / Tipe')
            ->setCellValue('G1', 'Nilai Perolehan (Rp)')
            ->setCellValue('H1', 'Keterangan');

        $rowIndex = 2;
        foreach ($dataAset as $no => $item) {
            $sheet->setCellValue('A' . $rowIndex, $no + 1);
            $sheet->setCellValue('B' . $rowIndex, $item['nama_aset']);
            $sheet->setCellValue('C' . $rowIndex, $item['kode_aset']);
            $sheet->setCellValue('D' . $rowIndex, $item['nup']);
            $sheet->setCellValue('E' . $rowIndex, $item['tahun_perolehan']);
            $sheet->setCellValue('F' . $rowIndex, $item['merk_type']);
            $sheet->setCellValue('G' . $rowIndex, number_format($item['nilai_perolehan'], 0, ',', '.'));
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

    /**
     * Export data Aset ke format PDF.
     */
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
            'title'       => 'Laporan Aset Produksi',
            'asetData'    => $dataAset,
            'filterTahun' => $filterTahun
        ];

        $html = view('admin_komersial/laporan/_aset_export_pdf', $data);
        $filename = 'laporan_aset_produksi_' . date('YmdHis') . '.pdf';

        // Panggil helper function yang sudah ada
        return $this->generatePdf($html, $filename);
    }
}
