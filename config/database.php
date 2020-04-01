<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

return [
    'development' => [
        'adapter' => 'sqlite',
        'name' => './database/database'
    ],
    'production' => [
        'driver' => getenv('DB_DRIVER'),
        'host' => getenv('DB_HOST'),
        'database' => getenv('DB_DATABASE'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]
];