<?php

namespace App\Controllers;

// Import semua class yang dibutuhkan dari Spreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Import semua model yang akan digunakan
use App\Models\BkuBulananModel;
use App\Models\DetailPendapatanModel;
use App\Models\DetailPengeluaranModel;
use App\Models\MasterKategoriPengeluaranModel;
use App\Models\DetailAlokasiModel;
use App\Models\PengaturanModel;
use App\Models\MasterNeracaModel;
use App\Models\DetailNeracaModel;
use App\Models\LogAktivitasModel;
use App\Models\MasterLabaRugiModel;
use App\Models\DetailLabaRugiModel;
use App\Models\MasterArusKasModel;
use App\Models\DetailArusKasModel;
use App\Models\LabaRugiTahunModel;
use App\Models\MasterPerubahanModalModel;
use App\Models\DetailPerubahanModalModel;

class LaporanController extends BaseController
{

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
    /**
     * Method utama yang akan dipanggil oleh route untuk membuat file Excel multi-sheet.
     */
    public function cetakPaketLengkap($tahun)
    {
        $this->logAktivitas('CETAK PAKET LENGKAP', "Mencetak Laporan Lengkap BUMDES (Excel) untuk periode {$tahun}");
        // 1. Buat SATU objek Spreadsheet utama
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Hapus sheet default

        // 2. Generate semua BKU Bulanan
        $bkuBulananModel = new BkuBulananModel();
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $laporanBulanan = $bkuBulananModel->where('tahun', $tahun)->where('bulan', $bulan)->first();
            if ($laporanBulanan) {
                $this->_generateBkuBulananSheet($spreadsheet, $laporanBulanan['id']);
            }
        }

        // 3. Generate BKU Tahunan
        $this->_generateBkuTahunanSheet($spreadsheet, $tahun);

        // 4. [BARU] Generate Laporan Laba Rugi
        $this->_generateLabaRugiSheet($spreadsheet, $tahun);

        // 5. [BARU] Generate Laporan Perubahan Modal
        $this->_generatePerubahanModalSheet($spreadsheet, $tahun);

        // 4. Generate Neraca Keuangan
        $this->_generateNeracaSheet($spreadsheet, $tahun);

        // 5. [BARU] Generate Laporan Arus Kas
        $this->_generateArusKasSheet($spreadsheet, $tahun);

        // 5. Finalisasi dan kirim file Excel ke browser (HANYA SEKALI DI SINI)
        if ($spreadsheet->getSheetCount() > 0) {
            $spreadsheet->setActiveSheetIndex(0);
        } else {
            // Jika tidak ada data sama sekali, tampilkan pesan atau redirect
            return redirect()->to('/bku-tahunan?tahun=' . $tahun)->with('error', 'Tidak ada data laporan yang dapat dicetak untuk tahun ' . $tahun);
        }

        $filename = 'Laporan_Lengkap_BUMDES_' . $tahun . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    private function getPerubahanModalData($tahun)
    {
        // Load model yang dibutuhkan
        $labaRugiTahunModel = new LabaRugiTahunModel();
        $masterPerubahanModalModel = new MasterPerubahanModalModel();
        $detailPerubahanModalModel = new DetailPerubahanModalModel();

        // 1. Ambil Laba Rugi Bersih dari tabel ringkasan
        $labaRugiData = $labaRugiTahunModel->where('tahun', $tahun)->first();
        $labaRugiBersih = $labaRugiData['laba_rugi_bersih'] ?? 0;

        // 2. Ambil semua komponen master dan nilai yang sudah diinput
        $semuaKomponen = $masterPerubahanModalModel->findAll();
        $nilaiTersimpan = $detailPerubahanModalModel->where('tahun', $tahun)->findAll();
        $nilaiTersimpanMap = array_column($nilaiTersimpan, 'jumlah', 'master_perubahan_modal_id');

        // 3. Kelompokkan komponen dan hitung totalnya
        $komponenPenambahan = [];
        $komponenPengurangan = [];
        $totalPenambahanLain = 0;
        $totalPengurangan = 0;

        foreach ($semuaKomponen as $item) {
            $jumlah = $nilaiTersimpanMap[$item['id']] ?? 0;
            $item['jumlah'] = $jumlah;

            if ($item['kategori'] == 'penambahan') {
                $komponenPenambahan[] = $item;
                $totalPenambahanLain += $jumlah;
            } else if ($item['kategori'] == 'pengurangan') {
                $komponenPengurangan[] = $item;
                $totalPengurangan += $jumlah;
            }
        }

        // 4. Hitung modal akhir
        // Asumsi modal awal diambil dari tahun sebelumnya atau 0 jika tidak ada
        $laporanTahunLalu = $labaRugiTahunModel->where('tahun', $tahun - 1)->first();
        $modalAwal = $laporanTahunLalu['saldo_modal_akhir'] ?? 0;

        $totalPenambahan = $labaRugiBersih + $totalPenambahanLain;
        $modalAkhir = $modalAwal + $totalPenambahan - $totalPengurangan;

        return [
            'tahun' => $tahun,
            'laba_rugi_bersih' => $labaRugiBersih,
            'komponen_penambahan' => $komponenPenambahan,
            'komponen_pengurangan' => $komponenPengurangan,
            'modal_akhir' => $modalAkhir
        ];
    }

