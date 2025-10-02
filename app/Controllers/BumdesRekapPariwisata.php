<?php

namespace App\Controllers;

// Menggunakan semua library yang Anda tentukan
use App\Controllers\BaseController;
use App\Models\AsetPariwisataModel;
use App\Models\ObjekWisataModel; // Mengganti PariwisataModel
use App\Models\PengaturanModel; // Untuk data kop surat & ttd
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Dompdf\Dompdf;
use Dompdf\Options;

class BumdesRekapPariwisata extends BaseController
{
    protected $objekWisataModel;
    protected $asetPariwisataModel;
    protected $pengaturanModel;

    public function __construct()
    {
        $this->objekWisataModel = new ObjekWisataModel();
        $this->asetPariwisataModel = new AsetPariwisataModel();
        $this->pengaturanModel = new PengaturanModel();
    }

    public function index()
    {
        $data = [
            'title'       => 'Laporan Aset Pariwisata',
            // Menggunakan objekWisataModel untuk mengambil data
            'pariwisata'  => $this->objekWisataModel->orderBy('nama_wisata', 'ASC')->findAll(),
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => site_url('dashboard'), 'icon' => 'fas fa-fw fa-tachometer-alt'],
                ['title' => 'Laporan BUMDES', 'url' => site_url('bumdes/laporan'), 'icon' => 'fas fa-fw fa-file-alt'],
                ['title' => 'Laporan Aset Pariwisata', 'url' => '#', 'icon' => 'fas fa-tree']
            ]
        ];
        return view('bumdes/laporan/pariwisata', $data);
    }

    /**
     * Membuat laporan aset dalam format PDF dengan kop surat dinamis.
     */
    public function exportPDF($id)
    {
        $wisata = $this->objekWisataModel->find($id);
        if (!$wisata) {
            return redirect()->back()->with('error', 'Data objek wisata tidak ditemukan.');
        }

        $data = [
            'title'       => 'Laporan Aset Pariwisata',
            'subtitle'    => 'Lokasi: ' . $wisata['nama_wisata'],
            'dataAset'    => $this->asetPariwisataModel->where('wisata_id', $id)->findAll(),
            'pengaturan'  => $this->pengaturanModel->first() // Mengambil data BUMDES
        ];

        // Mengatur opsi Dompdf untuk keamanan dan memuat gambar
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('bumdes/laporan/_pariwisata_export_pdf', $data); // Menggunakan view template
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan_Aset_' . url_title($wisata['nama_wisata'], '_', true) . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 0]);
    }

    /**
     * Membuat laporan aset dalam format Excel dengan kop surat.
     */
    public function exportExcel($id)
    {
        $wisata = $this->objekWisataModel->find($id);
        if (!$wisata) {
            return redirect()->back()->with('error', 'Data objek wisata tidak ditemukan.');
        }

        $asetList = $this->asetPariwisataModel->where('wisata_id', $id)->findAll();
        $pengaturan = $this->pengaturanModel->first();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- STYLING ---
        $centerStyle = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]];
        $boldStyle = ['font' => ['bold' => true]];
        $borderStyle = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]]];

        // --- KOP SURAT ---
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $sheet->setCellValue('A1', 'BADAN USAHA MILIK DESA (BUMDES)')->getStyle('A1')->applyFromArray($boldStyle)->applyFromArray($centerStyle);
        $sheet->setCellValue('A2', $pengaturan['nama_bumdes'] ?? 'NAMA BUMDES')->getStyle('A2')->applyFromArray($boldStyle)->applyFromArray($centerStyle);
        $sheet->setCellValue('A3', $pengaturan['alamat_bumdes'] ?? 'Alamat BUMDES')->getStyle('A3')->applyFromArray($centerStyle);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // --- JUDUL LAPORAN ---
        $sheet->mergeCells('A5:F5');
        $sheet->mergeCells('A6:F6');
        $sheet->setCellValue('A5', 'LAPORAN ASET PARIWISATA')->getStyle('A5')->applyFromArray($boldStyle)->applyFromArray($centerStyle);
        $sheet->setCellValue('A6', 'Lokasi: ' . $wisata['nama_wisata'])->getStyle('A6')->applyFromArray($centerStyle);

        // --- HEADER TABEL ---
        $sheet->setCellValue('A8', 'No')->getStyle('A8')->applyFromArray($boldStyle)->applyFromArray($centerStyle);
        $sheet->setCellValue('B8', 'Nama Aset')->getStyle('B8')->applyFromArray($boldStyle)->applyFromArray($centerStyle);
        $sheet->setCellValue('C8', 'Jumlah')->getStyle('C8')->applyFromArray($boldStyle)->applyFromArray($centerStyle);
        $sheet->setCellValue('D8', 'Kondisi')->getStyle('D8')->applyFromArray($boldStyle)->applyFromArray($centerStyle);
        $sheet->setCellValue('E8', 'Tgl Perolehan')->getStyle('E8')->applyFromArray($boldStyle)->applyFromArray($centerStyle);
        $sheet->setCellValue('F8', 'Keterangan')->getStyle('F8')->applyFromArray($boldStyle)->applyFromArray($centerStyle);

        // --- ISI TABEL ---
        $row = 9;
        foreach ($asetList as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item['nama_aset']);
            $sheet->setCellValue('C' . $row, $item['jumlah']);
            $sheet->setCellValue('D' . $row, $item['kondisi']);
            $sheet->setCellValue('E' . $row, !empty($item['tanggal_perolehan']) ? date('d-m-Y', strtotime($item['tanggal_perolehan'])) : '');
            $sheet->setCellValue('F' . $row, $item['keterangan']);
            $row++;
        }

        // --- FORMATTING AKHIR ---
        $lastRow = $row - 1;
        $sheet->getStyle('A8:F' . $lastRow)->applyFromArray($borderStyle); // Terapkan border ke tabel
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true); // Auto-size kolom
        }

        // --- TANDA TANGAN ---
        $signRow = $lastRow + 3;
        $sheet->mergeCells('E' . $signRow . ':F' . $signRow);
        $sheet->mergeCells('E' . ($signRow + 1) . ':F' . ($signRow + 1));
        $sheet->mergeCells('E' . ($signRow + 5) . ':F' . ($signRow + 5));

        $sheet->setCellValue('E' . $signRow, ($pengaturan['lokasi_surat'] ?? 'Melung') . ', ' . date('d F Y'))->getStyle('E' . $signRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('E' . ($signRow + 1), $pengaturan['jabatan_ketua'] ?? 'Ketua BUMDES')->getStyle('E' . ($signRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('E' . ($signRow + 5), $pengaturan['nama_ketua'] ?? 'Nama Ketua')->getStyle('E' . ($signRow + 5))->applyFromArray($boldStyle)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // --- OUTPUT KE BROWSER ---
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Aset_' . url_title($wisata['nama_wisata'], '_', true) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}
