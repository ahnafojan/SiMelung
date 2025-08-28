<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetKomersialModel;
use CodeIgniter\API\ResponseTrait;

/**
 * @property \CodeIgniter\HTTP\IncomingRequest $request 
 */
class KomersialRekapAset extends BaseController
{
    use ResponseTrait;

    protected $asetModel;
    protected $pager;

    public function __construct()
    {
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

        // --- PERBAIKAN UNTUK TypeError ---
        // Workaround untuk pager versi lama agar filter tetap ada di link paginasi
        $queryParams = $this->request->getGet();
        unset($queryParams['page_aset']); // Hapus parameter halaman agar tidak duplikat
        if (!empty($queryParams)) {
            // Gabungkan URL dasar dengan query string filter secara manual
            $pagerAset->setPath(current_url() . '?' . http_build_query($queryParams));
        } else {
            $pagerAset->setPath(current_url());
        }
        // --- AKHIR PERBAIKAN ---

        // --- Logika untuk merespons AJAX ---
        if ($this->request->isAJAX()) {

            $table_partial = view('admin_komersial/laporan/_aset_table_partial', [
                'aset'      => $dataAset,
                'pagerAset' => $pagerAset
            ]);

            // Hitung ulang statistik berdasarkan filter (tanpa paginasi)
            $statsQuery = new AsetKomersialModel(); // Gunakan instance baru
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

        return view('admin_komersial/laporan/aset', $data);
    }
}
