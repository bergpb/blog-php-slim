<?php

require __DIR__ . '/vendor/autoload.php';

$db = include __DIR__ . '/config/database.php';

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
            'adapter' => $db['development']['adapter'],
            'name' => $db['development']['name'],
        ],
        'production' => [
            'adapter' => $db['production']['driver'],
            'host' => $db['production']['host'],
            'name' => $db['production']['database'],
            'user' => $db['production']['username'],
            'pass' => $db['production']['password'],
            'charset' => $db['production']['charset'],
            'collation' => $db['production']['collation']
        ]
    ]
];