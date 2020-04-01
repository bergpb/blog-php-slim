<?php

session_start();

date_default_timezone_set('America/Fortaleza');

require __DIR__ . '/../vendor/autoload.php';

// load database configurations
$db = include __DIR__ . '/../config/database.php';

$env = getenv('APP_ENV');

$app = new Slim\App([
    'settings' => [
        'env' => getenv('APP_ENV'),
        'db' => [
            'driver'    => 'sqlite',
            'database'  => __DIR__ . '/../database/database.sqlite3',
            'prefix'    => ''
        ],
        // 'db' => [
        //     'driver' => $db['development']['driver'],
        //     'host' => $db['development']['adapter'],
        //     'database' => $db['development']['adapter'],
        //     'username' => $db['development']['adapter'],
        //     'password' => $db['development']['adapter'],
        //     'charset' => $db['development']['adapter'],
        //     'collation' => $db['development']['adapter'],
        //     'prefix' => $db['development']['adapter'],
        // ],
        'displayErrorDetails' => (getenv('APP_ENV') == 'development') ? true : false,
    ],
]);

// init container
$container = $app->getContainer();

// use eloquent
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

require __DIR__ . '/container.php';

require __DIR__ . '/services.php';

require __DIR__ . '/middlewares.php';

require __DIR__ . '/routes.php';
