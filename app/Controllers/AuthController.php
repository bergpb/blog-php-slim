<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserPermission;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    public function login($request, $response)
    {
        if ($request->isGet())
            return $this->container->view->render($response, 'login.twig');

        if(!$this->container->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password'))) {
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        return $response->withRedirect($this->container->router->pathFor('home'));
    }

    public function register($request, $response)
    {
        if ($request->isGet()) {
            return $this->container->view->render($response, 'register.twig');
        }

        $validation = $this->container->validator->validate($request, [
            'name' => v::notEmpty()->alpha()->length(10),
            'email' => v::notEmpty()->noWhitespace()->email(),
            'password' => v::notEmpty()->noWhitespace()
        ]);

        if($validation->failed())
            return $response->withRedirect($this->container->router->pathFor('auth.register'));

        $now = new \Datetime(date('m/d/Y H:i:s'));
        $now->modify('+1 hour');

        $user = User::create([
            'name' => $request->getParam('name'),
            'email' => $request->getParam('email'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
            'confirmation_key' => bin2hex(random_bytes(20)),
            'confirmation_expires' => $now,
        ]);

        $user->permissions()->create(UserPermission::$defaults);

        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function logout($request, $response)
    {
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
            return $response->withRedirect($this->container->router->pathFor('home'));
        }
    }
}