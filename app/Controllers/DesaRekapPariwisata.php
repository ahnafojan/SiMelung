<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetPariwisataModel;
use App\Models\ObjekWisataModel;

/**
 * Controller ini menangani pembuatan laporan untuk data pariwisata.
 * Pastikan method laporanObjekWisata() dan laporanAset() ada dan
 * memiliki nama yang sama persis seperti yang didefinisikan di Routes.php.
 * @property \CodeIgniter\HTTP\IncomingRequest $request 
 */
class DesaRekapPariwisata extends BaseController
{
    protected $asetPariwisataModel;
    protected $objekWisataModel;

    /**
     * Constructor untuk menginisialisasi model yang dibutuhkan.
     */
    public function __construct()
    {
        $this->asetPariwisataModel = new AsetPariwisataModel();
        $this->objekWisataModel    = new ObjekWisataModel();
    }

    /**
     * Menampilkan laporan untuk Objek Wisata dengan pagination.
     * Method ini akan dipanggil oleh rute '/desa/laporan_pariwisata/objekwisata'.
     */
    public function laporanObjekWisata()
    {
        // Ambil nilai per_page dari query string, default-nya 10
        $perPage = $this->request->getGet('per_page') ?? 10;
        // Validasi nilai per_page
        $perPage = in_array($perPage, [10, 25, 100]) ? $perPage : 10;

        $wisataData = $this->objekWisataModel->orderBy('nama_wisata', 'ASC')->paginate($perPage, 'wisata');

        $data = [
            'title'       => 'Laporan Objek Wisata',
            'list_wisata' => $wisataData,
            'pager'       => $this->objekWisataModel->pager,
            'perPage'     => $perPage,
            'currentPage' => $this->objekWisataModel->pager->getCurrentPage('wisata'),
        ];

        return view('desa/laporan_pariwisata/objekwisata', $data);
    }

    /**
     * Menampilkan laporan untuk Aset Pariwisata dengan pagination.
     * Method ini akan dipanggil oleh rute '/desa/laporan_pariwisata/asetpariwisata'.
     */
    public function laporanAset()
    {
        // Ambil nilai per_page dari query string, default-nya 10
        $perPage = $this->request->getGet('per_page') ?? 10;
        // Validasi nilai per_page
        $perPage = in_array($perPage, [10, 25, 100]) ? $perPage : 10;

        // Query untuk mengambil data aset beserta nama wisatanya
        $query = $this->asetPariwisataModel
            ->select('
                aset_pariwisata.*, 
                objek_wisata.nama_wisata,
                aset_wisata.wisata_id AS objek_wisata_id
            ')
            ->join('aset_wisata', 'aset_wisata.aset_id = aset_pariwisata.id', 'left')
            ->join('objek_wisata', 'objek_wisata.id = aset_wisata.wisata_id', 'left')
            ->orderBy('objek_wisata.nama_wisata', 'ASC')
            ->orderBy('aset_pariwisata.nama_aset', 'ASC');

        $asetData = $query->paginate($perPage, 'aset');

        $data = [
            'title'     => 'Laporan Aset Pariwisata',
            'list_aset' => $asetData,
            'pager'     => $this->asetPariwisataModel->pager,
            'perPage'   => $perPage,
            'currentPage' => $this->asetPariwisataModel->pager->getCurrentPage('aset'),
            // Mengirim daftar wisata untuk dropdown di modal (jika ada)
            'list_wisata' => $this->objekWisataModel->orderBy('nama_wisata', 'ASC')->findAll(),
        ];

        return view('desa/laporan_pariwisata/asetpariwisata', $data);
    }
}
