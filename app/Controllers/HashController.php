<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class HashController extends Controller
{
    public function generate()
    {
        $password = "123456"; // password asli
        $hash = password_hash($password, PASSWORD_BCRYPT);

        return "Password: {$password}<br>Hash: {$hash}";
    }
}
