<?php

namespace App\Controllers;

use App\Models\Post;

use Respect\Validation\Validator as v;

class PostController extends Controller
{
    public function create($request, $response){
        if($request->isGet())
            return $this->container->view->render($response, 'post/create.twig');

        $validation = $this->container->validator->validate($request, [
            'title' => v::length(5)->notEmpty(),
            'description' => v::notEmpty()
        ]);

        if($validation->failed())
            return $response->withRedirect($this->container->router->pathFor('post.create'));

        Post::create([
            'title' => $request->getParam('title'),
            'description' => $request->getParam('description'),
            'published' => $request->getParam('published') == '0' ? false : true,
            'user_id' => $this->container->auth->user()->id
        ]);

        $this->container->flash->addMessage('success', 'Post cadastrado com sucesso.');
        return $response->withRedirect($this->container->router->pathFor('post.create'));
    }

    public function edit($request, $response){
        return $this->container->view->render($response, 'post/edit.twig');
    }

    public function update($request, $response){
        
    }

    public function delete($request, $response){
        
    }
}