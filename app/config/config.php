<?php

declare(strict_types=1);

return new \Phalcon\Config([
    'database' => [
        'dsn' => \getenv('DSN'),
        'dbname' => \getenv('DBNAME'),
    ],
    'jwtAuth' => [
        'secretKey' => \getenv('APP_SECRET'),
        'payload' => [
            'exp' => 1440,
            'iss' => 'phalmon',
        ],
        'ignoreUri' => [
            '/',
            '/api',
            '/api/login',
        ],
    ],
    'debug' => (bool) \getenv('APP_DEBUG'),
    'queue' => [
        'host' => \getenv('QUEUE_HOST'),
        'port' => \getenv('QUEUE_PORT'),
    ],
    'cors_origin' => \getenv('CORS_ORIGIN'),
]);
