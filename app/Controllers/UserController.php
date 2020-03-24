<?php

namespace App\Controllers;

use App\Models\Post;
use Slim\Http\UploadedFile;

class UserController extends Controller
{
    public function avatar($request, $response)
    {
        if($request->isGet())
            return $this->container->view->render($response, 'user/avatar.twig');

        $directory = $this->container->upload_directory;
        $avatar = $request->getUploadedFiles()['avatar'];

        if(!$avatar->getError()) {
            $filename = $this->moveUploadFile($directory, $avatar);

            $user = $this->container->auth->user();
            $user->avatar = $filename;
            $user->save();

            $this->container->flash->addMessage('success', 'Avatar enviado com sucesso.');
        } else {
            $this->container->flash->addMessage('error', 'Houve um erro a enviar o avatar.');
        }

        return $response->withRedirect($this->container->router->pathFor('user.avatar'));
    }

    public function posts($request, $response)
    {
        $user_id = $this->container->auth->user()->id;

        $data = [
            'posts' => Post::where('user_id', $user_id)->orderBy('created_at', 'DESC')->get(),      
        ];

        return $this->container->view->render($response, 'user/posts.twig', $data);
    }

    private function moveUploadFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}