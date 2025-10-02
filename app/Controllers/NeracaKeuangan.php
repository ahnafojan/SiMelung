<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BkuBulananModel;
use App\Models\MasterNeracaModel;
use App\Models\DetailNeracaModel;
// [BARU] Menambahkan model LabaRugiTahunModel
use App\Models\LabaRugiTahunModel;

// Tambahkan import PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\PengaturanModel;
use App\Models\LogAktivitasModel;

class NeracaKeuangan extends BaseController
{

    /**
     * [DIUBAH] Method ini sekarang mengambil data laba rugi bersih dari tabel laba_rugi_tahunan.
     */
    private function getNeracaData($tahun)
    {
        $masterNeracaModel = new MasterNeracaModel();
        $detailNeracaModel = new DetailNeracaModel();
        // [BARU] Inisialisasi model LabaRugiTahunModel
        $labaRugiTahunModel = new LabaRugiTahunModel();

        // Ambil data laba rugi bersih dari tabel laba_rugi_tahunan
        $labaRugiData = $labaRugiTahunModel->where('tahun', $tahun)->first();
        $surplusDefisitDitahan = $labaRugiData['laba_rugi_bersih'] ?? 0;

        // Ambil semua komponen dari master dan nilai yang sudah tersimpan
        $semuaKomponen = $masterNeracaModel->orderBy('kategori, id')->findAll();
        $nilaiTersimpan = $detailNeracaModel->where('tahun', $tahun)->findAll();
        $nilaiTersimpanMap = array_column($nilaiTersimpan, 'jumlah', 'master_neraca_id');

        // Kelompokkan komponen berdasarkan kategori
        $komponen = [
            'aktiva_lancar' => [],
            'aktiva_tetap' => [],
            'hutang_lancar' => [],
            'hutang_jangka_panjang' => [],
            'modal' => []
        ];
        foreach ($semuaKomponen as $item) {
            $item['jumlah'] = $nilaiTersimpanMap[$item['id']] ?? 0;
            $komponen[$item['kategori']][] = $item;
        }

        return [
            'tahunDipilih' => $tahun,
            'komponen' => $komponen,
            // [DIUBAH] Menggunakan nilai dari laba rugi bersih
            'surplusDefisitDitahan' => $surplusDefisitDitahan
        ];
    }

    private function logAktivitas($aktivitas, $deskripsi, $bku_id = null)
    {
        $logModel = new LogAktivitasModel();
        $logModel->save([
            'username'  => session()->get('username') ?? 'System', // Ambil username dari session
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'bku_id'    => $bku_id
        ]);
    }

    public function index()
    {
        $bkuModel = new BkuBulananModel();

        $data = [
            'title' => 'Laporan Neraca Keuangan',
            'daftar_tahun' => $bkuModel->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll()
        ];

        $tahunDipilih = $this->request->getGet('tahun');

        if ($tahunDipilih) {
            // [DIUBAH] Menggunakan helper function getNeracaData agar lebih rapi dan konsisten
            $neracaData = $this->getNeracaData($tahunDipilih);
            $data = array_merge($data, $neracaData);
        }

        return view('admin_keuangan/neraca_keuangan/index', $data);
    }

    public function simpan()
    {
        $tahun = $this->request->getPost('tahun');
        $jumlah = $this->request->getPost('jumlah');

        $detailNeracaModel = new DetailNeracaModel();

        $db = \Config\Database::connect();
        $db->transStart();

        // Hapus data lama untuk tahun ini
        $detailNeracaModel->where('tahun', $tahun)->delete();

        // Simpan data baru
        if ($jumlah) {
            foreach ($jumlah as $master_id => $nilai) {
                if (!empty($nilai)) {
                    $detailNeracaModel->insert([
                        'tahun' => $tahun,
                        'master_neraca_id' => $master_id,
                        'jumlah' => (float) preg_replace('/[^0-9]/', '', $nilai)
                    ]);
                }
            }
        }

        $db->transCommit();

        // simpan log aktivitas
        $this->logAktivitas('SIMPAN NERACA', "Menyimpan/Memperbarui data Neraca Keuangan untuk tahun {$tahun}.");
        return redirect()->to('/neraca-keuangan?tahun=' . $tahun)->with('success', 'Data Neraca Keuangan berhasil disimpan.');
    }

