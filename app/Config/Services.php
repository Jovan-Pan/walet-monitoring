<?php

namespace Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function paginator($getShared = true)
    {
        if ($getShared) return static::getSharedInstance('paginator');
        return new \CodeIgniter\Pager\Pager(static::config('Pager'));
    }
}
