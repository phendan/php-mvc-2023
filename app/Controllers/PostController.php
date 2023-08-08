<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;
use App\Models\FormValidation;
use App\Models\FileValidation;
use App\Models\Post;
use App\Models\User;
use App\Helpers\Session;
use App\Helpers\Exception;
use App\Traits\HasProtectedRoutes;

class PostController extends BaseController {
    use HasProtectedRoutes;

    // Show a single post
    public function index(Request $request)
    {
        $postId = $request->getParams()['id'];

        $post = new Post($this->db);
        if (!$post->find($postId)) {
            Session::flash('error', 'The post you tried to view does not exist.');
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
        $this->redirectAnonymousUsers();
        $this->response->view('/post/create');
    }

    // Post request from form
    public function create(Request $request)
    {
        $this->redirectAnonymousUsers();

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
                'errors' => $e->getData()
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

        $this->redirectUnintendedAccess($request, '/dashboard');

        $post = new Post($this->db);

        if (!$post->find($postId)) {
            Session::flash('error', 'This post has already been deleted.');
            $this->response->redirectTo('/dashboard');
        }

        $this->redirectAnonymousUsers();

        if ($this->user->owns($post)) {
            Session::flash('error', 'You do not have permission to delete this post.');
            $this->response->redirectTo('/dashboard');
        }

        if (!$post->delete()) {
            Session::flash('error', 'Something went wrong');
            return $this->response->redirectTo('/dashboard');
        }

        Session::flash('success', 'The post was successfully deleted.');
        return $this->response->redirectTo('/dashboard');
    }

    public function edit(Request $request)
    {
        $postId = $request->getParams()['id'];

        $post = new Post($this->db);
        if (!$post->find($postId)) {
            Session::flash('error', 'The post you tried to edit does not exist.');
            $this->response->redirectTo('/dashboard');
        }

        $this->redirectAnonymousUsers();

        $this->response->view('/post/edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request)
    {
        $postId = $request->getParams()['id'];
        $post = new Post($this->db);

        if (!$post->find($postId)) {
            Session::flash('error', 'The post you tried to edit does not exist.');
            $this->response->redirectTo('/dashboard');
        }

        $this->redirectAnonymousUsers();

        if (!$this->user->owns($post)) {
            Session::flash('error', 'You do not have permission to edit this post.');
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

        Session::flash('error', 'Your post has been successfully updated');
        $this->response->redirectTo("/post/{$post->getId()}");
    }

    public function like(Request $request)
    {
        $postId = $request->getParams()['id'];
        $post = new Post($this->db);

        if (!$post->find($postId)) {
            Session::flash('error', 'The post you tried to edit does not exist.');
            $this->response->redirectTo('/dashboard');
        }

        $this->redirectUnintendedAccess($request);
        $this->redirectAnonymousUsers();

        if (!$post->like($this->user->getId())) {
            Session::flash('error', "You've already liked this post.");
        }

        $this->response->redirectTo("/post/{$post->getId()}");
    }

    public function dislike(Request $request)
    {
        $postId = $request->getParams()['id'];
        $post = new Post($this->db);

        if (!$post->find($postId)) {
            Session::flash('error', 'The post you tried to edit does not exist.');
            $this->response->redirectTo('/dashboard');
        }

        $this->redirectUnintendedAccess($request);
        $this->redirectAnonymousUsers();

        $post->dislike($this->user->getId());
        $this->response->redirectTo("/post/{$post->getId()}");
    }
}
