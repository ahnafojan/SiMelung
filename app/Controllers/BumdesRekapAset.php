<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetKomersialModel; // TETAP: Menggunakan model yang sama
use CodeIgniter\API\ResponseTrait;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request 
 */
class BumdesRekapAset extends BaseController // DIUBAH: Nama class
{
    use ResponseTrait;

    protected $asetModel;
    protected $pager;

    public function __construct()
    {
        // TETAP: Menggunakan instance AsetKomersialModel sesuai permintaan
        $this->asetModel = new AsetKomersialModel();
        $this->pager = \Config\Services::pager();
    }

    public function index()
    {
        $filterTahunAset = $this->request->getGet('tahun_aset') ?? 'semua';
        $perPageAset     = $this->request->getGet('per_page_aset') ?? 10;

        // --- Logika untuk mendapatkan data ---
        $asetModelBuilder = $this->asetModel;
        if ($filterTahunAset && $filterTahunAset != 'semua') {
            $asetModelBuilder->where('tahun_perolehan', $filterTahunAset);
        }

        $dataAset = $asetModelBuilder->orderBy('tahun_perolehan', 'DESC')->paginate($perPageAset, 'aset');
        $pagerAset = $this->asetModel->pager;

        // Workaround untuk pager agar filter tetap ada di link paginasi
        $queryParams = $this->request->getGet();
        unset($queryParams['page_aset']);
        if (!empty($queryParams)) {
            $pagerAset->setPath(current_url() . '?' . http_build_query($queryParams));
        } else {
            $pagerAset->setPath(current_url());
        }

        // --- Logika untuk merespons AJAX ---
        if ($this->request->isAJAX()) {

            // DIUBAH: Path untuk partial view
            $table_partial = view('bumdes/laporan/_aset_table_partial', [
                'aset'      => $dataAset,
                'pagerAset' => $pagerAset
            ]);

            // Hitung ulang statistik berdasarkan filter (tanpa paginasi)
            $statsQuery = new AsetKomersialModel(); // TETAP: Menggunakan instance AsetKomersialModel
            if ($filterTahunAset && $filterTahunAset != 'semua') {
                $statsQuery->where('tahun_perolehan', $filterTahunAset);
            }
            $allAset = $statsQuery->findAll();
            $totalNilai = array_sum(array_column($allAset, 'nilai_perolehan'));

            return $this->response->setJSON([
                'table_partial' => $table_partial,
                'stats' => [
                    'total_aset'   => number_format(count($allAset), 0, ',', '.'),
                    'total_nilai'  => number_format($totalNilai, 0, ',', '.'),
                    'filter_aktif' => $filterTahunAset == 'semua' ? 'Semua Tahun' : 'Tahun ' . $filterTahunAset,
                    'per_page'     => $perPageAset . ' Item'
                ]
            ]);
        }

        // --- Kode untuk pemuatan halaman biasa (non-AJAX) ---
        $daftarTahunAset = $this->asetModel->getTahunPerolehan();
        $data = [
            'aset'          => $dataAset,
            'pagerAset'     => $pagerAset,
            'daftarTahun'   => $daftarTahunAset,
            'filterTahun'   => $filterTahunAset,
            'perPageAset'   => $perPageAset,
        ];

        // DIUBAH: Path untuk view utama
        return view('bumdes/laporan/aset', $data);
    }
}
