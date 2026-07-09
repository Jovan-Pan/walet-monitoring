<?php

/**
 * --------------------------------------------------------------------
 * CodeIgniter4 Application Bootstrap (HTTP front controller)
 * --------------------------------------------------------------------
 * Aligned with CodeIgniter 4.4.x bootstrap conventions.
 */

// Check PHP version.
$minPhpVersion = '7.4';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    exit(sprintf('Your PHP version must be %s or higher to run CodeIgniter. Current version: %s', $minPhpVersion, PHP_VERSION));
}

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Load our paths config file
require FCPATH . '../app/Config/Paths.php';

$paths = new Config\Paths();

// Location of the framework bootstrap file.
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

// Load environment settings from .env files into $_SERVER and $_ENV
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

// Define ENVIRONMENT
if (! defined('ENVIRONMENT')) {
    define('ENVIRONMENT', env('CI_ENVIRONMENT', 'production'));
}

// Grab our CodeIgniter instance
$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

$app->run();

exit(EXIT_SUCCESS);
