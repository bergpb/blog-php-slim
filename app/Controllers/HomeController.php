<?php

namespace App\Controllers;

use App\Models\Post;
use Exception;

class HomeController extends Controller
{
    public function index($request, $response)
    {
        $data = [
            'posts' => Post::where('published', true)
                            ->orderBy('created_at', 'DESC')
                            ->get(),
        ];

        return $this->container->view->render($response, 'index.twig', $data);
    }
}