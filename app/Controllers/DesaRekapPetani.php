<?php

namespace App\Controllers;

// PENTING: Panggil parent controller-nya
use App\Controllers\KomersialRekapPetani;

/**
 * Controller ini untuk menampilkan rekap petani di level Desa.
 * Dia mewarisi semua method dari KomersialRekapPetani.
 */
class DesaRekapPetani extends KomersialRekapPetani
{
    /**
     * Override fungsi index dari parent.
     * Logikanya dibuat identik dengan parent, namun view yang dipanggil berbeda.
     */
    public function index()
    {
        // 1. Mengambil nilai filter dari URL
        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'jenis_kopi' => $this->request->getGet('jenis_kopi') ?? '',
        ];
        $perPage = $this->request->getGet('per_page') ?? 10;

        // 2. Membangun dan menjalankan query dengan paginasi
        $petaniQuery = $this->_getFilteredPetaniQuery($filters);
        $rekapPetaniTerdaftar = $petaniQuery->paginate($perPage, 'petani');
        $petaniPager = $this->petaniModel->pager;

        // 3. Workaround pager untuk versi lama CodeIgniter
        $queryParams = $this->request->getGet();
        unset($queryParams['page_petani']);
        if (!empty($queryParams)) {
            $petaniPager->setPath(current_url() . '?' . http_build_query($queryParams));
        } else {
            $petaniPager->setPath(current_url());
        }

        // 4. Cek jika ini adalah request AJAX dari filter
        if ($this->request->isAJAX()) {
            // Buat potongan HTML untuk daftar petani
            $list_view_html = $this->_buildPetaniListView($rekapPetaniTerdaftar, $petaniPager);

            // Kembalikan JSON dengan format yang benar sesuai harapan JavaScript
            return $this->response->setJSON([
                'list_view'  => $list_view_html,
                'pagination' => $petaniPager->links('petani', 'default_full'),
                'total'      => $petaniPager->getTotal('petani')
            ]);
        }

        // 5. Kode di bawah ini hanya untuk pemuatan halaman awal (non-AJAX)
        foreach ($rekapPetaniTerdaftar as &$petani) {
            $petani['jenis_kopi_list'] = $petani['jenis_kopi_list'] ?? 'Tidak Terdata';
        }
        $daftarJenisKopi = $this->jenisPohonModel->select('nama_jenis')->distinct()->orderBy('nama_jenis', 'ASC')->findAll();

        $data = [
            'rekapPetaniTerdaftar' => $rekapPetaniTerdaftar,
            'petaniPager'          => $petaniPager,
            'filters'              => $filters,
            'perPage'              => $perPage,
            'daftarJenisKopi'      => $daftarJenisKopi,
        ];
        $data['petaniListView'] = $this->_buildPetaniListView($rekapPetaniTerdaftar, $petaniPager);

        // PERBEDAAN UTAMA: Mengarahkan ke view yang berbeda untuk Desa
        return view('desa/laporan_komersial/petani', $data);
    }
}
