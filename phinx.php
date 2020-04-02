<?php

require __DIR__ . '/vendor/autoload.php';

$db = include __DIR__ . '/config/enviroments/production.php';

return [
    'paths' => [
        'migrations' => [
            __DIR__ . '/database/migrations'
        ],
        'seeds' => [
            __DIR__ . '/database/seeds'
        ]
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database' => 'development',
        'development' => [
            'adapter' => 'sqlite',
            'name' => './database/database'
        ],
        'production' => [
            'adapter' => $db['driver'],
            'host' => $db['host'],
            'name' => $db['database'],
            'user' => $db['username'],
            'pass' => $db['password'],
            'charset' => $db['charset'],
            'collation' => $db['collation'],
            'prefix' => $db['prefix'],
        ]
    ]
];