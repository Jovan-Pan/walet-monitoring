<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Constants;

class AuthController extends BaseController
{
    /**
     * Halaman login
     */
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login', ['appName' => 'Sistem Monitoring Walet']);
    }

    /**
     * Proses login dengan rate limiting (P0-5) & session regenerate (P0-6)
     */
    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $ip       = $this->request->getIPAddress();

        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi');
        }

        // P0-5: Rate limiting - cek apakah IP+username sedang di-lock
        if ($this->isLocked($ip, $username)) {
            $minutes = $this->getLockRemaining($ip, $username);
            return redirect()->back()->withInput()
                ->with('error', "Terlalu banyak percobaan gagal. Akun terkunci untuk sementara. Coba lagi dalam {$minutes} menit.");
        }

        $model = new UserModel();
        $user  = $model->findByUsername($username);

        // Username tidak ditemukan
        if (! $user) {
            $this->recordFailedAttempt($ip, $username);
            return redirect()->back()->withInput()->with('error', 'Username atau password salah');
        }

        // Akun nonaktif
        if ($user['status'] !== 'aktif') {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan, hubungi admin');
        }

        // Password salah
        if (! password_verify($password, $user['password'])) {
            $this->recordFailedAttempt($ip, $username);
            $remaining = Constants::LOGIN_MAX_ATTEMPTS - $this->getAttemptCount($ip, $username);
            $msg = $remaining > 0
                ? "Username atau password salah. Percobaan tersisa: {$remaining}"
                : 'Username atau password salah. Akun terkunci selama ' . Constants::LOGIN_LOCK_MINUTES . ' menit.';
            return redirect()->back()->withInput()->with('error', $msg);
        }

        // === LOGIN BERHASIL ===

        // P0-5: Reset counter gagal
        $this->clearAttempts($ip, $username);

        // P0-6: Session regenerate untuk mencegah session fixation
        session()->regenerate(true);

        // Update last login
        $model->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Set session data
        session()->set([
            'id'                   => $user['id'],
            'nama'                 => $user['nama'],
            'username'             => $user['username'],
            'email'                => $user['email'],
            'role'                 => $user['role'],
            'foto'                 => $user['foto'],
            'isLoggedIn'           => true,
            'must_change_password' => ! empty($user['must_change_password']),
        ]);

        // P0-4: Force password change kalau flag aktif
        if (! empty($user['must_change_password'])) {
            return redirect()->to('/auth/change-password')
                ->with('warning', ' demi keamanan, Anda wajib mengganti password default sebelum bisa mengakses sistem.');
        }

        return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . $user['nama']);
    }

    /**
     * Form ganti password (P0-4)
     */
    public function changePasswordForm()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        return $this->render('auth/change_password', [
            'title'    => 'Ganti Password',
            'forced'   => session()->get('must_change_password') ? true : false,
        ]);
    }

    /**
     * Proses ganti password (P0-4)
     */
    public function changePassword()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $rules = [
            'current'         => 'required',
            'new'             => 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]',
            'confirm'         => 'required|matches[new]',
        ];

        $messages = [
            'new' => [
                'min_length'  => 'Password baru minimal 8 karakter',
                'regex_match' => 'Password harus mengandung huruf besar, huruf kecil, dan angka',
            ],
            'confirm' => [
                'matches' => 'Konfirmasi password tidak cocok',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId  = session()->get('id');
        $model   = new UserModel();
        $user    = $model->find($userId);

        if (! password_verify($this->request->getPost('current'), $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah');
        }

        $newPassword = $this->request->getPost('new');

        // Cek password tidak sama dengan yang lama
        if (password_verify($newPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Password baru tidak boleh sama dengan password lama');
        }

        // Update password + clear must_change flag
        $model->update($userId, [
            'password'             => $newPassword,  // hashPassword event di model akan hash ini
            'must_change_password' => 0,
        ]);

        // Update session
        session()->set('must_change_password', false);

        $this->notifikasi('success', 'Password berhasil diganti. Akun Anda kini aman.');
        return redirect()->to('/dashboard');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah keluar dari sistem');
    }

    // =========================================================
    // PRIVATE: Rate limiting helpers (P0-5)
    // =========================================================

    private function isLocked(string $ip, string $username): bool
    {
        $db = \Config\Database::connect();
        $row = $db->table('login_attempts')
            ->where('ip_address', $ip)
            ->where('username', $username)
            ->where('locked_until IS NOT NULL')
            ->where('locked_until >', date('Y-m-d H:i:s'))
            ->get()->getRowArray();

        return ! empty($row);
    }

    private function getLockRemaining(string $ip, string $username): int
    {
        $db = \Config\Database::connect();
        $row = $db->table('login_attempts')
            ->where('ip_address', $ip)
            ->where('username', $username)
            ->get()->getRowArray();

        if (empty($row) || empty($row['locked_until'])) return 0;

        $diff = strtotime($row['locked_until']) - time();
        return max(0, (int) ceil($diff / 60));
    }

    private function recordFailedAttempt(string $ip, string $username): void
    {
        $db = \Config\Database::connect();
        $builder = $db->table('login_attempts');
        $row = $builder->where('ip_address', $ip)->where('username', $username)->get()->getRowArray();

        $now = date('Y-m-d H:i:s');

        if (empty($row)) {
            $builder->insert([
                'ip_address'    => $ip,
                'username'      => $username,
                'attempt_count' => 1,
                'last_attempt'  => $now,
                'locked_until'  => null,
            ]);
        } else {
            $newCount = (int) $row['attempt_count'] + 1;
            $lockUntil = null;

            if ($newCount >= Constants::LOGIN_MAX_ATTEMPTS) {
                $lockUntil = date('Y-m-d H:i:s', strtotime('+' . Constants::LOGIN_LOCK_MINUTES . ' minutes'));
            }

            $builder->where('ip_address', $ip)->where('username', $username)->update([
                'attempt_count' => $newCount,
                'last_attempt'  => $now,
                'locked_until'  => $lockUntil,
            ]);
        }
    }

    private function getAttemptCount(string $ip, string $username): int
    {
        $db = \Config\Database::connect();
        $row = $db->table('login_attempts')
            ->where('ip_address', $ip)
            ->where('username', $username)
            ->get()->getRowArray();
        return $row['attempt_count'] ?? 0;
    }

    private function clearAttempts(string $ip, string $username): void
    {
        $db = \Config\Database::connect();
        $db->table('login_attempts')
            ->where('ip_address', $ip)
            ->where('username', $username)
            ->delete();
    }
}
