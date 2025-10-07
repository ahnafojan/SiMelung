<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UmkmModel;
use App\Models\PengaturanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanUmkm extends BaseController
{
    /**
     * Menampilkan halaman utama untuk memilih laporan (index).
     */
    public function index()
    {
        // 1. Inisialisasi Model UMKM
        $umkmModel = new UmkmModel();
        // 2. Ambil semua data UMKM
        $dataUmkm = $umkmModel->findAll();

        $data = [
            'title'      => 'Laporan Data UMKM',
            'umkmData'   => $dataUmkm, // <= Data UMKM dikirim ke view
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/index'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan UMKM',
                'url'   => '#',
                'icon'  => 'fas fa-file-alt'
            ]
        ];

        return view('admin_umkm/pengaturan/pengaturanumkm', $data);
    }

    /**
     * Mengekspor data UMKM ke format Excel.
     */
    public function exportExcel()
    {
        $umkmModel = new UmkmModel();

        $dataUmkm = $umkmModel->findAll();

        $title = 'Laporan Data UMKM BUMDES ALAM LESTARI';

        $headers = ['No', 'Nama UMKM', 'Deskripsi', 'Pemilik', 'Alamat', 'Kontak'];

        $dataMapping = ['nama_umkm', 'deskripsi', 'pemilik', 'alamat', 'kontak'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->_generateExcelTemplate($sheet, $title, $headers, $dataUmkm, $dataMapping);

        $filename = 'Laporan_UMKM_BUMDES_ALAM_LESTARI_' . date('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    /**
     * Mengekspor data UMKM ke format PDF.
     */
    public function exportPDF()
    {
        $umkmModel = new UmkmModel();
        $umkmData = $umkmModel->findAll();

        $data = [
            'title'      => 'LAPORAN DATA UMKM',
            'subtitle'   => 'Daftar Usaha Mikro Kecil Menengah BUMDES ALAM LESTARI',
            'umkmData'   => $umkmData,
        ];

        $data = array_merge($data, $this->_getSignatureData(), $this->_getLogoData());

        $html = view('admin_umkm/laporan/laporan_pdf', $data);

        return $this->generatePdf($html, 'laporan_umkm_bumdes_alam_lestari_' . date('YmdHis') . '.pdf');
    }

    // --- FUNGSI HELPER ---

    private function _generateExcelTemplate(&$sheet, string $title, array $headers, array $data, array $dataMapping)
    {
        $pengaturanModel = new PengaturanModel();
        $pengaturanDb = $pengaturanModel->whereIn('meta_key', ['direktur_bumdes', 'lokasi_laporan', 'nama_bumdes'])->findAll();
        $pengaturan = array_column($pengaturanDb, 'meta_value', 'meta_key');

        $direktur = $pengaturan['ketua_bumdes'] ?? 'NAMA KETUA';
        $lokasi = $pengaturan['lokasi_laporan'] ?? 'LOKASI';
        $namaBumdes = $pengaturan['nama_bumdes'] ?? 'BUMDES ALAM LESTARI';

        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $endCol = count($headers);
        $endColStr = Coordinate::stringFromColumnIndex($endCol);

        // Header Kop Surat
        $sheet->mergeCells('A1:' . $endColStr . '1')->setCellValue('A1', 'BADAN USAHA MILIK DESA');
        $sheet->mergeCells('A2:' . $endColStr . '2')->setCellValue('A2', strtoupper($namaBumdes));
        $sheet->mergeCells('A3:' . $endColStr . '3')->setCellValue('A3', 'DESA MELUNG KECAMATAN KEDUNGBANTENG KABUPATEN BANYUMAS');
        $sheet->mergeCells('A4:' . $endColStr . '4')->setCellValue('A4', strtoupper($title));

        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getFont()->setSize(16);

        // Header Tabel
        $headerRow = 6;
        foreach ($headers as $index => $header) {
            $colStr = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue($colStr . $headerRow, $header);
        }
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Isi Data
        $currentRow = $headerRow + 1;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $currentRow, $index + 1);
            foreach ($dataMapping as $colIndex => $key) {
                $targetCol = Coordinate::stringFromColumnIndex($colIndex + 2);
                $value = $item[$key] ?? '';
                $sheet->setCellValue($targetCol . $currentRow, $value);
            }
            $currentRow++;
        }

        // Bagian Tanda Tangan
        $rowTtd = $currentRow + 2;
        $colTtdStart = max(2, $endCol - 2);
        $colTtdStartStr = Coordinate::stringFromColumnIndex($colTtdStart);

        $sheet->mergeCells($colTtdStartStr . $rowTtd . ':' . $endColStr . $rowTtd)->setCellValue($colTtdStartStr . $rowTtd, $lokasi . ', ' . date('d ') . $bulanIndonesia[(int)date('n')] . date(' Y'));
        $sheet->mergeCells($colTtdStartStr . ($rowTtd + 1) . ':' . $endColStr . ($rowTtd + 1))->setCellValue($colTtdStartStr . ($rowTtd + 1), 'Direktur ' . $namaBumdes);
        $sheet->mergeCells($colTtdStartStr . ($rowTtd + 5) . ':' . $endColStr . ($rowTtd + 5))->setCellValue($colTtdStartStr . ($rowTtd + 5), $direktur);

        $signatureRange = $colTtdStartStr . $rowTtd . ':' . $endColStr . ($rowTtd + 5);
        $sheet->getStyle($signatureRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($colTtdStartStr . ($rowTtd + 5))->getFont()->setBold(true)->setUnderline(true);

        $dataEndRow = $currentRow - 1;
        $styleArray = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_LEFT],
        ];
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $dataEndRow)->applyFromArray($styleArray);
        $sheet->getStyle('A' . $headerRow . ':' . $endColStr . $headerRow)->getAlignment()->setWrapText(true);
        for ($i = 1; $i <= $endCol; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }
    }

    protected function _getSignatureData(): array
    {
        $pengaturanModel = new PengaturanModel();
        $pengaturanDb = $pengaturanModel->whereIn('meta_key', ['ketua_bumdes', 'lokasi_laporan', 'nama_bumdes'])->findAll();
        $pengaturan = array_column($pengaturanDb, 'meta_value', 'meta_key');

        return [
            'namaPenandatangan'    => $pengaturan['ketua_bumdes'] ?? '_________________',
            'jabatanPenandatangan' => 'Ketua BUMDES',
            'lokasi'               => $pengaturan['lokasi_laporan'] ?? 'Lokasi',
            'namaBumdes'           => $pengaturan['nama_bumdes'] ?? 'BUMDES ALAM LESTARI',
        ];
    }

    private function generatePdf(string $html, string $filename)
    {
        $options = new Options();
        $options->set('chroot', FCPATH);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => false]);
        exit;
    }

    private function _getLogoData(): array
    {
        // Path logo utama (sesuai yang Anda masukkan)
        $pathToLogo = FCPATH . 'img/BUMDESS.png';

        // Path logo cadangan/default
        $defaultLogoPath = FCPATH . 'img/default_logo.png';

        if (file_exists($pathToLogo)) {
            // Jika logo utama ada, gunakan ini
            return ['logoPath' => $pathToLogo];
        }

        if (file_exists($defaultLogoPath)) {
            // Jika logo utama tidak ada, gunakan logo default (jika ada)
            return ['logoPath' => $defaultLogoPath];
        }

        // Jika kedua-duanya tidak ada, kembalikan path ke logo utama 
        // (atau bisa juga mengembalikan ['logoPath' => null] untuk di-handle di View)
        return ['logoPath' => $pathToLogo];
    }
}
