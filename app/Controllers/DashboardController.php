<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function desa()
    {
        return view('dashboard/dashboard_desa');
    }

    public function bumdes()
    {
        return view('dashboard/dashboard_bumdes');
    }

    public function keuangan()
    {
        return view('dashboard/keuangan_view');
    }

    public function umkm()
    {
        return view('dashboard/dashboard_umkm');
    }

    public function komersial()
    {
        return view('dashboard/dashboard_komersial');
    }
    public function pariwisata()
    {
        return view('dashboard/pariwisata_view');
    }
}
