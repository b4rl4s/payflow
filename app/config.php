<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'name' => getenv('DB_NAME') ?: 'payflow_core',
        'user' => getenv('DB_USER') ?: 'payflow_app',
        'pass' => getenv('DB_PASS') ?: '',
    ],
    'redis' => [
        'host' => getenv('REDIS_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('REDIS_PORT') ?: '6379'),
    ],
    'env' => getenv('APP_ENV') ?: 'production',
];
