<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\SiteURI;

class Request extends BaseConfig
{
    public array $proxies = [];
    public string $uriProtocol = 'REQUEST_URI';
    public string $defaultLocale = 'id';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['id', 'en'];
}
