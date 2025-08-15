<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Services;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class LaporanKomersial extends BaseController
{
    public function index()
    {
        $db = db_connect();

        // Ambil filter dari GET
        $filter = [
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date'   => $this->request->getGet('end_date') ?? '',
            'petani'     => $this->request->getGet('petani') ?? ''
        ];

        // Ambil daftar petani untuk dropdown
        $petaniList = $db->table('petani')
            ->select('id, nama as nama_petani')
            ->get()
            ->getResultArray();

        // Ambil data rekap
        $rekapData = $this->rekapKopi($filter);

        // Paging manual (karena groupBy)
        $pager   = Services::pager();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;
        $totalRows = count($rekapData);
        $rekapPaged = array_slice($rekapData, ($page - 1) * $perPage, $perPage);

        // Hitung total keluar global (untuk footer tabel)
        $builderKeluar = $db->table('kopi_keluar')->selectSum('jumlah');
        if (!empty($filter['start_date'])) {
            $builderKeluar->where('tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builderKeluar->where('tanggal <=', $filter['end_date']);
        }
        $totalKeluarGlobal = (float) ($builderKeluar->get()->getRow()->jumlah ?? 0);

        return view('admin_komersial/laporan/index', [
            'petaniList'         => $petaniList,
            'rekap'              => $rekapPaged,
            'pager'              => $pager->makeLinks($page, $perPage, $totalRows),
            'filter'             => $filter,
            'totalKeluarGlobal'  => $totalKeluarGlobal
        ]);
    }

    private function rekapKopi($filter)
    {
        $db = \Config\Database::connect();

        // Query total masuk, tanggal terakhir, jumlah transaksi per petani
        $builderMasuk = $db->table('kopi_masuk')
            ->select('petani.nama AS nama_petani')
            ->select('SUM(kopi_masuk.jumlah) AS total_masuk')
            ->select('MAX(kopi_masuk.tanggal) AS tanggal_terakhir')
            ->select('COUNT(kopi_masuk.id) AS jumlah_transaksi')
            ->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left')
            ->groupBy('petani.nama');

        // Filter tanggal masuk
        if (!empty($filter['start_date'])) {
            $builderMasuk->where('kopi_masuk.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builderMasuk->where('kopi_masuk.tanggal <=', $filter['end_date']);
        }
        // Filter petani
        if (!empty($filter['petani'])) {
            $builderMasuk->where('petani.id', $filter['petani']);
        }

        $masukData = $builderMasuk->get()->getResultArray();

        // Hitung total keluar global (tidak per petani)
        $builderKeluar = $db->table('kopi_keluar')
            ->select('SUM(jumlah) AS total_keluar');
        if (!empty($filter['start_date'])) {
            $builderKeluar->where('tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builderKeluar->where('tanggal <=', $filter['end_date']);
        }
        $keluarTotal = (float) ($builderKeluar->get()->getRow()->total_keluar ?? 0);

        // Satukan hasil rekap
        $rekap = [];
        foreach ($masukData as $row) {
            $stokAkhir = $row['total_masuk'] - $keluarTotal;
            $rekap[] = [
                'nama_petani'      => $row['nama_petani'] ?? '-',
                'total_masuk'      => (float) ($row['total_masuk'] ?? 0),
                'tanggal_terakhir' => $row['tanggal_terakhir'] ?? '-',
                'jumlah_transaksi' => (int) ($row['jumlah_transaksi'] ?? 0),
                'stok_akhir'       => $stokAkhir
            ];
        }

        return $rekap;
    }
    public function export()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date'   => $this->request->getGet('end_date') ?? '',
            'petani'     => $this->request->getGet('petani') ?? ''
        ];

        $rekap = $this->rekapKopi($filter);

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Petani');
        $sheet->setCellValue('C1', 'Total Masuk (Kg)');
        $sheet->setCellValue('D1', 'Tanggal Masuk Terakhir');
        $sheet->setCellValue('E1', 'Jumlah Transaksi');
        $sheet->setCellValue('F1', 'Stok Akhir (Kg)');

        // Data
        $no = 1;
        $rowIndex = 2;
        foreach ($rekap as $row) {
            $sheet->setCellValue('A' . $rowIndex, $no++);
            $sheet->setCellValue('B' . $rowIndex, $row['nama_petani']);
            $sheet->setCellValue('C' . $rowIndex, $row['total_masuk']);
            $sheet->setCellValue('D' . $rowIndex, $row['tanggal_terakhir']);
            $sheet->setCellValue('E' . $rowIndex, $row['jumlah_transaksi']);
            $sheet->setCellValue('F' . $rowIndex, $row['stok_akhir']);
            $rowIndex++;
        }

        // Output Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'rekap_kopi_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $filter = [
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date'   => $this->request->getGet('end_date') ?? '',
            'petani'     => $this->request->getGet('petani') ?? ''
        ];

        $rekap = $this->rekapKopi($filter);

        // Buat HTML untuk PDF
        $html = '<h3>Laporan Rekap Kopi</h3>';
        $html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Petani</th>
                        <th>Total Masuk (Kg)</th>
                        <th>Tanggal Masuk Terakhir</th>
                        <th>Jumlah Transaksi</th>
                        <th>Stok Akhir (Kg)</th>
                    </tr>
                </thead>
                <tbody>';
        $no = 1;
        foreach ($rekap as $row) {
            $html .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . $row['nama_petani'] . '</td>
                    <td>' . number_format($row['total_masuk'], 2) . '</td>
                    <td>' . $row['tanggal_terakhir'] . '</td>
                    <td>' . $row['jumlah_transaksi'] . '</td>
                    <td>' . number_format($row['stok_akhir'], 2) . '</td>
                  </tr>';
        }
        $html .= '</tbody></table>';

        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('rekap_kopi_' . date('YmdHis') . '.pdf');
    }
}
