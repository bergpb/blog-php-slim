<?php

namespace App\Handlers;

class ErrorHandler
{
    // permite chamar o objeto como se fosse uma função
    public function __invoke($request, $response, $exception)
    {
        return $response->withStatus(500)
                        ->withJson([
                            'error' => 'Something is wrong!'
                        ]);
    }
}