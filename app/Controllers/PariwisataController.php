<?php

namespace App\Controllers;

use App\Models\PariwisataModel;

class PariwisataController extends BaseController
{
    protected $pariwisataModel;

    public function __construct()
    {
        $this->pariwisataModel = new PariwisataModel();
    }

    public function index()
    {
        $data['pariwisata'] = $this->pariwisataModel->findAll();
        return view('pariwisata/index', $data);
    }
}
