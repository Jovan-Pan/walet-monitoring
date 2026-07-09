<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Role Filter (P0-2)
 *
 * Usage in Routes: ['filter' => 'role:admin'] or ['filter' => 'role:admin,owner']
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Must be logged in first
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Force password change if required (P0-4)
        if ($session->get('must_change_password') && ! $this->isOnChangePasswordPage()) {
            return redirect()->to('/auth/change-password')
                ->with('warning', 'Anda harus mengganti password default sebelum bisa mengakses sistem.');
        }

        // Check role
        $allowedRoles = $arguments ?? [];
        $currentRole  = $session->get('role');

        if (! empty($allowedRoles) && ! in_array($currentRole, $allowedRoles)) {
            // Log unauthorized access attempt
            log_message('warning', "Akses ditolak: user='{$session->get('username')}' role='{$currentRole}' mencoba akses route yang butuh role=" . implode(',', $allowedRoles));

            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman tersebut.');
        }
    }

    private function isOnChangePasswordPage(): bool
    {
        $uri = service('uri')->getPath();
        return in_array($uri, ['auth/change-password'], true);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosong
    }
}
