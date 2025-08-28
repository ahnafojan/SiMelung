<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika user belum login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Jika ada role yang dipassing dari routes
        if ($arguments && isset($arguments[0])) {
            $allowedRole = $arguments[0];

            if (session()->get('role') !== $allowedRole) {
                return redirect()->to('/')->with('error', 'Anda tidak punya akses ke halaman ini!');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak melakukan apa-apa setelah request
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cegah cache halaman setelah login
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                 ->setHeader('Pragma', 'no-cache')
                 ->setHeader('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }
}