    private function _generatePerubahanModalSheet(Spreadsheet &$spreadsheet, $tahun)
    {
        $data = $this->getPerubahanModalData($tahun);
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Perubahan Modal ' . $tahun);

        // Judul
        $sheet->mergeCells('A1:C1')->setCellValue('A1', 'Laporan Perubahan Modal');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->mergeCells('A2:C2')->setCellValue('A2', 'Periode Tahun ' . $data['tahun']);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        // Header Tabel
        $sheet->setCellValue('A4', 'Keterangan')->setCellValue('B4', 'Jumlah (Rp)');
        $sheet->getStyle('A4:B4')->getFont()->setBold(true);

        $row = 5;

        // PENAMBAHAN
        $sheet->setCellValue('A' . $row, 'Penambahan:')->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue('A' . $row, '  Laba Bersih Tahun Berjalan')->setCellValue('B' . $row, $data['laba_rugi_bersih']);
        $row++;
        foreach ($data['komponen_penambahan'] as $item) {
            $sheet->setCellValue('A' . $row, '  ' . $item['nama_komponen'])->setCellValue('B' . $row, $item['jumlah']);
            $row++;
        }

        // PENGURANGAN
        $row++;
        $sheet->setCellValue('A' . $row, 'Pengurangan:')->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        foreach ($data['komponen_pengurangan'] as $item) {
            $sheet->setCellValue('A' . $row, '  ' . $item['nama_komponen'])->setCellValue('B' . $row, $item['jumlah']);
            $row++;
        }

        // MODAL AKHIR
        $row++;
        $sheet->setCellValue('A' . $row, 'Modal Akhir (per 31 Desember ' . $data['tahun'] . ')')->setCellValue('B' . $row, $data['modal_akhir']);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');

        // Styling
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getStyle('B4:B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A4:B' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }

    private function getArusKasData($tahun)
    {
        $bkuModel = new BkuBulananModel();
        $masterArusKasModel = new MasterArusKasModel();
        $detailArusKasModel = new DetailArusKasModel();
        $db = \Config\Database::connect();

        $pendapatanUtama = $bkuModel->selectSum('penghasilan_bulan_ini')->where('tahun', $tahun)->get()->getRow()->penghasilan_bulan_ini ?? 0;

        $builder = $db->table('detail_alokasi as da');
        $builder->select('mk.nama_kategori, SUM(da.jumlah_realisasi) as total_per_kategori');
        $builder->join('bku_bulanan as bb', 'bb.id = da.bku_id');
        $builder->join('master_kategori_pengeluaran as mk', 'mk.id = da.master_kategori_id');
        $builder->where('bb.tahun', $tahun);
        $builder->groupBy('mk.nama_kategori');
        $pengeluaranBKU = $builder->get()->getResultArray();
        $pengeluaranBKUMap = array_column($pengeluaranBKU, 'total_per_kategori', 'nama_kategori');

        $komponenMasuk = $masterArusKasModel->where('kategori', 'masuk')->findAll();
        $komponenKeluar = $masterArusKasModel->where('kategori', 'keluar')->findAll();

        $nilaiTersimpan = $detailArusKasModel->where('tahun', $tahun)->findAll();
        $nilaiTersimpanMap = array_column($nilaiTersimpan, 'jumlah', 'master_arus_kas_id');

        $totalKasMasukLainnya = 0;
        foreach ($komponenMasuk as &$item) {
            $item['jumlah'] = $nilaiTersimpanMap[$item['id']] ?? 0;
            $totalKasMasukLainnya += $item['jumlah'];
        }
        unset($item);

        $totalKasKeluarLainnya = 0;
        foreach ($komponenKeluar as &$item) {
            $item['jumlah'] = $nilaiTersimpanMap[$item['id']] ?? 0;
            $totalKasKeluarLainnya += $item['jumlah'];
        }
        unset($item);

        $totalKasMasuk = $pendapatanUtama + $totalKasMasukLainnya;
        $totalKasKeluar = ($pengeluaranBKUMap['PENGEMBANGAN'] ?? 0) + ($pengeluaranBKUMap['HONOR'] ?? 0) + ($pengeluaranBKUMap['PAD'] ?? 0) + $totalKasKeluarLainnya;

        $laporanBulanPertama = $bkuModel->where('tahun', $tahun)->orderBy('bulan', 'ASC')->first();
        $saldoAwalTahun = $laporanBulanPertama['saldo_bulan_lalu'] ?? 0;
        $saldoAkhir = $saldoAwalTahun + $totalKasMasuk - $totalKasKeluar;

        return [
            'pendapatanUtama' => $pendapatanUtama,
            'pembelianBarang' => $pengeluaranBKUMap['PENGEMBANGAN'] ?? 0,
            'bebanGaji' => $pengeluaranBKUMap['HONOR'] ?? 0,
            'pad' => $pengeluaranBKUMap['PAD'] ?? 0,
            'komponenMasuk' => $komponenMasuk,
            'komponenKeluar' => $komponenKeluar,
            'totalKasMasuk' => $totalKasMasuk,
            'totalKasKeluar' => $totalKasKeluar,
            'saldoAkhir' => $saldoAkhir,
        ];
    }

    private function _generateArusKasSheet(Spreadsheet &$spreadsheet, $tahun)
    {
        $data = $this->getArusKasData($tahun);
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Arus Kas ' . $tahun);

        $headerStyle = ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F4F4F']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]];
        $totalRowStyle = ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E9ECEF']]];
        $finalSaldoStyle = ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F4F4F']]];
        $allBorders = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]];

        $sheet->mergeCells('A1:C1')->setCellValue('A1', 'LAPORAN ARUS KAS');
        $sheet->mergeCells('A2:C2')->setCellValue('A2', 'PERIODE TAHUN ' . $tahun);
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 4;
        $startDataRow = $row;

        $sheet->mergeCells('A' . $row . ':C' . $row)->setCellValue('A' . $row, 'ARUS KAS MASUK')->getStyle('A' . $row . ':C' . $row)->applyFromArray($headerStyle);
        $row++;
        $sheet->setCellValue('A' . $row, 'Penerimaan Pendapatan Operasional Utama')->setCellValue('C' . $row, $data['pendapatanUtama']);
        $row++;

        foreach ($data['komponenMasuk'] as $item) {
            $sheet->setCellValue('A' . $row, $item['nama_komponen'])->setCellValue('C' . $row, $item['jumlah']);
            $row++;
        }
        $sheet->setCellValue('B' . $row, 'Total Arus Kas Masuk')->setCellValue('C' . $row, $data['totalKasMasuk'])->getStyle('A' . $row . ':C' . $row)->applyFromArray($totalRowStyle);
        $row += 2;

        $sheet->mergeCells('A' . $row . ':C' . $row)->setCellValue('A' . $row, 'ARUS KAS KELUAR')->getStyle('A' . $row . ':C' . $row)->applyFromArray($headerStyle);
        $row++;
        $sheet->setCellValue('A' . $row, 'Pembelian Barang dan Jasa')->setCellValue('C' . $row, $data['pembelianBarang']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Pembayaran Beban Gaji')->setCellValue('C' . $row, $data['bebanGaji']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Pendapatan Asli Desa')->setCellValue('C' . $row, $data['pad']);
        $row++;
        foreach ($data['komponenKeluar'] as $item) {
            $sheet->setCellValue('A' . $row, $item['nama_komponen'])->setCellValue('C' . $row, $item['jumlah']);
            $row++;
        }
        $sheet->setCellValue('B' . $row, 'Total Arus Kas Keluar')->setCellValue('C' . $row, $data['totalKasKeluar'])->getStyle('A' . $row . ':C' . $row)->applyFromArray($totalRowStyle);
        $row += 2;

        $sheet->setCellValue('B' . $row, 'SALDO AKHIR')->setCellValue('C' . $row, $data['saldoAkhir'])->getStyle('A' . $row . ':C' . $row)->applyFromArray($finalSaldoStyle);
        $lastDataRow = $row;

