<?php

session_start();

date_default_timezone_set('America/Fortaleza');

require __DIR__ . '/../vendor/autoload.php';

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => '0.0.0.0',
            'database' => 'mpblog',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
    ],
]);

$container = $app->getContainer();

$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// use validators
$container['validator'] = function($container) {
    return new App\Validation\Validator;
};

// use flash messages
$container['flash'] = function($container) {
    return new Slim\Flash\Messages;
};

// use authentication
$container['auth'] = function($container) {
    return new App\Auth\Auth($container);
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);

    $view->addExtension(new Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri(),
    ));

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    return $view;
};

$container['HomeController'] = function ($container) {
    return new App\Controllers\HomeController($container);
};

$container['AuthController'] = function ($container) {
    return new App\Controllers\AuthController($container);
};

$app->add(new App\Middleware\DisplayInputErrorsMiddleware($container));

require __DIR__ . '/routes.php';
