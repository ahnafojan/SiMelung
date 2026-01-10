<?php

namespace App\Controllers;

use App\Models\KopiMasukModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class BiayaKopi extends Controller
{
    protected $kopiMasukModel;

    public function __construct()
    {
        $this->kopiMasukModel = new KopiMasukModel();
        helper(['form', 'url', 'date']);
    }

    public function index()
    {
        if (!session()->get('user_id')) {
            session()->setFlashdata('error', 'Anda harus login untuk mengakses halaman ini.');
            return redirect()->to('/login');
        }

        // Ambil filter dari GET
        $tanggalAwal = $this->request->getGet('tanggal_awal');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');
        $perPage = (int)($this->request->getGet('per_page') ?? 10);
        $currentPage = (int)($this->request->getGet('page_biaya') ?? 1);

        // Validasi tanggal
        $validation = \Config\Services::validation();
        $validation->setRules([
            'tanggal_awal' => 'permit_empty|valid_date',
            'tanggal_akhir' => 'permit_empty|valid_date'
        ]);

        if (!empty($tanggalAwal) || !empty($tanggalAkhir)) {
            if (!$validation->run(['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir])) {
                $tanggalAwal = $tanggalAwal ?: date('Y-m-01');
                $tanggalAkhir = $tanggalAkhir ?: date('Y-m-d');
            }
        } else {
            $tanggalAwal = date('Y-m-01');
            $tanggalAkhir = date('Y-m-d');
        }

        // Pastikan tanggal_awal <= tanggal_akhir
        if ($tanggalAwal > $tanggalAkhir) {
            [$tanggalAwal, $tanggalAkhir] = [$tanggalAkhir, $tanggalAwal];
        }

        // --- Hitung Ringkasan ---
        $summary = $this->calculateSummary($tanggalAwal, $tanggalAkhir);
        $totalBiayaPembelian = $summary['total_biaya'];
        $jumlahTransaksi = $summary['jumlah_transaksi'];
        $totalKgDibeli = $summary['total_kg'];

        // --- Ambil Data Transaksi untuk Tabel ---
        $paginationData = $this->getTransaksiDetail($tanggalAwal, $tanggalAkhir, $perPage, $currentPage);
        $transaksi = $paginationData['data'];
        $pager = $paginationData['pager'];

        // --- Siapkan Data untuk Grafik ---
        $chartDataBiaya = $this->getChartData($tanggalAwal, $tanggalAkhir);

        // --- Kirim Data ke View ---
        $data = [
            'tanggalAwal'          => $tanggalAwal,
            'tanggalAkhir'         => $tanggalAkhir,
            'totalBiayaPembelian'  => $totalBiayaPembelian,
            'jumlahTransaksi'      => $jumlahTransaksi,
            'totalKgDibeli'        => $totalKgDibeli,
            'transaksi'            => $transaksi,
            'pager'                => $pager,
            'currentPage'          => $currentPage,
            'perPage'              => $perPage,
            'chartDataBiaya'       => $chartDataBiaya, // Data untuk Chart.js
        ];
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('/dashboard/dashboard_komersial'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Biaya Pembelian Kopi',
                'url'   => '#',
                'icon'  => 'fas fa-seedling'
            ]
        ];

        return view('admin_komersial/kopi/biayakopi', $data);
    }

    /**
     * Fungsi untuk menghitung ringkasan biaya pembelian.
     */
    private function calculateSummary($tanggalAwal, $tanggalAkhir)
    {
        $builder = $this->kopiMasukModel->db->table('kopi_masuk')
            ->select('jumlah, total_harga')
            ->where('tanggal >=', $tanggalAwal)
            ->where('tanggal <=', $tanggalAkhir);

        $results = $builder->get()->getResultArray();

        $totalBiaya = 0;
        $totalKg = 0;
        $jumlahTransaksi = count($results);

        foreach ($results as $item) {
            $totalBiaya += $item['total_harga'] ?? 0;
            $totalKg += $item['jumlah'] ?? 0;
        }

        return [
            'total_biaya'      => $totalBiaya,
            'jumlah_transaksi' => $jumlahTransaksi,
            'total_kg'         => $totalKg
        ];
    }

    /**
     * Fungsi untuk mengambil detail transaksi untuk tabel (dengan pagination)
     */
    private function getTransaksiDetail($tanggalAwal, $tanggalAkhir, $perPage, $currentPage)
    {
        // Gunakan query builder untuk join dan ambil data yang diperlukan
        $builder = $this->kopiMasukModel->db->table('kopi_masuk')
            ->select('
                kopi_masuk.id,
                kopi_masuk.petani_user_id,
                kopi_masuk.jumlah,
                kopi_masuk.tanggal,
                kopi_masuk.keterangan,
                kopi_masuk.harga_saat_transaksi,
                kopi_masuk.total_harga,
                petani.nama as nama_petani,
                jenis_pohon.nama_jenis as nama_jenis_pohon
            ')
            ->join('petani', 'kopi_masuk.petani_user_id = petani.user_id', 'left')
            ->join('petani_pohon', 'kopi_masuk.petani_pohon_id = petani_pohon.id', 'left')
            ->join('jenis_pohon', 'petani_pohon.jenis_pohon_id = jenis_pohon.id', 'left')
            ->where('kopi_masuk.tanggal >=', $tanggalAwal)
            ->where('kopi_masuk.tanggal <=', $tanggalAkhir)
            ->orderBy('kopi_masuk.tanggal', 'DESC')
            ->orderBy('kopi_masuk.id', 'DESC');

        // Hitung total items
        $totalItems = $builder->countAllResults(false);

        // Hitung offset
        $offset = ($perPage * ($currentPage - 1));

        // Ambil data dengan limit dan offset
        $pagedData = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Buat pager instance
        $pager = \Config\Services::pager();

        // Siapkan URI dengan query string untuk filter
        $uri = current_url() . '?tanggal_awal=' . urlencode($tanggalAwal) . '&tanggal_akhir=' . urlencode($tanggalAkhir) . '&per_page=' . $perPage;

        // Setup pager dengan cara yang benar untuk CI4
        $pagerConfig = [
            'total'       => $totalItems,
            'perPage'     => $perPage,
            'currentPage' => $currentPage,
            'segment'     => 0,
            'baseURL'     => $uri,
        ];

        // Make pager dengan group 'biaya'
        $pager->makeLinks($currentPage, $perPage, $totalItems, 'custom_pagination_template', 0, 'biaya');

        return [
            'data'  => $pagedData,
            'pager' => $pager
        ];
    }

    /**
     * Fungsi untuk mengambil data harian untuk grafik biaya
     */
    private function getChartData($tanggalAwal, $tanggalAkhir)
    {
        // Generate array tanggal antara awal dan akhir menggunakan PHP native
        $dates = [];
        $currentDate = strtotime($tanggalAwal);
        $endDate = strtotime($tanggalAkhir);

        while ($currentDate <= $endDate) {
            $dates[] = date('Y-m-d', $currentDate);
            $currentDate = strtotime('+1 day', $currentDate);
        }

        // Inisialisasi data grafik
        $biayaPerHari = array_fill_keys($dates, 0);

        // Ambil transaksi harian
        $transaksi = $this->kopiMasukModel
            ->select('tanggal, total_harga')
            ->where('tanggal >=', $tanggalAwal)
            ->where('tanggal <=', $tanggalAkhir)
            ->orderBy('tanggal', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        foreach ($transaksi as $masuk) {
            $tanggal = $masuk['tanggal'];
            if (isset($biayaPerHari[$tanggal])) {
                $biayaPerHari[$tanggal] += $masuk['total_harga'] ?? 0;
            }
        }

        // Format label tanggal untuk ditampilkan (opsional, bisa diformat lebih user-friendly)
        $labels = [];
        foreach (array_keys($biayaPerHari) as $date) {
            $labels[] = date('d/m', strtotime($date)); // Format: dd/mm
        }

        // Format untuk Chart.js
        return [
            'labels' => $labels,
            'biaya' => array_values($biayaPerHari),
        ];
    }
}
