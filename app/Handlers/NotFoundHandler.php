<?php

namespace App\Handlers;

use Slim\Views\Twig;

class NotFoundHandler
{
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    // call class like a function
    public function __invoke($request, $response)
    {
        return $this->container->view->render($response, 'errors/404.twig')->withStatus(404);
    }
}