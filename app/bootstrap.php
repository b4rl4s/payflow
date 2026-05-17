<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'Payflow\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/src/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

$config = require __DIR__ . '/config.php';

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=utf8mb4',
    $config['db']['host'],
    $config['db']['name']
);
$pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

date_default_timezone_set('America/New_York');
session_name('pf_sess');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (PHP_SAPI !== 'cli') {
    session_start();
}

$GLOBALS['payflow.pdo'] = $pdo;
$GLOBALS['payflow.config'] = $config;

if (!function_exists('pf_site')) {

    function pf_site(string $hostname, string $path = '/'): string
    {
        return \Payflow\Support\AppUrl::site($hostname, $path);

    }
}

if (!function_exists('pf_origin')) {

    function pf_origin(string $hostname): string
    {
        return \Payflow\Support\AppUrl::origin($hostname);

    }
}

