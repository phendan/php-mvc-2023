<?php

class PostController extends BaseController {
    // Show a single post
    public function index()
    {
        //
    }

    // Get request for create form
    public function show()
    {
        $this->response->view('/post/create');
    }

    // Post request from form
    public function create(Request $request)
    {
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

        dd($fileValidation);
        //
    }
}
