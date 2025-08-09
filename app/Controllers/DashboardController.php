<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function desa()
    {
        return view('dashboard/desa_view');
    }

    public function bumdes()
    {
        return view('dashboard/bumdes_view');
    }

    public function keuangan()
    {
        return view('dashboard/keuangan_view');
    }

    public function umkm()
    {
        return view('dashboard/umkm_view');
    }

    public function broker()
    {
        return view('dashboard/broker_view');
    }
    public function pariwisata()
    {
        return view('dashboard/pariwisata_view');
    }
}
