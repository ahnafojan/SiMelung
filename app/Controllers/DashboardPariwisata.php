<?php

namespace App\Controllers;

use App\Models\AsetPariwisataModel;

class DashboardPariwisata extends BaseController
{
    public function index()
    {
        $model = new AsetPariwisataModel();

        // Data jumlah aset
        $data['jumlah_aset'] = $model->countAll();

        // Total nilai perolehan
        $data['total_nilai'] = $model->selectSum('nilai_perolehan')
            ->get()
            ->getRow()
            ->nilai_perolehan;

        // Data aset per tahun
        $data['aset_per_tahun'] = $model->select('tahun_perolehan, COUNT(*) as jumlah')
            ->groupBy('tahun_perolehan')
            ->orderBy('tahun_perolehan')
            ->findAll();

        // Data aset per metode pengadaan
        $data['aset_per_metode'] = $model->select('metode_pengadaan, COUNT(*) as jumlah')
            ->groupBy('metode_pengadaan')
            ->findAll();

        return view('dashboard/dashboard_pariwisata', $data);
    }
}
