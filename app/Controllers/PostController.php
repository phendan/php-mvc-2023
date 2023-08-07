<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;
use App\Models\FormValidation;
use App\Models\FileValidation;
use App\Models\Post;
use Exception;

class PostController extends BaseController {
    // Show a single post
    public function index(Request $request)
    {
        $postId = $request->getParams()['id'];

        $post = new Post($this->db);
        if (!$post->find($postId)) {
            $_SESSION['message'] = 'The post you tried to view does not exist.';
            $this->response->redirectTo('/dashboard');
        }

        // $this->response->json(200, $post->toArray());

        $this->response->view('/post/index', [
            'post' => $post
        ]);
    }

    // Get request for create form
    public function show()
    {
        $this->response->view('/post/create');
    }

    // Post request from form
    public function create(Request $request)
    {
        if (!$this->user->isLoggedIn()) {
            $_SESSION['message'] = 'You must be signed in to view this page.';
            $this->response->redirectTo('/login');
        }

        $postData = $request->getInput('post');

        $formValidation = new FormValidation($postData);

        $formValidation->setRules([
            'title' => 'required|min:10|max:64',
            'body' => 'required|min:50'
        ]);

        $formValidation->validate();

        $imageData = $request->getInput('files');

        $fileValidation = new FileValidation($imageData);

        $fileValidation->setRules([
            'image' => 'required|type:image|maxsize:20297152'
        ]);

        $fileValidation->validate();

        if ($formValidation->fails() || $fileValidation->fails()) {
            $this->response->view('post/create', [
                'errors' => array_merge(
                    $formValidation->getErrors(),
                    $fileValidation->getErrors()
                )
            ]);
        }

        try {
            $post = new Post($this->db);
            $post->create(
                $this->user->getId(),
                $postData['title'],
                $postData['body'],
                $imageData['image']
            );
            $this->response->redirectTo("/post/{$post->getId()}");
        } catch (Exception $e) {
            $this->response->view('post/create', [
                'errors' => ['root' => [$e->getMessage()]]
            ]);
        }
    }

    public function list(Request $request)
    {
        //
    }

    public function delete(Request $request)
    {
        // Get post id
        $postId = $request->getParams()['id'];

        if (
            !isset($request->getInput('get')['csrfToken']) ||
            $request->getInput('get')['csrfToken'] !== $_SESSION['csrfToken']
        ) {
            $_SESSION['message'] = 'This request did not seem intentional. Did you mean to delete a post?';
            $this->response->redirectTo('/dashboard');
        }

        $post = new Post($this->db);

        if (!$post->find($postId)) {
            $_SESSION['message'] = 'This post has already been deleted.';
            $this->response->redirectTo('/dashboard');
        }

        if (!$this->user->isLoggedIn() || $this->user->owns($post)) {
            $_SESSION['message'] = 'You do not have permission to delete this post.';
            $this->response->redirectTo('/dashboard');
        }

        if (!$post->delete()) {
            $_SESSION['message'] = 'Something went wrong';
            return $this->response->redirectTo('/dashboard');
        }

        $_SESSION['message'] = 'The post was successfully deleted.';
        return $this->response->redirectTo('/dashboard');
    }

    public function edit(Request $request)
    {
        $postId = $request->getParams()['id'];

        $post = new Post($this->db);
        if (!$post->find($postId)) {
            $_SESSION['message'] = 'The post you tried to edit does not exist.';
            $this->response->redirectTo('/dashboard');
        }

        $this->response->view('/post/edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request)
    {
        $postId = $request->getParams()['id'];
        $post = new Post($this->db);

        if (!$post->find($postId)) {
            $_SESSION['message'] = 'The post you tried to edit does not exist.';
            $this->response->redirectTo('/dashboard');
        }

        if (!$this->user->isLoggedIn() || !$this->user->owns($post)) {
            $_SESSION['message'] = 'You do not have permission to edit this post.';
            $this->response->redirectTo('/dashboard');
        }

        $formInput = $request->getInput();

        $validation = new FormValidation($formInput);

        $validation->setRules([
            'title' => 'required|min:10|max:64',
            'body' => 'required|min:50'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->response->view('post/edit', [
                'post' => $post,
                'errors' => $validation->getErrors()
            ], 422);
        }

        if (!$post->edit($formInput['title'], $formInput['body'])) {
            $this->response->view('posts/edit', [
                'post' => $post,
                'errors' => [
                    'root' => ['Something went wrong while trying to update your post.']
                ]
            ]);
        }

        $_SESSION['message'] = 'Your post has been successfully updated';
        $this->response->redirectTo("/post/{$post->getId()}");
    }
}
