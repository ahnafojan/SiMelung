<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetKomersialModel;
use App\Models\PetaniModel;
use App\Models\KopiMasukModel;
use App\Models\KopiKeluarModel;
use App\Models\StokKopiModel;
use App\Models\JenisPohonModel;
use App\Models\PetaniPohonModel;


class LaporanKomersial extends BaseController
{
    protected $petaniModel;
    protected $kopiMasukModel;
    protected $kopiKeluarModel;
    protected $stokKopiModel;
    protected $jenisPohonModel;
    protected $petaniPohonModel;
    protected $asetModel;
    protected $pager;


    public function __construct()
    {
        $this->petaniModel = new PetaniModel();
        $this->kopiMasukModel = new KopiMasukModel();
        $this->kopiKeluarModel = new KopiKeluarModel();
        $this->stokKopiModel = new StokKopiModel();
        $this->jenisPohonModel = new JenisPohonModel();
        $this->petaniPohonModel = new PetaniPohonModel();
        $this->asetModel = new AsetKomersialModel();
        $this->pager = \Config\Services::pager();
    }

    public function index()
    {
        // 1. Ambil filter dari GET
        $filter = [
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date'   => $this->request->getGet('end_date') ?? '',
            'petani'     => $this->request->getGet('petani') ?? ''
        ];
        //Pager
        //petani
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;

        // Pager baru untuk Kopi Masuk
        $pageMasuk = $this->request->getGet('page_masuk') ?? 1;
        $perPageMasuk = $this->request->getGet('per_page_masuk') ?? 10;

        // Pager baru untuk Kopi Keluar
        $pageKeluar = $this->request->getGet('page_keluar') ?? 1;
        $perPageKeluar = $this->request->getGet('per_page_keluar') ?? 10;

        // Pager baru untuk Stok Akhir
        $pageStok = $this->request->getGet('page_stok') ?? 1;
        $perPageStok = $this->request->getGet('per_page_stok') ?? 10;

        // 2. Ambil daftar petani untuk dropdown
        $petaniList = $this->petaniModel->select('user_id, nama as nama_petani')->findAll();

        // ======================================================
        // BAGIAN 2: DATA UNTUK LAPORAN ASET (DIPERBAIKI)
        // ======================================================
        $filterTahunAset = $this->request->getGet('tahun_aset') ?? 'semua';
        $perPageAset = $this->request->getGet('per_page_aset') ?? 10;

        // PERBAIKAN DIMULAI DI SINI
        $asetModelBuilder = $this->asetModel; // Mulai dengan instance model

        // Terapkan filter tahun jika ada
        if ($filterTahunAset && $filterTahunAset != 'semua') {
            $asetModelBuilder->where('tahun_perolehan', $filterTahunAset);
        }

        // Panggil paginate() pada model yang sudah difilter
        $dataAset = $asetModelBuilder->orderBy('tahun_perolehan', 'DESC')->paginate($perPageAset, 'aset');
        // SELESAI PERBAIKAN

        $pagerAset = $this->asetModel->pager;
        $daftarTahunAset = $this->asetModel->getTahunPerolehan();

        // 3. Ambil data untuk setiap tabel laporan
        list($rekapPetani, $pagerKopiMasuk) = $this->getRekapKopiMasuk($filter, $perPageMasuk, $pageMasuk);
        list($rekapPenjualan, $pagerKopiKeluar) = $this->getRekapKopiKeluar($filter, $perPageKeluar, $pageKeluar);
        list($stokAkhirPerJenis, $pagerStokAkhir) = $this->getStokAkhir($filter, $perPageStok, $pageStok);

        // --- Panggilan yang BENAR ke _getRekapPetaniTerdaftar dengan paginasi ---
        list($rekapPetaniTerdaftar, $petaniPager) = $this->_getRekapPetaniTerdaftar($filter, $perPage, $page);
        // --- Akhir perubahan ---

        // 4. Hitung total global untuk footer
        $allStokData = $this->getStokAkhir($filter, 0, 1, false);
        $totalStokGlobal = array_sum(array_column($stokAkhirPerJenis, 'stok_akhir'));
        $totalAset = $this->asetModel->countAll();

        // 5. Kirim data ke view
        return view('admin_komersial/laporan/index', [
            'petaniList'           => $petaniList,
            'filter'               => $filter,
            'rekapPetani'          => $rekapPetani,
            'rekapPenjualan'       => $rekapPenjualan,
            'stokAkhirPerJenis'    => $stokAkhirPerJenis,
            'totalStokGlobal'      => $totalStokGlobal,
            'totalAset'            => $totalAset,
            'rekapPetaniTerdaftar' => $rekapPetaniTerdaftar,
            'petaniPager'          => $petaniPager, // Kirim objek pager ke view
            'perPage'              => $perPage,
            'pagerKopiMasuk'       => $pagerKopiMasuk,
            'perPageMasuk'         => $perPageMasuk,
            'pagerKopiKeluar'      => $pagerKopiKeluar,
            'perPageKeluar'        => $perPageKeluar,
            'pagerStokAkhir'       => $pagerStokAkhir,
            'perPageStok'          => $perPageStok,
            'aset'                  => $dataAset,
            'pagerAset'             => $pagerAset,
            'daftarTahun'           => $daftarTahunAset,
            'filterTahun'           => $filterTahunAset,
            'perPageAset'           => $perPageAset,
        ]);
    }

