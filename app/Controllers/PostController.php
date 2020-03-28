<?php

namespace App\Controllers;

use App\Models\Post;

use Respect\Validation\Validator as v;

class PostController extends Controller
{
    public function create($request, $response){
        if($request->isGet())
            return $this->container->view->render($response, 'post/create.twig');

        $validation = $this->container->validator->validate($request, 
            [
                'title' => v::length(5)->notEmpty(),
                'description' => v::notEmpty()
            ],
            [
                'notEmpty' => 'Campo deve ser preenchido.',
                'length' => 'Campo deve conter no minimo 5 caracteres.',
            ]
        );

        if($validation->failed())
            return $response->withRedirect($this->container->router->pathFor('post.create'));

        Post::create([
            'title' => $request->getParam('title'),
            'description' => $request->getParam('description'),
            'published' => $request->getParam('published') == '0' ? false : true,
            'user_id' => $this->container->auth->user()->id
        ]);

        $this->container->flash->addMessage('success', 'Post cadastrado com sucesso.');
        return $response->withRedirect($this->container->router->pathFor('user.posts'));
    }

    public function edit($request, $response, $params)
    {
        $user_id = $this->container->auth->user()->id;

        $data = [
            'post' => Post::find($params['id']),
        ];

        if(!$this->container->auth->admin())
            if($data['post']['user_id'] != $user_id)
            {
                $this->container->flash->addMessage('warning', 'Sem permissão para alterar este post.');
                return $response->withRedirect($this->container->router->pathFor('user.posts'));
            }

        return $this->container->view->render($response, 'post/edit.twig', $data);
    }

    public function update($request, $response, $params)
    {
        $post = Post::find($params['id']);
        $user_id = $this->container->auth->user()->id;

        if(!$this->container->auth->admin())
            if($post['user_id'] != $user_id)
            {
                $this->container->flash->addMessage('warning', 'Sem permissão para alterar este post.');
                return $response->withRedirect($this->container->router->pathFor('user.posts'));
            }

        $validation = $this->container->validator->validate($request, 
            [
                'title' => v::length(5)->notEmpty(),
                'description' => v::notEmpty()
            ],
            [
                'notEmpty' => 'Campo deve ser preenchido.',
                'length' => 'Campo deve conter no minimo 5 caracteres.',
            ]
        );

        if($validation->failed())
            return $response->withRedirect($this->container->router->pathFor('post.edit', ['id' => $post->id]));

        $post->title = $request->getParam('title');
        $post->description = $request->getParam('description');
        $post->published = $request->getParam('published');
        $post->save();

        $this->container->flash->addMessage('success', 'Post alterado.');
        return $response->withRedirect($this->container->router->pathFor('user.posts', ['id' => $post->id]));
    }

    public function delete($request, $response)
    {
        $post = Post::find($request->getParam('id'));
        $user_id = $this->container->auth->user()->id;

        if(!$this->container->auth->admin())
            if($post['user_id'] != $user_id){
                $this->container->flash->addMessage('warning', 'Sem permissão para remover este post.');
                return $response->withRedirect($this->container->router->pathFor('user.posts'));
            }

        if($post){
            $post->delete();
            $this->container->flash->addMessage('success', 'Post removido.');
        } else {
            $this->container->flash->addMessage('danger', 'Post não pode ser removido!');
        }

        return $response->withRedirect($this->container->router->pathFor('home'));

    }
}