<?php

namespace App\Controllers;

use App\Models\UmkmModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class PengaturanUmkm extends BaseController
{
    // === HALAMAN EXPORT ===
    public function export()
    {
        $umkmModel = new UmkmModel();
        $data['umkm'] = $umkmModel->findAll();

        return view('admin_umkm/pengaturan/pengaturanumkm', [
            'title' => 'Pengaturan Export UMKM',
            'umkm'  => $data['umkm']
        ]);
    }

    // === EXPORT EXCEL ===
    public function exportUmkmExcel()
    {
        $umkmModel = new UmkmModel();
        $dataUmkm = $umkmModel->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul
        $sheet->setCellValue('A1', 'Laporan Data UMKM Desa Melung');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Header
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama UMKM');
        $sheet->setCellValue('C3', 'Deskripsi');
        $sheet->setCellValue('D3', 'Pemilik');
        $sheet->setCellValue('E3', 'Alamat');
        $sheet->setCellValue('F3', 'Kontak');

        // Styling header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A3:F3')->applyFromArray($headerStyle);

        // Isi Data
        $row = 4;
        $no = 1;
        foreach ($dataUmkm as $u) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $u['nama_umkm']);
            $sheet->setCellValue('C' . $row, $u['deskripsi']);
            $sheet->setCellValue('D' . $row, $u['pemilik']);
            $sheet->setCellValue('E' . $row, $u['alamat']);
            $sheet->setCellValue('F' . $row, $u['kontak']);
            $row++;
        }

        // Styling data (border + alignment)
        $dataStyle = [
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $sheet->getStyle('A4:F' . ($row - 1))->applyFromArray($dataStyle);
        $sheet->getStyle('A4:A' . ($row - 1))->getAlignment()->setHorizontal('center'); // No di tengah

        // Atur lebar kolom otomatis
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_umkm_' . date('Y-m-d_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    // === EXPORT PDF ===
    public function exportUmkmPdf()
    {
        $umkmModel = new UmkmModel();
        $data['umkmData'] = $umkmModel->findAll();
        $data['title'] = "Laporan Data UMKM Desa Melung";
        $data['subtitle'] = "Daftar UMKM yang terdaftar";
        $data['lokasi'] = "Desa Melung";

        $html = view('admin_umkm/laporan/laporan_pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("laporan_umkm.pdf", ["Attachment" => true]);
    }
}
