<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['status' => 'error', 'message' => 'Sesi berakhir, silakan login kembali']);
            }
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Force password change if required (P0-4)
        if ($session->get('must_change_password')) {
            $uri = service('uri')->getPath();
            if ($uri !== 'auth/change-password' && $uri !== 'logout') {
                return redirect()->to('/auth/change-password')
                    ->with('warning', 'Anda harus mengganti password default sebelum bisa mengakses sistem.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada action setelah request
    }
}
