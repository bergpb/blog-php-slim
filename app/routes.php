<?php

$app->get('/', 'HomeController:index')->setName('home');

$app->group('/auth', function () {
    $this->map(['GET', 'POST'], '/login', 'AuthController:login')->setName('auth.login');
    $this->map(['GET', 'POST'], '/registrar', 'AuthController:register')->setName('auth.register');
    $this->get('/logout', 'AuthController:logout')->setName('auth.logout');
});