        $sheet->getStyle('C' . ($startDataRow + 1) . ':C' . $lastDataRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A' . $startDataRow . ':C' . $lastDataRow)->applyFromArray($allBorders);
        $sheet->getColumnDimension('A')->setWidth(45);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getStyle('C:C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    }

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

    private function getLaporanTahunanData($tahun)
    {
        $bkuModel = new BkuBulananModel();
        $db = \Config\Database::connect();
        $laporanBulanPertama = $bkuModel->where('tahun', $tahun)->orderBy('bulan', 'ASC')->first();
        $saldoAwalTahun = $laporanBulanPertama['saldo_bulan_lalu'] ?? 0;
        $totalPenghasilanSetahun = $bkuModel->selectSum('penghasilan_bulan_ini')->where('tahun', $tahun)->first()['penghasilan_bulan_ini'] ?? 0;
        $totalPendapatanTahunan = $saldoAwalTahun + $totalPenghasilanSetahun;
        $builder = $db->table('detail_pengeluaran as dp');
        $builder->select('mkp.nama_kategori, SUM(dp.jumlah) as total_per_kategori');
        $builder->join('bku_bulanan as bb', 'bb.id = dp.bku_id');
        $builder->join('master_kategori_pengeluaran as mkp', 'mkp.id = dp.master_kategori_id');
        $builder->where('bb.tahun', $tahun);
        $builder->groupBy('dp.master_kategori_id');
        $pengeluaranPerKategori = $builder->get()->getResultArray();
        $totalPengeluaran = array_sum(array_column($pengeluaranPerKategori, 'total_per_kategori'));
        $saldoAkhirTahun = $totalPendapatanTahunan - $totalPengeluaran;
        return ['totalPendapatan' => $totalPendapatanTahunan, 'pengeluaranPerKategori' => $pengeluaranPerKategori, 'totalPengeluaran' => $totalPengeluaran, 'saldoAkhirTahun' => $saldoAkhirTahun];
    }

    private function _generateLabaRugiSheet(Spreadsheet &$spreadsheet, $tahun)
    {
        $data = $this->getLaporanLabaRugiData($tahun);

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Laba Rugi ' . $tahun);

        // Judul
        $sheet->mergeCells('A1:B1')->setCellValue('A1', 'LAPORAN LABA RUGI');
        $sheet->mergeCells('A2:B2')->setCellValue('A2', 'PERIODE TAHUN ' . $tahun);
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 4;
        // PENDAPATAN
        $sheet->mergeCells('A' . $row . ':B' . $row)->setCellValue('A' . $row, 'PENDAPATAN');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        $startDataRow = $row;
        $sheet->setCellValue('A' . $row, 'Pendapatan Usaha');
        $sheet->setCellValue('B' . $row, $data['pendapatanUsaha']);
        $row++;
        $totalPendapatanLain = 0;
        foreach ($data['komponenPendapatan'] as $item) {
            $sheet->setCellValue('A' . $row, $item['nama_komponen']);
            $sheet->setCellValue('B' . $row, $item['jumlah']);
            $totalPendapatanLain += $item['jumlah'];
            $row++;
        }
        $totalPendapatan = $data['pendapatanUsaha'] + $totalPendapatanLain;
        $totalPendapatanRow = $row;
        $sheet->setCellValue('A' . $row, 'TOTAL PENDAPATAN');
        $sheet->setCellValue('B' . $row, $totalPendapatan);
        $row += 2;

        // BIAYA
        $sheet->mergeCells('A' . $row . ':B' . $row)->setCellValue('A' . $row, 'BIAYA-BIAYA');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue('A' . $row, 'Biaya Bahan Baku');
        $sheet->setCellValue('B' . $row, $data['biayaBahanBaku']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Biaya Gaji');
        $sheet->setCellValue('B' . $row, $data['biayaGaji']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Pendapatan Asli Desa (PAD)');
        $sheet->setCellValue('B' . $row, $data['pad']);
        $row++;
        $totalBiayaLain = 0;
        foreach ($data['komponenBiaya'] as $item) {
            $sheet->setCellValue('A' . $row, $item['nama_komponen']);
            $sheet->setCellValue('B' . $row, $item['jumlah']);
            $totalBiayaLain += $item['jumlah'];
            $row++;
        }
        $totalBiaya = $data['biayaBahanBaku'] + $data['biayaGaji'] + $data['pad'] + $totalBiayaLain;
        $totalBiayaRow = $row;
        $sheet->setCellValue('A' . $row, 'TOTAL BIAYA');
        $sheet->setCellValue('B' . $row, $totalBiaya);
        $row++;

        // LABA / RUGI
        $labaRugi = $totalPendapatan - $totalBiaya;
        $labaRugiRow = $row;
        $sheet->setCellValue('A' . $row, 'LABA / (RUGI) BERSIH');
        $sheet->setCellValue('B' . $row, $labaRugi);
        $lastDataRow = $row;

        // --- STYLING ---
        $sheet->getStyle('A' . $totalPendapatanRow . ':B' . $totalPendapatanRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $totalBiayaRow . ':B' . $totalBiayaRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $labaRugiRow . ':B' . $labaRugiRow)->getFont()->setBold(true);
        $sheet->getStyle('B' . $startDataRow . ':B' . $lastDataRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $styleArray = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]];
        $sheet->getStyle('A4:B' . $lastDataRow)->applyFromArray($styleArray);
        $totalFill = ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE9ECEF']]];
        $sheet->getStyle('A' . $totalPendapatanRow . ':B' . $totalPendapatanRow)->applyFromArray($totalFill);
        $sheet->getStyle('A' . $totalBiayaRow . ':B' . $totalBiayaRow)->applyFromArray($totalFill);
        $labaRugiFill = ($labaRugi >= 0)
            ? ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'D4EDDA']]]
            : ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F8D7DA']]];
        $sheet->getStyle('A' . $labaRugiRow . ':B' . $labaRugiRow)->applyFromArray($labaRugiFill);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(25);
    }

    private function getLaporanLabaRugiData($tahun)
    {
        $bkuModel = new BkuBulananModel();
        $masterLabaRugiModel = new MasterLabaRugiModel();
        $detailLabaRugiModel = new DetailLabaRugiModel();
        $db = \Config\Database::connect();

        $totalPenghasilanSetahun = $bkuModel->selectSum('penghasilan_bulan_ini')->where('tahun', $tahun)->get()->getRow()->penghasilan_bulan_ini ?? 0;

        $builder = $db->table('detail_alokasi as da');
        $builder->select('mk.nama_kategori, SUM(da.jumlah_realisasi) as total_per_kategori');
        $builder->join('bku_bulanan as bb', 'bb.id = da.bku_id');
        $builder->join('master_kategori_pengeluaran as mk', 'mk.id = da.master_kategori_id');
        $builder->where('bb.tahun', $tahun);
        $builder->groupBy('mk.nama_kategori');
        $pengeluaranBKU = $builder->get()->getResultArray();
        $pengeluaranBKUMap = array_column($pengeluaranBKU, 'total_per_kategori', 'nama_kategori');

        $komponenPendapatan = $masterLabaRugiModel->where('kategori', 'pendapatan')->findAll();
        $komponenBiaya = $masterLabaRugiModel->where('kategori', 'biaya')->findAll();

        $nilaiTersimpan = $detailLabaRugiModel->where('tahun', $tahun)->findAll();
        $nilaiTersimpanMap = array_column($nilaiTersimpan, 'jumlah', 'master_laba_rugi_id');

        foreach ($komponenPendapatan as &$item) {
            $item['jumlah'] = $nilaiTersimpanMap[$item['id']] ?? 0;
        }
        unset($item); // Best practice
        foreach ($komponenBiaya as &$item) {
            $item['jumlah'] = $nilaiTersimpanMap[$item['id']] ?? 0;
        }
        unset($item); // Best practice

        return [
            'pendapatanUsaha' => $totalPenghasilanSetahun,
            'biayaBahanBaku' => $pengeluaranBKUMap['PENGEMBANGAN'] ?? 0,
            'biayaGaji' => $pengeluaranBKUMap['HONOR'] ?? 0,
            'pad' => $pengeluaranBKUMap['PAD'] ?? 0,
            'komponenPendapatan' => $komponenPendapatan,
            'komponenBiaya' => $komponenBiaya,
        ];
    }

