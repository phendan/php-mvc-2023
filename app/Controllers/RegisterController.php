<?php

class RegisterController {
    // GET Request
    public function index(Request $request)
    {
        $response = new Response;
        $response->view('register/index');
    }

    // POST Request
    public function create(Request $request)
    {
        $formInput = $request->getInput();

        $validation = new FormValidation($formInput);

        $validation->setRules([
            'username' => 'required|min:3|max:64',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'passwordAgain' => 'required|matches:password'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $response = new Response;
            $response->view(
                path: 'register/index',
                data: [
                    'title' => 'Register',
                    'errors' => $validation->getErrors()
                ],
                statusCode: 422
            );
        }

        // User Registrierung
    }
}
