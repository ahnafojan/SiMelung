<?php

namespace App\Controllers;

class LaporanBumdes extends BaseController
{
    public function index()
    {
        $data['breadcrumbs'] = [
            [
                'title' => 'Dashboard',
                'url'   => site_url('dashboard/dashboard_bumdes'),
                'icon'  => 'fas fa-fw fa-tachometer-alt'
            ],
            [
                'title' => 'Laporan BUMDES',
                'url'   => '#',
                'icon'  => 'fas fa-fw fa-file-alt'
            ]
        ];

        // Kirimkan array $data yang berisi breadcrumbs ke view
        return view('bumdes/laporan/index', $data);
    }
}