    private function _generateBkuBulananSheet(Spreadsheet &$spreadsheet, $id)
    {
        $konfigurasiHierarki = ['OPERASIONAL PENGELOLAAN' => ['KESEKRETARIATAN', 'PROMOSI']];
        $bkuModel = new BkuBulananModel();
        $detailPendapatanModel = new DetailPendapatanModel();
        $detailPengeluaranModel = new DetailPengeluaranModel();
        $masterKategoriModel = new MasterKategoriPengeluaranModel();
        $detailAlokasiModel = new DetailAlokasiModel();
        $pengaturanModel = new PengaturanModel();
        $laporan = $bkuModel->find($id);
        if (!$laporan) {
            return;
        }
        $rincianPendapatan = $detailPendapatanModel->select('detail_pendapatan.*, master_pendapatan.nama_pendapatan')->join('master_pendapatan', 'master_pendapatan.id = detail_pendapatan.master_pendapatan_id')->where('detail_pendapatan.bku_id', $id)->findAll();
        $rincianPengeluaran = $detailPengeluaranModel->select('detail_pengeluaran.*, master_kategori_pengeluaran.nama_kategori')->join('master_kategori_pengeluaran', 'master_kategori_pengeluaran.id = detail_pengeluaran.master_kategori_id')->where('detail_pengeluaran.bku_id', $id)->findAll();
        $rincianAlokasi = $detailAlokasiModel->select('detail_alokasi.*, master_kategori_pengeluaran.nama_kategori')->join('master_kategori_pengeluaran', 'master_kategori_pengeluaran.id = detail_alokasi.master_kategori_id')->where('detail_alokasi.bku_id', $id)->findAll();
        $masterKategori = $masterKategoriModel->findAll();
        $ketua = $pengaturanModel->where('meta_key', 'ketua_bumdes')->first()['meta_value'] ?? 'NAMA KETUA';
        $bendahara = $pengaturanModel->where('meta_key', 'bendahara_bumdes')->first()['meta_value'] ?? 'NAMA BENDAHARA';
        $lokasi = $pengaturanModel->where('meta_key', 'lokasi_laporan')->first()['meta_value'] ?? 'LOKASI';
        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $namaBulan = $bulanIndonesia[(int)$laporan['bulan']];

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BKU ' . $namaBulan);

        $kategoriHierarki = [];
        $kategoriColumnMap = [];
        $kategoriSudahDiProses = [];
        $kategoriByName = array_column($masterKategori, null, 'nama_kategori');

        foreach ($masterKategori as $kat) {
            $namaKategori = $kat['nama_kategori'];
            if (in_array($namaKategori, $kategoriSudahDiProses)) continue;
            if (isset($konfigurasiHierarki[$namaKategori])) {
                $childrenData = [];
                foreach ($konfigurasiHierarki[$namaKategori] as $namaAnak) {
                    if (isset($kategoriByName[$namaAnak])) {
                        $childrenData[] = $kategoriByName[$namaAnak];
                        $kategoriSudahDiProses[] = $namaAnak;
                    }
                }
                $kat['children'] = $childrenData;
                $kategoriHierarki[] = $kat;
            } else {
                $kat['children'] = [];
                $kategoriHierarki[] = $kat;
            }
        }
        $totalKolomPengeluaran = 0;
        foreach ($kategoriHierarki as $kat) {
            $childCount = count($kat['children']);
            $totalKolomPengeluaran += ($childCount > 0) ? $childCount : 1;
        }
        $startColPengeluaran = 5;
        $endColPengeluaran = ($totalKolomPengeluaran > 0) ? $startColPengeluaran + $totalKolomPengeluaran - 1 : $startColPengeluaran;
        $komulatifCol = $endColPengeluaran + 1;
        $saldoCol = $endColPengeluaran + 2;
        $endColTotal = $saldoCol;
        $endColTotalStr = Coordinate::stringFromColumnIndex($endColTotal);

        $sheet->mergeCells('A1:' . $endColTotalStr . '1')->setCellValue('A1', 'BUKU KAS UMUM BADAN USAHA MILIK DESA');
        $sheet->mergeCells('A2:' . $endColTotalStr . '2')->setCellValue('A2', '*BUMDES ALAM LESTARI*');
        $sheet->mergeCells('A3:' . $endColTotalStr . '3')->setCellValue('A3', 'DESA MELUNG KECAMATAN KEDUNGBANTENG');
        $sheet->mergeCells('A4:' . $endColTotalStr . '4')->setCellValue('A4', 'KABUPATEN BANYUMAS');
        $sheet->mergeCells('A5:' . $endColTotalStr . '5')->setCellValue('A5', 'PERIODE: ' . strtoupper($namaBulan . ' ' . $laporan['tahun']));

        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('A1:A5')->getFont()->setBold(true);
        $sheet->getStyle('A1:A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headerRow = 8;
        $subHeaderRow1 = 9;
        $subHeaderRow2 = 10;
        if ($totalKolomPengeluaran > 0) {
            $sheet->mergeCells(Coordinate::stringFromColumnIndex($startColPengeluaran) . $headerRow . ':' . Coordinate::stringFromColumnIndex($endColPengeluaran) . $headerRow)->setCellValue(Coordinate::stringFromColumnIndex($startColPengeluaran) . $headerRow, 'PENGELUARAN');
        }
        $currentColIndex = $startColPengeluaran;
        foreach ($kategoriHierarki as $parentKat) {
            $colStr = Coordinate::stringFromColumnIndex($currentColIndex);
            if (empty($parentKat['children'])) {
                $sheet->mergeCells($colStr . $subHeaderRow1 . ':' . $colStr . $subHeaderRow2)->setCellValue($colStr . $subHeaderRow1, strtoupper($parentKat['nama_kategori']) . "\n" . $parentKat['persentase'] . '%');
                $kategoriColumnMap[$parentKat['id']] = $currentColIndex;
                $currentColIndex++;
            } else {
                $childCount = count($parentKat['children']);
                $endMergeColIndex = $currentColIndex + $childCount - 1;
                $sheet->mergeCells($colStr . $subHeaderRow1 . ':' . Coordinate::stringFromColumnIndex($endMergeColIndex) . $subHeaderRow1)->setCellValue($colStr . $subHeaderRow1, strtoupper($parentKat['nama_kategori']) . "\n" . $parentKat['persentase'] . '%');
                foreach ($parentKat['children'] as $childKat) {
                    $childColStr = Coordinate::stringFromColumnIndex($currentColIndex);
                    $sheet->setCellValue($childColStr . $subHeaderRow2, strtoupper($childKat['nama_kategori']) . "\n" . $childKat['persentase'] . '%');
                    $kategoriColumnMap[$childKat['id']] = $currentColIndex;
                    $currentColIndex++;
                }
            }
        }
        $komulatifColStr = Coordinate::stringFromColumnIndex($komulatifCol);
        $saldoColStr = Coordinate::stringFromColumnIndex($saldoCol);
        $sheet->mergeCells('A' . $headerRow . ':A' . $subHeaderRow2)->setCellValue('A' . $headerRow, 'NO');
        $sheet->mergeCells('B' . $headerRow . ':B' . $subHeaderRow2)->setCellValue('B' . $headerRow, 'TANGGAL');
        $sheet->mergeCells('C' . $headerRow . ':C' . $subHeaderRow2)->setCellValue('C' . $headerRow, 'URAIAN');
        $sheet->mergeCells('D' . $headerRow . ':D' . $subHeaderRow2)->setCellValue('D' . $headerRow, 'PENDAPATAN');
        $sheet->mergeCells($komulatifColStr . $headerRow . ':' . $komulatifColStr . $subHeaderRow2)->setCellValue($komulatifColStr . $headerRow, 'KOMULATIF PENGELUARAN');
        $sheet->mergeCells($saldoColStr . $headerRow . ':' . $saldoColStr . $subHeaderRow2)->setCellValue($saldoColStr . $headerRow, 'SALDO');

        $row = $subHeaderRow2 + 1;
        $nomor = 1;
        $saldo = 0;
        $komulatifPengeluaran = 0;
        $sheet->setCellValue('A' . $row, $nomor++)->setCellValue('C' . $row, 'Sisa Saldo Bulan Lalu')->setCellValue('D' . $row, $laporan['saldo_bulan_lalu']);
        $saldo += (float)$laporan['saldo_bulan_lalu'];
        $sheet->setCellValue($saldoColStr . $row, $saldo);
        $row++;
        foreach ($rincianPendapatan as $p) {
            $sheet->setCellValue('A' . $row, $nomor++)->setCellValue('B' . $row, date('d-m-Y', strtotime($p['created_at'])))->setCellValue('C' . $row, $p['nama_pendapatan'])->setCellValue('D' . $row, $p['jumlah']);
            $saldo += (float)$p['jumlah'];
            $sheet->setCellValue($saldoColStr . $row, $saldo);
            $row++;
        }
        $totalPendapatanRow = $row;
        $sheet->setCellValue('C' . $row, 'Total Pendapatan Bulan Ini')->setCellValue('D' . $row, $laporan['total_pendapatan']);
        foreach ($rincianAlokasi as $alokasi) {
            if (isset($kategoriColumnMap[$alokasi['master_kategori_id']])) {
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($kategoriColumnMap[$alokasi['master_kategori_id']]) . $row, $alokasi['jumlah_alokasi']);
            }
        }
        $row++;
        foreach ($rincianPengeluaran as $p) {
            $sheet->setCellValue('A' . $row, $nomor++)->setCellValue('B' . $row, date('d-m-Y', strtotime($p['created_at'])))->setCellValue('C' . $row, $p['deskripsi_pengeluaran']);
            if (isset($kategoriColumnMap[$p['master_kategori_id']])) {
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($kategoriColumnMap[$p['master_kategori_id']]) . $row, $p['jumlah']);
            }
            $komulatifPengeluaran += (float)$p['jumlah'];
            $saldo -= (float)$p['jumlah'];
            $sheet->setCellValue($komulatifColStr . $row, $komulatifPengeluaran)->setCellValue($saldoColStr . $row, $saldo);
            $row++;
        }
        $row++;
        $alokasiRow = $row;
        $sisaAlokasiRow = $row + 1;
        $jumlahPengeluaranRow = $row + 2;
        $sisaSaldoRow = $row + 3;
        $sheet->setCellValue('C' . $alokasiRow, 'Alokasi')->setCellValue('C' . $sisaAlokasiRow, 'Sisa Alokasi')->setCellValue('C' . $jumlahPengeluaranRow, 'Jumlah Pengeluaran Bulan Ini')->setCellValue('C' . $sisaSaldoRow, 'Sisa Saldo Bulan ini');
        foreach ($rincianAlokasi as $alokasi) {
            if (isset($kategoriColumnMap[$alokasi['master_kategori_id']])) {
                $colIndexStr = Coordinate::stringFromColumnIndex($kategoriColumnMap[$alokasi['master_kategori_id']]);
                $sheet->setCellValue($colIndexStr . $alokasiRow, $alokasi['jumlah_alokasi']);
                $sheet->setCellValue($colIndexStr . $sisaAlokasiRow, $alokasi['sisa_alokasi']);
            }
        }
        $sheet->setCellValue($komulatifColStr . $jumlahPengeluaranRow, $laporan['total_pengeluaran'])->setCellValue($saldoColStr . $sisaSaldoRow, $laporan['saldo_akhir']);
        $row = $sisaSaldoRow + 3;
        $sheet->mergeCells('A' . $row . ':D' . $row)->setCellValue('A' . $row, 'Mengetahui,');
        $sheet->mergeCells('A' . ($row + 1) . ':D' . ($row + 1))->setCellValue('A' . ($row + 1), 'Ketua BUMDES');
        $sheet->mergeCells('A' . ($row + 5) . ':D' . ($row + 5))->setCellValue('A' . ($row + 5), $ketua)->getStyle('A' . ($row + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle('A' . $row . ':D' . ($row + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $startColKananIndex = max(6, $endColPengeluaran - 1);
        $startColKananStr = Coordinate::stringFromColumnIndex($startColKananIndex);
        $sheet->mergeCells($startColKananStr . $row . ':' . $endColTotalStr . $row)->setCellValue($startColKananStr . $row, $lokasi . ', ' . date('d ') . $namaBulan . date(' Y'));
        $sheet->mergeCells($startColKananStr . ($row + 1) . ':' . $endColTotalStr . ($row + 1))->setCellValue($startColKananStr . ($row + 1), 'Bendahara BUMDES');
        $sheet->mergeCells($startColKananStr . ($row + 5) . ':' . $endColTotalStr . ($row + 5))->setCellValue($startColKananStr . ($row + 5), $bendahara)->getStyle($startColKananStr . ($row + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle($startColKananStr . $row . ':' . $endColTotalStr . ($row + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $styleArray = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]], 'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]];
        $sheet->getStyle('A' . $headerRow . ':' . $endColTotalStr . $sisaSaldoRow)->applyFromArray($styleArray);
        $sheet->getStyle('A' . $headerRow . ':' . $endColTotalStr . $subHeaderRow2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setWrapText(true);
        $sheet->getStyle('A' . $headerRow . ':' . $endColTotalStr . $subHeaderRow2)->getFont()->setBold(true);
        $sheet->getStyle('A' . $totalPendapatanRow . ':' . $endColTotalStr . $totalPendapatanRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $alokasiRow . ':' . $endColTotalStr . $sisaSaldoRow)->getFont()->setBold(true);
        $sheet->getStyle('D' . ($subHeaderRow2 + 1) . ':' . $endColTotalStr . $sisaSaldoRow)->getNumberFormat()->setFormatCode('#,##0');
        for ($i = 1; $i <= $endColTotal; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }
        $sheet->getRowDimension($headerRow)->setRowHeight(30);
        $sheet->getRowDimension($subHeaderRow1)->setRowHeight(30);
        $sheet->getRowDimension($subHeaderRow2)->setRowHeight(30);
    }

    private function _generateBkuTahunanSheet(Spreadsheet &$spreadsheet, $tahun)
    {
        $hasil = $this->getLaporanTahunanData($tahun);
        $pengaturanModel = new PengaturanModel();
        $masterKategoriModel = new MasterKategoriPengeluaranModel();
        $masterKategori = $masterKategoriModel->findAll();
        $pengaturan = $pengaturanModel->getAllAsArray();
        $ketua = $pengaturan['ketua_bumdes'] ?? 'NAMA KETUA';
        $bendahara = $pengaturan['bendahara_bumdes'] ?? 'NAMA BENDAHARA';
        $lokasi = $pengaturan['lokasi_laporan'] ?? 'LOKASI';
        $kepala_desa = $pengaturan['nama_kepala_desa'] ?? 'Nama Kepala Desa';
        $penasihat = $pengaturan['nama_penasihat'] ?? 'Nama Penasihat';
        $pengawas = $pengaturan['nama_pengawas'] ?? 'Nama Pengawas';
        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BKU Tahunan ' . $tahun);

        $konfigurasiHierarki = ['OPERASIONAL PENGELOLAAN' => ['KESEKRETARIATAN', 'PROMOSI']];
        $kategoriByName = array_column($masterKategori, null, 'nama_kategori');
        $semuaAnakKategori = [];
        foreach ($konfigurasiHierarki as $children) {
            $semuaAnakKategori = array_merge($semuaAnakKategori, $children);
        }
        $kategoriHierarki = [];
        foreach ($masterKategori as $kat) {
            $namaKategori = $kat['nama_kategori'];
            if (in_array($namaKategori, $semuaAnakKategori)) continue;
            if (isset($konfigurasiHierarki[$namaKategori])) {
                $childrenData = [];
                foreach ($konfigurasiHierarki[$namaKategori] as $namaAnak) {
                    if (isset($kategoriByName[$namaAnak])) $childrenData[] = $kategoriByName[$namaAnak];
                }
                $kat['children'] = $childrenData;
                $kategoriHierarki[] = $kat;
            } else {
                $kat['children'] = [];
                $kategoriHierarki[] = $kat;
            }
        }
        $totalKolomPengeluaran = 0;
        foreach ($kategoriHierarki as $kat) {
            $childCount = count($kat['children']);
            $totalKolomPengeluaran += ($childCount > 0) ? $childCount : 1;
        }
        $startColPengeluaran = 4;
        $endColPengeluaran = ($totalKolomPengeluaran > 0) ? $startColPengeluaran + $totalKolomPengeluaran - 1 : $startColPengeluaran - 1;
        $komulatifCol = $endColPengeluaran + 1;
        $saldoCol = $endColPengeluaran + 2;
        $endColTotal = $saldoCol;
        $endColTotalStr = Coordinate::stringFromColumnIndex($endColTotal);
        $sheet->mergeCells('A1:' . $endColTotalStr . '1')->setCellValue('A1', 'BUKU KAS UMUM BADAN USAHA MILIK DESA (TAHUNAN)');
        $sheet->mergeCells('A2:' . $endColTotalStr . '2')->setCellValue('A2', '*BUMDES ALAM LESTARI*');
        $sheet->mergeCells('A3:' . $endColTotalStr . '3')->setCellValue('A3', 'DESA MELUNG KECAMATAN KEDUNG BANTENG');
        $sheet->mergeCells('A4:' . $endColTotalStr . '4')->setCellValue('A4', 'KABUPATEN BANYUMAS');
        $sheet->mergeCells('A5:' . $endColTotalStr . '5')->setCellValue('A5', 'PERIODE TAHUN ' . $tahun);

        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('A1:A5')->getFont()->setBold(true);
        $sheet->getStyle('A1:A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headerRow = 8;
        $subHeaderRow1 = 9;
        $subHeaderRow2 = 10;
        if ($totalKolomPengeluaran > 0) {
            $sheet->mergeCells(Coordinate::stringFromColumnIndex($startColPengeluaran) . $headerRow . ':' . Coordinate::stringFromColumnIndex($endColPengeluaran) . $headerRow)->setCellValue(Coordinate::stringFromColumnIndex($startColPengeluaran) . $headerRow, 'PENGELUARAN');
        }
        $kategoriColumnMap = [];
        $currentColIndex = $startColPengeluaran;
        foreach ($kategoriHierarki as $parentKat) {
            $colStr = Coordinate::stringFromColumnIndex($currentColIndex);
            if (empty($parentKat['children'])) {
                $sheet->mergeCells($colStr . $subHeaderRow1 . ':' . $colStr . $subHeaderRow2)->setCellValue($colStr . $subHeaderRow1, strtoupper($parentKat['nama_kategori']) . "\n" . $parentKat['persentase'] . '%');
                $kategoriColumnMap[$parentKat['id']] = $currentColIndex;
                $currentColIndex++;
            } else {
                $childCount = count($parentKat['children']);
                $endMergeColIndex = $currentColIndex + $childCount - 1;
                $sheet->mergeCells($colStr . $subHeaderRow1 . ':' . Coordinate::stringFromColumnIndex($endMergeColIndex) . $subHeaderRow1)->setCellValue($colStr . $subHeaderRow1, strtoupper($parentKat['nama_kategori']) . "\n" . $parentKat['persentase'] . '%');
                foreach ($parentKat['children'] as $childKat) {
                    $childColStr = Coordinate::stringFromColumnIndex($currentColIndex);
                    $sheet->setCellValue($childColStr . $subHeaderRow2, strtoupper($childKat['nama_kategori']) . "\n" . $childKat['persentase'] . '%');
                    $kategoriColumnMap[$childKat['id']] = $currentColIndex;
                    $currentColIndex++;
                }
            }
        }
        $komulatifColStr = Coordinate::stringFromColumnIndex($komulatifCol);
        $saldoColStr = Coordinate::stringFromColumnIndex($saldoCol);
        $sheet->mergeCells('A' . $headerRow . ':A' . $subHeaderRow2)->setCellValue('A' . $headerRow, 'NO');
        $sheet->mergeCells('B' . $headerRow . ':B' . $subHeaderRow2)->setCellValue('B' . $headerRow, 'URAIAN');
        $sheet->mergeCells('C' . $headerRow . ':C' . $subHeaderRow2)->setCellValue('C' . $headerRow, 'PENDAPATAN');
        $sheet->mergeCells($komulatifColStr . $headerRow . ':' . $komulatifColStr . $subHeaderRow2)->setCellValue($komulatifColStr . $headerRow, 'KOMULATIF PENGELUARAN');
        $sheet->mergeCells($saldoColStr . $headerRow . ':' . $saldoColStr . $subHeaderRow2)->setCellValue($saldoColStr . $headerRow, 'SALDO');
        $row = $subHeaderRow2 + 1;
        $nomor = 1;
        $saldo = 0;
        $komulatifPengeluaran = 0;
        $sheet->setCellValue('A' . $row, $nomor++)->setCellValue('B' . $row, "Akumulasi Pendapatan Tahun " . $tahun)->setCellValue('C' . $row, $hasil['totalPendapatan']);
        $saldo = (float)$hasil['totalPendapatan'];
        $sheet->setCellValue($saldoColStr . $row, $saldo);
        $row++;
        $realisasiMap = array_column($hasil['pengeluaranPerKategori'], 'total_per_kategori', 'nama_kategori');
        $sheet->setCellValue('B' . $row, 'Total Pendapatan Tahun Ini')->setCellValue('C' . $row, $hasil['totalPendapatan']);
        foreach ($masterKategori as $kat) {
            if (isset($kategoriColumnMap[$kat['id']])) {
                $alokasi = $hasil['totalPendapatan'] * ($kat['persentase'] / 100);
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($kategoriColumnMap[$kat['id']]) . $row, $alokasi);
            }
        }
        $row++;
        foreach ($masterKategori as $kat) {
            $realisasi = $realisasiMap[$kat['nama_kategori']] ?? 0;
            if ($realisasi > 0) {
                $sheet->setCellValue('A' . $row, $nomor++)->setCellValue('B' . $row, "Akumulasi Pengeluaran: " . $kat['nama_kategori']);
                if (isset($kategoriColumnMap[$kat['id']])) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($kategoriColumnMap[$kat['id']]) . $row, $realisasi);
                }
                $komulatifPengeluaran += (float)$realisasi;
                $saldo -= (float)$realisasi;
                $sheet->setCellValue($komulatifColStr . $row, $komulatifPengeluaran)->setCellValue($saldoColStr . $row, $saldo);
                $row++;
            }
        }
        $row++;
        $alokasiRow = $row;
        $sisaAlokasiRow = $row + 1;
        $jumlahPengeluaranRow = $row + 2;
        $sisaSaldoRow = $row + 3;
        $sheet->setCellValue('B' . $alokasiRow, 'Alokasi')->getStyle('B' . $alokasiRow)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $sisaAlokasiRow, 'Sisa Alokasi')->getStyle('B' . $sisaAlokasiRow)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $jumlahPengeluaranRow, 'Jumlah Pengeluaran Tahun Ini')->getStyle('B' . $jumlahPengeluaranRow)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $sisaSaldoRow, 'Sisa Saldo Tahun ini')->getStyle('B' . $sisaSaldoRow)->getFont()->setBold(true);
        foreach ($masterKategori as $kat) {
            if (isset($kategoriColumnMap[$kat['id']])) {
                $colIndex = $kategoriColumnMap[$kat['id']];
                $alokasi = $hasil['totalPendapatan'] * ($kat['persentase'] / 100);
                $realisasi = $realisasiMap[$kat['nama_kategori']] ?? 0;
                $sisa = $alokasi - $realisasi;
                $colStr = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue($colStr . $alokasiRow, $alokasi)->getStyle($colStr . $alokasiRow)->getFont()->setBold(true);
                $sheet->setCellValue($colStr . $sisaAlokasiRow, $sisa)->getStyle($colStr . $sisaAlokasiRow)->getFont()->setBold(true);
            }
        }
        $sheet->setCellValue($komulatifColStr . $jumlahPengeluaranRow, $hasil['totalPengeluaran'])->getStyle($komulatifColStr . $jumlahPengeluaranRow)->getFont()->setBold(true);
        $sheet->setCellValue($saldoColStr . $sisaSaldoRow, $hasil['saldoAkhirTahun'])->getStyle($saldoColStr . $sisaSaldoRow)->getFont()->setBold(true);
        $lastDataRow = $sisaSaldoRow;
        $row = $lastDataRow + 2;
        $tanggalSekarang = date('j') . ' ' . $bulanIndonesia[date('n')] . ' ' . date('Y');
        $sheet->setCellValue('J' . $row, $lokasi . ', ' . $tanggalSekarang);
        $row++;
        $sheet->setCellValue('B' . $row, 'Mengetahui Kepala Desa Melung')->setCellValue('B' . ($row + 3), $kepala_desa);
        $sheet->setCellValue('D' . $row, 'Penasihat')->setCellValue('D' . ($row + 3), $penasihat);
        $sheet->setCellValue('F' . $row, 'Pengawas')->setCellValue('F' . ($row + 3), $pengawas);
        $sheet->setCellValue('H' . $row, 'Ketua BUM-Des')->setCellValue('H' . ($row + 3), $ketua);
        $sheet->setCellValue('J' . $row, 'Bendahara BUMDES')->setCellValue('J' . ($row + 3), $bendahara);
        $styleArray = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]];
        $sheet->getStyle('A' . $headerRow . ':' . $endColTotalStr . $lastDataRow)->applyFromArray($styleArray);
        $headerRange = 'A' . $headerRow . ':' . $endColTotalStr . $subHeaderRow2;
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        for ($col = 'A'; $col <= $endColTotalStr; $col++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getRowDimension($headerRow)->setRowHeight(30);
        $sheet->getRowDimension($subHeaderRow1)->setRowHeight(30);
        $sheet->getRowDimension($subHeaderRow2)->setRowHeight(30);
    }

