<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public $defaultGroup = 'default';

    public $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'db_walet',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
    ];

    public array $test = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
    ];

    public function __construct()
    {
        parent::__construct();
        if (getenv('database.default.hostname')) {
            $this->default['hostname'] = getenv('database.default.hostname');
            $this->default['username'] = getenv('database.default.username');
            $this->default['password'] = getenv('database.default.password') ?: '';
            $this->default['database'] = getenv('database.default.database');
            $this->default['DBDriver'] = getenv('database.default.DBDriver') ?: 'MySQLi';
            $this->default['port']     = (int) (getenv('database.default.port') ?: 3306);
            $this->default['charset']  = getenv('database.default.charset') ?: 'utf8mb4';
            $this->default['DBCollat'] = getenv('database.default.DBCollat') ?: 'utf8mb4_general_ci';
        }
    }
}