    public function cetakPdf($tahun = null)
    {
        if (!$tahun) {
            return redirect()->to('/neraca-keuangan');
        }

        // simpan log aktivitas
        $this->logAktivitas('CETAK PDF', "Mencetak laporan Neraca Keuangan (PDF) untuk periode {$tahun}");

        // 1. Ambil semua data yang dibutuhkan (logika sama dengan Excel)
        $data = $this->getNeracaData($tahun);
        $pengaturanModel = new PengaturanModel();
        $ketua = $pengaturanModel->where('meta_key', 'ketua_bumdes')->first()['meta_value'] ?? 'NAMA KETUA';
        $bendahara = $pengaturanModel->where('meta_key', 'bendahara_bumdes')->first()['meta_value'] ?? 'NAMA BENDAHARA';
        $lokasi = $pengaturanModel->where('meta_key', 'lokasi_laporan')->first()['meta_value'] ?? 'LOKASI';
        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        // 2. Kumpulkan semua data untuk dikirim ke view
        $viewData = [
            'tahun' => $tahun,
            'data' => $data, // Ini sudah berisi 'komponen', 'surplusDefisitDitahan', dll.
            'ketua' => $ketua,
            'bendahara' => $bendahara,
            'lokasi' => $lokasi,
            'bulanIndonesia' => $bulanIndonesia,
        ];

        // 3. Render view ke PDF menggunakan Dompdf
        $filename = 'Laporan_Neraca_BUMDES_' . $tahun . '.pdf';

        // Asumsi view ada di app/Views/neraca_keuangan/cetak_pdf.php
        $html = view('admin_keuangan/neraca_keuangan/cetak_pdf', $viewData);



        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Layout potrait cukup untuk 2 kolom
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => false]);
        exit();
    }

    public function cetakExcel($tahun = null)
    {
        if (!$tahun) return redirect()->to('/neraca-keuangan');
        $this->logAktivitas('CETAK EXCEL', "Mencetak laporan Neraca Keuangan (Excel) untuk periode {$tahun}");

        // 1. Ambil semua data yang dibutuhkan
        $data = $this->getNeracaData($tahun);
        $komponen = $data['komponen'];
        $pengaturanModel = new PengaturanModel();
        $ketua = $pengaturanModel->where('meta_key', 'ketua_bumdes')->first()['meta_value'] ?? 'NAMA KETUA';
        $bendahara = $pengaturanModel->where('meta_key', 'bendahara_bumdes')->first()['meta_value'] ?? 'NAMA BENDAHARA';
        $lokasi = $pengaturanModel->where('meta_key', 'lokasi_laporan')->first()['meta_value'] ?? 'LOKASI';
        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Neraca Keuangan ' . $tahun);

        // ... (Sisa kode untuk generate Excel tidak berubah) ...
        $sheet->mergeCells('A1:F1')->setCellValue('A1', 'NERACA KEUANGAN');
        $sheet->mergeCells('A2:F2')->setCellValue('A2', 'PERIODE TAHUN ' . $tahun);
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A4:C4')->setCellValue('A4', 'AKTIVA');
        $sheet->mergeCells('D4:F4')->setCellValue('D4', 'PASIVA');
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        $sheet->getStyle('A4:F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $rowAktiva = 5;
        $sheet->mergeCells('A' . $rowAktiva . ':C' . $rowAktiva)->setCellValue('A' . $rowAktiva, 'Aktiva Lancar');
        $sheet->getStyle('A' . $rowAktiva)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowAktiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowAktiva++;
        $totalAktivaLancar = 0;
        $nomor = 1;
        foreach ($komponen['aktiva_lancar'] as $item) {
            $sheet->setCellValue('A' . $rowAktiva, $nomor++);
            $sheet->setCellValue('B' . $rowAktiva, $item['nama_komponen']);
            $sheet->setCellValue('C' . $rowAktiva, $item['jumlah']);
            $totalAktivaLancar += $item['jumlah'];
            $rowAktiva++;
        }
        $sheet->setCellValue('B' . $rowAktiva, 'JUMLAH AKTIVA LANCAR')->getStyle('B' . $rowAktiva)->getFont()->setBold(true);
        $sheet->setCellValue('C' . $rowAktiva, $totalAktivaLancar)->getStyle('C' . $rowAktiva)->getFont()->setBold(true);
        $rowAktiva += 2;
        $sheet->mergeCells('A' . $rowAktiva . ':C' . $rowAktiva)->setCellValue('A' . $rowAktiva, 'Aktiva Tetap');
        $sheet->getStyle('A' . $rowAktiva)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowAktiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowAktiva++;
        $totalAktivaTetap = 0;
        $nomor = 1;
        foreach ($komponen['aktiva_tetap'] as $item) {
            $sheet->setCellValue('A' . $rowAktiva, $nomor++);
            $sheet->setCellValue('B' . $rowAktiva, $item['nama_komponen']);
            $sheet->setCellValue('C' . $rowAktiva, $item['jumlah']);
            $totalAktivaTetap += $item['jumlah'];
            $rowAktiva++;
        }
        $sheet->setCellValue('B' . $rowAktiva, 'JUMLAH AKTIVA TETAP')->getStyle('B' . $rowAktiva)->getFont()->setBold(true);
        $sheet->setCellValue('C' . $rowAktiva, $totalAktivaTetap)->getStyle('C' . $rowAktiva)->getFont()->setBold(true);
        $rowAktiva++;
        $totalAktiva = $totalAktivaLancar + $totalAktivaTetap;
        $sheet->setCellValue('B' . $rowAktiva, 'TOTAL AKTIVA')->getStyle('A' . $rowAktiva . ':C' . $rowAktiva)->getFont()->setBold(true);
        $sheet->setCellValue('C' . $rowAktiva, $totalAktiva);
        $rowPasiva = 5;
        $sheet->mergeCells('D' . $rowPasiva . ':F' . $rowPasiva)->setCellValue('D' . $rowPasiva, 'Hutang Lancar');
        $sheet->getStyle('D' . $rowPasiva)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowPasiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowPasiva++;
        $totalHutangLancar = 0;
        $nomor = 1;
        foreach ($komponen['hutang_lancar'] as $item) {
            $sheet->setCellValue('D' . $rowPasiva, $nomor++);
            $sheet->setCellValue('E' . $rowPasiva, $item['nama_komponen']);
            $sheet->setCellValue('F' . $rowPasiva, $item['jumlah']);
            $totalHutangLancar += $item['jumlah'];
            $rowPasiva++;
        }
        $sheet->setCellValue('E' . $rowPasiva, 'JUMLAH HUTANG LANCAR')->getStyle('E' . $rowPasiva)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $rowPasiva, $totalHutangLancar)->getStyle('F' . $rowPasiva)->getFont()->setBold(true);
        $rowPasiva += 2;
        $sheet->mergeCells('D' . $rowPasiva . ':F' . $rowPasiva)->setCellValue('D' . $rowPasiva, 'Hutang Jangka Panjang');
        $sheet->getStyle('D' . $rowPasiva)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowPasiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowPasiva++;
        $totalHutangJangkaPanjang = 0;
        $nomor = 1;
        foreach ($komponen['hutang_jangka_panjang'] as $item) {
            $sheet->setCellValue('D' . $rowPasiva, $nomor++);
            $sheet->setCellValue('E' . $rowPasiva, $item['nama_komponen']);
            $sheet->setCellValue('F' . $rowPasiva, $item['jumlah']);
            $totalHutangJangkaPanjang += $item['jumlah'];
            $rowPasiva++;
        }
        $sheet->setCellValue('E' . $rowPasiva, 'JUMLAH HUTANG JANGKA PANJANG')->getStyle('E' . $rowPasiva)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $rowPasiva, $totalHutangJangkaPanjang)->getStyle('F' . $rowPasiva)->getFont()->setBold(true);
        $rowPasiva += 2;
        $sheet->mergeCells('D' . $rowPasiva . ':F' . $rowPasiva)->setCellValue('D' . $rowPasiva, 'Modal');
        $sheet->getStyle('D' . $rowPasiva)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowPasiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowPasiva++;
        $sheet->setCellValue('E' . $rowPasiva, 'Surplus/Defisit Ditahan');
        $sheet->setCellValue('F' . $rowPasiva, $data['surplusDefisitDitahan']);
        $totalModalDinamis = 0;
        $nomor = 1;
        foreach ($komponen['modal'] as $item) {
            $rowPasiva++;
            $sheet->setCellValue('D' . $rowPasiva, $nomor++);
            $sheet->setCellValue('E' . $rowPasiva, $item['nama_komponen']);
            $sheet->setCellValue('F' . $rowPasiva, $item['jumlah']);
            $totalModalDinamis += $item['jumlah'];
        }
        $rowPasiva++;
        $totalModal = $data['surplusDefisitDitahan'] + $totalModalDinamis;
        $sheet->setCellValue('E' . $rowPasiva, 'JUMLAH MODAL')->getStyle('E' . $rowPasiva)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $rowPasiva, $totalModal)->getStyle('F' . $rowPasiva)->getFont()->setBold(true);
        $rowPasiva++;
        $totalPasiva = $totalHutangLancar + $totalHutangJangkaPanjang + $totalModal;
        $sheet->setCellValue('E' . $rowPasiva, 'TOTAL PASIVA')->getStyle('D' . $rowPasiva . ':F' . $rowPasiva)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $rowPasiva, $totalPasiva);
        $lastRow = max($rowAktiva, $rowPasiva) + 1;
        $sheet->mergeCells('A' . $lastRow . ':F' . $lastRow);
        $sheet->getStyle('A' . $lastRow)->getFont()->setBold(true);
        if ($totalAktiva == $totalPasiva) {
            $sheet->setCellValue('A' . $lastRow, 'CHECK BALANCE: SEIMBANG');
            $sheet->getStyle('A' . $lastRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D4EDDA');
        } else {
            $sheet->setCellValue('A' . $lastRow, 'CHECK BALANCE: TIDAK SEIMBANG (Selisih: ' . number_format($totalAktiva - $totalPasiva) . ')');
            $sheet->getStyle('A' . $lastRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F8D7DA');
        }
        $row = $lastRow + 3;
        $sheet->mergeCells('A' . $row . ':C' . $row)->setCellValue('A' . $row, 'Mengetahui,');
        $sheet->mergeCells('A' . ($row + 1) . ':C' . ($row + 1))->setCellValue('A' . ($row + 1), 'Ketua BUMDES');
        $sheet->mergeCells('A' . ($row + 5) . ':C' . ($row + 5))->setCellValue('A' . ($row + 5), $ketua);
        $sheet->getStyle('A' . $row . ':C' . ($row + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . ($row + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->mergeCells('D' . $row . ':F' . $row)->setCellValue('D' . $row, $lokasi . ', ' . date('d ') . $bulanIndonesia[(int)date('m')] . date(' Y'));
        $sheet->mergeCells('D' . ($row + 1) . ':F' . ($row + 1))->setCellValue('D' . ($row + 1), 'Bendahara BUMDES');
        $sheet->mergeCells('D' . ($row + 5) . ':F' . ($row + 5))->setCellValue('D' . ($row + 5), $bendahara);
        $sheet->getStyle('D' . $row . ':F' . ($row + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D' . ($row + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle('C5:C' . $rowAktiva)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F5:F' . $rowPasiva)->getNumberFormat()->setFormatCode('#,##0');
        $styleArray = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]];
        $sheet->getStyle('A4:C' . $rowAktiva)->applyFromArray($styleArray);
        $sheet->getStyle('D4:F' . $rowPasiva)->applyFromArray($styleArray);
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Laporan_Neraca_BUMDES_' . $tahun . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}