    /**
     * Mengambil dan mengolah data petani terdaftar beserta jenis kopi yang mereka miliki dengan paginasi.
     * @param array $filter Filter tanggal dan petani (jika berlaku)
     * @param int $perPage Jumlah item per halaman
     * @param int $page Halaman saat ini
     * @return array [data petani yang sudah diolah, objek pager]
     */
    protected function _getRekapPetaniTerdaftar($filter, $perPage, $page)
    {
        $farmersRawDataQuery = $this->petaniModel
            ->select('petani.id, petani.nama, petani.alamat, petani.no_hp, jenis_pohon.nama_jenis')
            ->join('petani_pohon', 'petani_pohon.user_id = petani.user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        if (!empty($filter['petani'])) {
            $farmersRawDataQuery->where('petani.user_id', $filter['petani']);
        }

        $allFarmersRawData = $farmersRawDataQuery->findAll();

        $processedFarmers = [];
        foreach ($allFarmersRawData as $row) {
            $farmerId = $row['id'];
            if (!isset($processedFarmers[$farmerId])) {
                $processedFarmers[$farmerId] = [
                    'id' => $row['id'],
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'],
                    'no_hp' => $row['no_hp'],
                    'jenis_kopi' => [],
                ];
            }
            if ($row['nama_jenis'] !== null) {
                $processedFarmers[$farmerId]['jenis_kopi'][] = $row['nama_jenis'];
            }
        }

        foreach ($processedFarmers as &$farmer) {
            $farmer['jenis_kopi'] = !empty($farmer['jenis_kopi']) ? implode(', ', array_unique($farmer['jenis_kopi'])) : 'Tidak ada jenis kopi';
        }
        $processedFarmers = array_values($processedFarmers);

        $total = count($processedFarmers);
        $offset = ($page - 1) * $perPage;
        $paginatedFarmers = array_slice($processedFarmers, $offset, $perPage);

        // Pastikan variabel pager tersedia sebelum memanggil makeLinks
        if ($this->pager) {
            // Perubahan di sini: Menggunakan 'default_full' sebagai nama template Pager
            $this->pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'petani');
        } else {
            // Handle jika pager tidak terinisialisasi (seharusnya tidak terjadi dengan construct)
            log_message('error', 'Pager object not initialized in _getRekapPetaniTerdaftar.');
        }


