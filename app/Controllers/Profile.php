<?php

namespace App\Controllers;

class Profile extends BaseController
{
    public function index()
    {
        return view('admin/profile/index');
    }
}
