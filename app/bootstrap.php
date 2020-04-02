<?php

session_start();

date_default_timezone_set('America/Fortaleza');

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

$db_config = require __DIR__ . '/../config/environments/' . getenv('APP_ENV') . '.php';

$app = new Slim\App([
    'settings' => [
        'env' => getenv('APP_ENV'),
        'db' => $db_config,
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
