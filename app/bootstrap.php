<?php

session_start();

date_default_timezone_set('America/Fortaleza');

require __DIR__ . '/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handle\StreamHandler;

// load .env file in project root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

$environment = getenv('APP_ENV');

$app = new Slim\App([
    'settings' => [
        'db' => [
            'driver' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
        'displayErrorDetails' => ($environment == 'development') ? true : false,
    ],
]);

// init container
$container = $app->getContainer();

// use eloquent
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// only render this handler in development mode
if($environment == 'development') {
    $container['errorHandler'] = function ($c) {
        return new App\Handlers\ErrorHandler($c->get('view'));
    };
};

$container['notFoundHandler'] = function ($c) {
    return new App\Handlers\NotFoundHandler($c->get('view'));
};

// $container['logger'] = function () {
//     $logger = new Monolog\Logger('Logger');
//     $fileHandler = new Monolog\Handler\StreamHandler('/logs/api.log');
//     $logger->pushHandler($fileHandler);
//     return $logger;
// };

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

// use upload
$container['upload_directory'] = __DIR__ . '/../public/uploads';

// use email
$container['mail'] = function($container) {
    return new App\Mail($container);
};

// pass variables into you views
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

    $view->getEnvironment()->addGlobal('current_path',
        $container["request"]->getUri()->getPath()
    );

    return $view;
};

// register services(controllers)
$container['HomeController'] = function ($container) {
    return new App\Controllers\HomeController($container);
};

$container['AuthController'] = function ($container) {
    return new App\Controllers\AuthController($container);
};

$container['UserController'] = function ($container) {
    return new App\Controllers\UserController($container);
};

$container['PostController'] = function ($container) {
    return new App\Controllers\PostController($container);
};

$app->add(new App\Middleware\DisplayInputErrorsMiddleware($container));

require __DIR__ . '/routes.php';
