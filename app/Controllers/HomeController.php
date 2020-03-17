<?php

namespace App\Controllers;

use App\Models\Post;

class HomeController extends Controller
{
    public function index($request, $response)
    {
        $data = [
            'posts' => Post::all()
        ];

        return $this->container->view->render($response, 'index.twig', $data);
    }
}