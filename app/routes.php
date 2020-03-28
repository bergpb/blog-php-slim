<?php

use App\Middleware\AuthMiddleware;

$app->get('/', 'HomeController:index')->setName('home');

$app->group('/postagem', function () {
    $this->map(['GET', 'POST'], '/criar', 'PostController:create')->setName('post.create');
    $this->get('/edit/{id}', 'PostController:edit')->setName('post.edit');
    $this->post('/edit/{id}', 'PostController:update')->setName('post.update');
    $this->get('/deletar', 'PostController:delete')->setName('post.delete');
})->add(new AuthMiddleware($container));

$app->group('/usuario', function () {
    $this->map(['GET', 'POST'], '/avatar', 'UserController:avatar')->setName('user.avatar');
    $this->get('/posts', 'UserController:posts')->setName('user.posts');
})->add(new AuthMiddleware($container));

$app->group('/auth', function () {
    $this->map(['GET', 'POST'], '/login', 'AuthController:login')->setName('auth.login');
    $this->map(['GET', 'POST'], '/registrar', 'AuthController:register')->setName('auth.register');
    $this->get('/confirmacao', 'AuthController:confirmation')->setName('auth.confirmation');
    $this->get('/reenviar', 'AuthController:resend')->setName('auth.resend');
    $this->get('/logout', 'AuthController:logout')->setName('auth.logout');
});