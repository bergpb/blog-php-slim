<?php

// use monolog
$container['logger'] = function() {
    $logger = new Monolog\Logger('Logger');
    $fileHandler = new Monolog\Handler\StreamHandler(__DIR__ . '/../logs/errors.log');
    $logger->pushHandler($fileHandler);
    return $logger;
};

// to catch exceptions
$container['errorHandler'] = function ($container) {
    return new App\Handlers\ErrorHandler($container);
};

// to catch 404 errors
$container['notFoundHandler'] = function ($container) {
    return new App\Handlers\NotFoundHandler($container);
};

// phpErrorHandler
$container['phpErrorHandler'] = function ($container) {
    return $container['errorHandler'];
};

// use validators
$container['validator'] = function() {
    return new App\Validation\Validator;
};

// use flash messages
$container['flash'] = function() {
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

$container['csrf'] = function(){
    return new \Slim\Csrf\Guard;
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

    // csrf extension
    $view->addExtension(new App\Views\CsrfExtension($container['csrf']));

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