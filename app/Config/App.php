<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\FileHandler;

/**
 * Class App
 *
 * Aligned with CodeIgniter 4.4.x BaseConfig property signatures.
 * (The project's original App.php used CI 4.5+ typed-property defaults
 *  that conflict with 4.4.x. We keep the application values but use
 *  property types/defaults compatible with 4.4.8.)
 */
class App extends BaseConfig
{
    public $baseURL = 'http://localhost:8080/';

    public $allowedHostnames = [];

    /**
     * Override baseURL dynamically based on the incoming request's host.
     *
     * This makes redirects (e.g. after login) go back to whatever host the
     * user accessed the app from — important when running behind a reverse
     * proxy (Caddy, ngrok, Cloudflare, IM preview proxy, etc.) where the
     * internal port differs from the public URL.
     */
    public function __construct()
    {
        parent::__construct();

        if (is_cli()) {
            return;
        }

        // Prefer X-Forwarded-Host (set by reverse proxies), fall back to Host header.
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? null);
        if (! empty($host)) {
            // Determine scheme: trust X-Forwarded-Proto if present (e.g. "https"),
            // otherwise fall back to HTTPS server var, otherwise http.
            $proto = $_SERVER['HTTP_X_FORWARDED_PROTO']
                ?? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http');
            $proto = strtolower(explode(',', $proto)[0]);
            // Strip whitespace and trailing slash from host.
            $host  = trim($host);
            $this->baseURL = $proto . '://' . $host . '/';
        }
    }

    public $indexPage = '';

    public $uriProtocol = 'REQUEST_URI';

    public $defaultLocale = 'id';

    public $negotiateLocale = false;

    public $supportedLocales = ['id', 'en'];

    public $appTimezone = 'Asia/Jakarta';

    public $charset = 'UTF-8';

    public $forceGlobalSecureRequests = false;

    public $sessionDriver            = FileHandler::class;
    public $sessionCookieName        = 'ci_session';
    public $sessionExpiration        = 7200;
    public $sessionSavePath          = WRITEPATH . 'session';
    public $sessionMatchIP           = false;
    public $sessionTimeToUpdate      = 300;
    public $sessionRegenerateDestroy = false;

    public $cookiePrefix   = '';
    public $cookieDomain   = '';
    public $cookiePath     = '/';
    public $cookieSecure   = false;
    public $cookieHTTPOnly = true;
    public $cookieSameSite = 'Lax';
    public $cookieRaw      = false;

    public $CSPEnabled = false;

    public $proxyIPs = [];

    public $encryptionKey = '';
}
