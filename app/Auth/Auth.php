<?php

namespace App\Auth;

use App\Models\User;

class Auth {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function user()
    {
        if(isset($_SESSION['user']))
            return User::find($_SESSION['user']);
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attempt(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if(!$user || !password_verify($password, $user->password)){
            $this->container->flash->addMessage('error', 'Usuário e/ou senha incorretos.');
            return false;
        }

        $_SESSION['user'] = $user->id;
        return true;
    }
}