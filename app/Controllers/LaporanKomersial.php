<?php

namespace App\Controllers;

class LaporanKomersial extends BaseController
{
    public function index()
    {
        return view('admin_komersial/laporan/index');
    }
}
