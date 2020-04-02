<?php

return [
    'adapter' => getenv('DB_DRIVER'),
    'host' => getenv('DB_HOST'),
    'name' => getenv('DB_DATABASE'),
    'user' => getenv('DB_USERNAME'),
    'pass' => getenv('DB_PASSWORD'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
];
