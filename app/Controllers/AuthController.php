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

        $validation = $this->container->validator->validate($request, 
            [
                'email' => v::notEmpty()->noWhitespace()->email(),
                'password' => v::notEmpty()->noWhitespace()
            ],
            [
                'notEmpty' => 'Campo deve ser preenchido.',
                'noWhitespace' => 'Campo nao deve conter espacos em branco.',
                'email' => 'Email nao e valido.'
            ]
        );

        if($validation->failed())
            return $response->withRedirect($this->container->router->pathFor('auth.login'));

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

        $validation = $this->container->validator->validate($request, 
            [
                'name' => v::notEmpty()->alpha()->length(10),
                'email' => v::notEmpty()->noWhitespace()->email(),
                'password' => v::notEmpty()->noWhitespace()
            ],
            [
                'noWhitespace' => 'Campo nao deve conter espacos em branco.',
                'notEmpty' => 'Campo deve ser preenchido.',
                'length' => 'Campo deve conter no minimo 10 caracteres.',
                'alpha' => 'Caracteres invalidos inseridos. Apenas letras sao aceitas.',
                'email' => 'Email nao e valido.'
            ]
        );

        if($validation->failed())
            return $response->withRedirect($this->container->router->pathFor('auth.register'));

        $now = new \Datetime(date('d-m-Y H:i:s'));
        $now->modify('+1 hour');
        $key = bin2hex(random_bytes(20));

        $user = User::create([
            'name' => $request->getParam('name'),
            'email' => $request->getParam('email'),
            'password' => password_hash($request->getParam('password'), PASSWORD_BCRYPT),
            'avatar' => 'default-user.png',
            'confirmation_key' => $key,
            'confirmation_expires' => $now,
        ]);

        $user->permissions()->create(UserPermission::$defaults);

        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'confirmation' => $key
        ];

        $this->container->mail->send($payload, 'welcome.twig', 'Bem vindo ao blog ' . $user->name, $payload);
        $this->container->flash->addMessage('success', 'Acesse seu email e confirme sua conta.');

        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function logout($request, $response)
    {
        if(isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        return $response->withRedirect($this->container->router->pathFor('home'));
    }

    public function confirmation($request, $response)
    {
        $user = User::where('confirmation_key', $request->getParam('confirmation'))->first();

        if(!$user) {
            $this->container->flash->addMessage('error', 'Conta inexistente.');
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        if($user->is_confirmation) {
            $this->container->flash->addMessage('success', 'Conta já confirmada anteriormente.');
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        if (strtotime(date('d-m-Y H:i:s')) > strtotime($user->confirmation_expires)) {
            $this->container->flash->addMessage('error', "Parece que você demorou um pouco para confirmar seu cadastro.
                Clique <a target='_blank' href='".$this->container->router->pathFor('auth.resend')."?email=".$user->email."'>aqui</a> para reenviar o email de confirmação.");
        } else {
            $user->is_confirmation = true;
            $user->save();
            $this->container->flash->addMessage('success', 'Conta confirmada com sucesso!');
        }

        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function resend($request, $response)
    {
        if(empty($request->getParam('email'))) {
            $this->container->flash->addMessage('error', 'Houve um erro ao tentar processar sua solicitação.');
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        $now = new \Datetime(date('d-m-Y H:i:s'));
        $now->modify('+1 hour');
        $key = bin2hex(random_bytes(20));

        $user = User::where('email', $request->getParam('email'))->first();
        $user->confirmation_key = $key;
        $user->confirmation_expires = $now;
        $user->save();

        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'confirmation' => $key
        ];

        $this->container->mail->send($payload, 'resend.twig', 'Reenvio de confirmação', $payload);

        $this->container->flash->addMessage('success', 'Um novo email de confirmação foi enviado com sucesso.');
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }
}