    private function _generateNeracaSheet(Spreadsheet &$spreadsheet, $tahun)
    {
        $data = $this->getNeracaData($tahun);
        $komponen = $data['komponen'];
        $pengaturanModel = new PengaturanModel();
        $ketua = $pengaturanModel->where('meta_key', 'ketua_bumdes')->first()['meta_value'] ?? 'NAMA KETUA';
        $bendahara = $pengaturanModel->where('meta_key', 'bendahara_bumdes')->first()['meta_value'] ?? 'NAMA BENDAHARA';
        $lokasi = $pengaturanModel->where('meta_key', 'lokasi_laporan')->first()['meta_value'] ?? 'LOKASI';
        $bulanIndonesia = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Neraca Keuangan ' . $tahun);

        $sheet->mergeCells('A1:F1')->setCellValue('A1', 'NERACA KEUANGAN');
        $sheet->mergeCells('A2:F2')->setCellValue('A2', 'PERIODE TAHUN ' . $tahun);

        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A4:C4')->setCellValue('A4', 'AKTIVA');
        $sheet->mergeCells('D4:F4')->setCellValue('D4', 'PASIVA');

        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        $sheet->getStyle('A4:F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowAktiva = 5;
        $sheet->mergeCells('A' . $rowAktiva . ':C' . $rowAktiva)->setCellValue('A' . $rowAktiva, 'Aktiva Lancar');
        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('A' . $rowAktiva)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowAktiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowAktiva++;
        $totalAktivaLancar = 0;
        $nomor = 1;
        foreach ($komponen['aktiva_lancar'] as $item) {
            $sheet->setCellValue('A' . $rowAktiva, $nomor++)->setCellValue('B' . $rowAktiva, $item['nama_komponen'])->setCellValue('C' . $rowAktiva, $item['jumlah']);
            $totalAktivaLancar += $item['jumlah'];
            $rowAktiva++;
        }
        $sheet->setCellValue('B' . $rowAktiva, 'JUMLAH AKTIVA LANCAR')->getStyle('B' . $rowAktiva)->getFont()->setBold(true);
        $sheet->setCellValue('C' . $rowAktiva, $totalAktivaLancar)->getStyle('C' . $rowAktiva)->getFont()->setBold(true);
        $rowAktiva += 2;
        $sheet->mergeCells('A' . $rowAktiva . ':C' . $rowAktiva)->setCellValue('A' . $rowAktiva, 'Aktiva Tetap');
        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('A' . $rowAktiva)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowAktiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowAktiva++;
        $totalAktivaTetap = 0;
        $nomor = 1;
        foreach ($komponen['aktiva_tetap'] as $item) {
            $sheet->setCellValue('A' . $rowAktiva, $nomor++)->setCellValue('B' . $rowAktiva, $item['nama_komponen'])->setCellValue('C' . $rowAktiva, $item['jumlah']);
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
        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('D' . $rowPasiva)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowPasiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowPasiva++;
        $totalHutangLancar = 0;
        $nomor = 1;
        foreach ($komponen['hutang_lancar'] as $item) {
            $sheet->setCellValue('D' . $rowPasiva, $nomor++)->setCellValue('E' . $rowPasiva, $item['nama_komponen'])->setCellValue('F' . $rowPasiva, $item['jumlah']);
            $totalHutangLancar += $item['jumlah'];
            $rowPasiva++;
        }
        $sheet->setCellValue('E' . $rowPasiva, 'JUMLAH HUTANG LANCAR')->getStyle('E' . $rowPasiva)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $rowPasiva, $totalHutangLancar)->getStyle('F' . $rowPasiva)->getFont()->setBold(true);
        $rowPasiva += 2;
        $sheet->mergeCells('D' . $rowPasiva . ':F' . $rowPasiva)->setCellValue('D' . $rowPasiva, 'Hutang Jangka Panjang');
        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('D' . $rowPasiva)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowPasiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowPasiva++;
        $totalHutangJangkaPanjang = 0;
        $nomor = 1;
        foreach ($komponen['hutang_jangka_panjang'] as $item) {
            $sheet->setCellValue('D' . $rowPasiva, $nomor++)->setCellValue('E' . $rowPasiva, $item['nama_komponen'])->setCellValue('F' . $rowPasiva, $item['jumlah']);
            $totalHutangJangkaPanjang += $item['jumlah'];
            $rowPasiva++;
        }
        $sheet->setCellValue('E' . $rowPasiva, 'JUMLAH HUTANG JANGKA PANJANG')->getStyle('E' . $rowPasiva)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $rowPasiva, $totalHutangJangkaPanjang)->getStyle('F' . $rowPasiva)->getFont()->setBold(true);
        $rowPasiva += 2;
        $sheet->mergeCells('D' . $rowPasiva . ':F' . $rowPasiva)->setCellValue('D' . $rowPasiva, 'Modal');
        // PERBAIKAN: Memisahkan pengaturan font dan alignment
        $sheet->getStyle('D' . $rowPasiva)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowPasiva)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $rowPasiva++;
        $sheet->setCellValue('E' . $rowPasiva, 'Surplus/Defisit Ditahan')->setCellValue('F' . $rowPasiva, $data['surplusDefisitDitahan']);
        $totalModalDinamis = 0;
        $nomor = 1;
        foreach ($komponen['modal'] as $item) {
            $rowPasiva++;
            $sheet->setCellValue('D' . $rowPasiva, $nomor++)->setCellValue('E' . $rowPasiva, $item['nama_komponen'])->setCellValue('F' . $rowPasiva, $item['jumlah']);
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
        $sheet->mergeCells('A' . ($row + 5) . ':C' . ($row + 5))->setCellValue('A' . ($row + 5), $ketua)->getStyle('A' . ($row + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle('A' . $row . ':C' . ($row + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('D' . $row . ':F' . $row)->setCellValue('D' . $row, $lokasi . ', ' . date('d ') . $bulanIndonesia[(int)date('m')] . date(' Y'));
        $sheet->mergeCells('D' . ($row + 1) . ':F' . ($row + 1))->setCellValue('D' . ($row + 1), 'Bendahara BUMDES');
        $sheet->mergeCells('D' . ($row + 5) . ':F' . ($row + 5))->setCellValue('D' . ($row + 5), $bendahara)->getStyle('D' . ($row + 5))->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle('D' . $row . ':F' . ($row + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C5:C' . $rowAktiva)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F5:F' . $rowPasiva)->getNumberFormat()->setFormatCode('#,##0');
        $styleArray = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]];
        $sheet->getStyle('A4:C' . $rowAktiva)->applyFromArray($styleArray);
        $sheet->getStyle('D4:F' . $rowPasiva)->applyFromArray($styleArray);
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