        return [$paginatedFarmers, $this->pager];
    }
    protected function _getAllRekapPetaniTerdaftar($filter)
    {
        $farmersRawDataQuery = $this->petaniModel
            ->select('petani.id, petani.nama, petani.alamat, petani.no_hp, jenis_pohon.nama_jenis')
            ->join('petani_pohon', 'petani_pohon.user_id = petani.user_id', 'left')
            ->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        if (!empty($filter['petani'])) {
            $farmersRawDataQuery->where('petani.user_id', $filter['petani']);
        }

        $allFarmersRawData = $farmersRawDataQuery->findAll();

        $processedFarmers = [];
        foreach ($allFarmersRawData as $row) {
            $farmerId = $row['id'];
            if (!isset($processedFarmers[$farmerId])) {
                $processedFarmers[$farmerId] = [
                    'id' => $row['id'],
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'],
                    'no_hp' => $row['no_hp'],
                    'jenis_kopi' => [],
                ];
            }
            if ($row['nama_jenis'] !== null) {
                $processedFarmers[$farmerId]['jenis_kopi'][] = $row['nama_jenis'];
            }
        }

        foreach ($processedFarmers as &$farmer) {
            $farmer['jenis_kopi'] = !empty($farmer['jenis_kopi']) ? implode(', ', array_unique($farmer['jenis_kopi'])) : 'Tidak ada jenis kopi';
        }
        return array_values($processedFarmers); // Reset keys dan kembalikan semua data
    }


    // Fungsi untuk mendapatkan data Rekap Kopi Masuk per Petani
    public function getRekapKopiMasuk($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('kopi_masuk');
        $builder->select('
            petani.nama as nama_petani,
            SUM(kopi_masuk.jumlah) AS total_masuk,
            MAX(kopi_masuk.tanggal) AS tanggal_terakhir,
            COUNT(kopi_masuk.id) AS jumlah_transaksi
        ');
        $builder->join('petani', 'petani.user_id = kopi_masuk.petani_user_id', 'left');

        if (!empty($filter['start_date'])) $builder->where('kopi_masuk.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date'])) $builder->where('kopi_masuk.tanggal <=', $filter['end_date']);
        if (!empty($filter['petani'])) $builder->where('kopi_masuk.petani_user_id', $filter['petani']);
        $builder->groupBy('petani.nama');

        // Logika Paginasi
        if ($paginate) {
            $totalBuilder = clone $builder;
            $total = $totalBuilder->countAllResults(false); // countAllResults() salah jika ada groupBy, jadi kita hitung manual
            if (is_array($total)) $total = count($total);

            $builder->limit($perPage, ($page - 1) * $perPage);
        }

        $data = $builder->get()->getResultArray();

        foreach ($data as &$row) {
            $row['rata_rata_setoran'] = $row['jumlah_transaksi'] > 0 ? $row['total_masuk'] / $row['jumlah_transaksi'] : 0;
        }

        if ($paginate) {
            $pager = service('pager');
            $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'masuk');
            return [$data, $pager];
        }

        return $data; // Kembalikan hanya data jika tidak paginasi
    }

    // Fungsi untuk mendapatkan data Rekap Kopi Keluar (Penjualan)
    public function getRekapKopiKeluar($filter, $perPage = null, $page = null, $paginate = true)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('kopi_keluar');
        $builder->select('
            kopi_keluar.tanggal,
            kopi_keluar.jumlah as jumlah_kg,
            kopi_keluar.tujuan as tujuan_pembeli,
            kopi_keluar.keterangan,
            jenis_pohon.nama_jenis as jenis_kopi
        ');
        $builder->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builder->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');
        $builder->orderBy('kopi_keluar.tanggal', 'DESC');

        if (!empty($filter['start_date'])) $builder->where('kopi_keluar.tanggal >=', $filter['start_date']);
        if (!empty($filter['end_date'])) $builder->where('kopi_keluar.tanggal <=', $filter['end_date']);

        if ($paginate) {
            $total = $builder->countAllResults(false);
            $builder->limit($perPage, ($page - 1) * $perPage);
        }

        $data = $builder->get()->getResultArray();

        if ($paginate) {
            $pager = service('pager');
            $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'keluar');
            return [$data, $pager];
        }

        return $data;
    }

    // Fungsi untuk mendapatkan data Stok Akhir per Jenis Kopi
    public function getStokAkhir($filter, $perPage = null, $page = null, $paginate = true)
    {
        // --- 1. Ambil Total Kopi Masuk per Jenis Kopi ---
        $db = \Config\Database::connect();
        $builderMasuk = $db->table('kopi_masuk');
        $builderMasuk->select('jenis_pohon.nama_jenis, SUM(kopi_masuk.jumlah) AS total_masuk');
        $builderMasuk->join('petani_pohon', 'petani_pohon.id = kopi_masuk.petani_pohon_id', 'left');
        $builderMasuk->join('jenis_pohon', 'jenis_pohon.id = petani_pohon.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) {
            $builderMasuk->where('kopi_masuk.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builderMasuk->where('kopi_masuk.tanggal <=', $filter['end_date']);
        }

        // Pastikan hanya jenis pohon yang valid yang dihitung
        $builderMasuk->where('jenis_pohon.nama_jenis IS NOT NULL');
        $builderMasuk->groupBy('jenis_pohon.nama_jenis');

        $dataMasuk = $builderMasuk->get()->getResultArray();
        // Ubah menjadi array asosiatif [nama_jenis => total_masuk] untuk pencarian cepat
        $totalMasuk = array_column($dataMasuk, 'total_masuk', 'nama_jenis');

        // --- 2. Ambil Total Kopi Keluar per Jenis Kopi ---
        $builderKeluar = $db->table('kopi_keluar');
        $builderKeluar->select('jenis_pohon.nama_jenis, SUM(kopi_keluar.jumlah) AS total_keluar');
        $builderKeluar->join('stok_kopi', 'stok_kopi.id = kopi_keluar.stok_kopi_id', 'left');
        $builderKeluar->join('jenis_pohon', 'jenis_pohon.id = stok_kopi.jenis_pohon_id', 'left');

        if (!empty($filter['start_date'])) {
            $builderKeluar->where('kopi_keluar.tanggal >=', $filter['start_date']);
        }
        if (!empty($filter['end_date'])) {
            $builderKeluar->where('kopi_keluar.tanggal <=', $filter['end_date']);
        }

        // Pastikan hanya jenis pohon yang valid yang dihitung
        $builderKeluar->where('jenis_pohon.nama_jenis IS NOT NULL');
        $builderKeluar->groupBy('jenis_pohon.nama_jenis');

        $dataKeluar = $builderKeluar->get()->getResultArray();
        // Ubah menjadi array asosiatif [nama_jenis => total_keluar]
        $totalKeluar = array_column($dataKeluar, 'total_keluar', 'nama_jenis');

        // --- 3. Kalkulasi Stok Akhir ---
        $stokAkhir = [];
        // Ambil semua jenis kopi yang terdaftar agar stok 0 tetap muncul
        $jenisKopiList = $this->jenisPohonModel->select('nama_jenis')->findAll();

        foreach ($jenisKopiList as $jenis) {
            $namaJenis = $jenis['nama_jenis'];
            // Gunakan null coalescing operator (??) untuk menangani jika tidak ada transaksi
            $masuk = $totalMasuk[$namaJenis] ?? 0;
            $keluar = $totalKeluar[$namaJenis] ?? 0;

            $stokAkhir[] = [
                'jenis_kopi' => $namaJenis,
                'stok_akhir' => $masuk - $keluar
            ];
        }

        // --- 4. Logika Paginasi ---

        // Jika paginasi tidak aktif (untuk export), langsung kembalikan semua data stok akhir
        if (!$paginate) {
            return $stokAkhir;
        }

        // Lakukan paginasi pada array hasil olahan PHP
        $total = count($stokAkhir);
        $paginatedData = array_slice($stokAkhir, ($page - 1) * $perPage, $perPage);

        // Buat objek pager dengan grup 'stok' yang unik
        $pager = service('pager');
        $pager->makeLinks($page, $perPage, $total, 'default_full', 0, 'stok');

        // Kembalikan data yang sudah dipaginasi dan objek pager-nya
        return [$paginatedData, $pager];
    }
}
