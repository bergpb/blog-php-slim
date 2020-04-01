<?php

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