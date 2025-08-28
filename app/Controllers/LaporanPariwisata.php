<?php

namespace App\Controllers;

use App\Models\AsetPariwisataModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanPariwisata extends BaseController
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetPariwisataModel();
    }

    // Halaman daftar laporan
    public function index()
    {
        $data['asets'] = $this->asetModel->findAll();
        return view('aset_pariwisata/laporan/pariwisata', $data);
    }

    // Export Excel
    public function exportExcel()
    {
        $dataAsets = $this->asetModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'Nama Aset')
              ->setCellValue('C1', 'Lokasi')
              ->setCellValue('D1', 'Kode Aset')
              ->setCellValue('E1', 'NUP')
              ->setCellValue('F1', 'Tahun')
              ->setCellValue('G1', 'Nilai')
              ->setCellValue('H1', 'Keterangan');

        $row = 2;
        $no = 1;
        foreach($dataAsets as $aset){
            $sheet->setCellValue('A'.$row, $no++)
                  ->setCellValue('B'.$row, $aset['nama_aset'])
                  ->setCellValue('C'.$row, $aset['nama_pariwisata'])
                  ->setCellValue('D'.$row, $aset['kode_aset'])
                  ->setCellValue('E'.$row, $aset['nup'])
                  ->setCellValue('F'.$row, $aset['tahun_perolehan'])
                  ->setCellValue('G'.$row, $aset['nilai_perolehan'])
                  ->setCellValue('H'.$row, $aset['keterangan']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan_Aset_Pariwisata.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    // Export PDF
    public function exportPDF()
    {
        $data['asets'] = $this->asetModel->findAll();
        $dompdf = new Dompdf();
        $html = view('aset_pariwisata/laporan/pariwisata_pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Laporan_Aset_Pariwisata.pdf', ['Attachment' => false]);
    }
}
