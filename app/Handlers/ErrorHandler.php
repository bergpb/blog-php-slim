<?php

namespace App\Handlers;

class ErrorHandler
{
    protected $container;

    // call class like a function
    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke($request, $response, $exception)
    {
        $env = $this->container->get('settings')['env'];

        $error = [
            'statusCode' => '500',
                'error' => [
                    'type' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'description' => $exception->getMessage()
                ],
        ];

        $this->container->logger->error($exception->getMessage(), ['error' => $error]);
        if($env == 'production')
            return $this->container->view->render($response, 'errors/500.twig')->withStatus(500);
        
        return $this->container->response
                                ->withStatus(500)
                                ->withJson($error);

    }
}