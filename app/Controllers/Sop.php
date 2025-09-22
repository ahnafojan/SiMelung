<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Sop extends Controller
{
    public function download()
    {
        $filepath = FCPATH . 'uploads/SOP-revs.pdf';
        $filename = 'SOP_Budidaya_Kopi_Desa_Melung.pdf';

        if (!file_exists($filepath)) {
            return redirect()->back()->with('error', 'File SOP tidak ditemukan');
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(file_get_contents($filepath));
    }
}
