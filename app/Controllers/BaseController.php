<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController menyediakan tempat yang nyaman untuk komponen yang
 * akan digunakan di semua controller aplikasi.
 */
abstract class BaseController extends Controller
{
    protected $helpers = ['url', 'form', 'filesystem'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    /**
     * Render view dengan layout default
     */
    protected function render(string $view, array $data = []): string
    {
        $session = session();
        $data['currentUser'] = [
            'id'       => $session->get('id'),
            'nama'     => $session->get('nama'),
            'username' => $session->get('username'),
            'role'     => $session->get('role'),
            'foto'     => $session->get('foto'),
        ];
        $data['appName'] = 'Sistem Monitoring Walet';

        return view('layout/header', $data)
             . view('layout/sidebar', $data)
             . view($view, $data)
             . view('layout/footer', $data);
    }

    /**
     * Notifikasi toast setelah redirect
     */
    protected function notifikasi(string $tipe, string $pesan)
    {
        session()->setFlashdata('toast', json_encode(['tipe' => $tipe, 'pesan' => $pesan]));
    }
}
