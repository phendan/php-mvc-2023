<?php

class RegisterController extends BaseController {
    // GET Request
    public function index(Request $request)
    {
        $this->response->view('register/index');
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
            $this->response->view(
                path: 'register/index',
                data: [
                    'title' => 'Register',
                    'errors' => $validation->getErrors()
                ],
                statusCode: 422
            );
        }

        // User Registrierung
        $user = new User($this->db);

        try {
            $user->register(
                $formInput['username'],
                $formInput['email'],
                $formInput['password']
            );
            $this->response->redirectTo('/login');
        } catch (Exception $e) {
            $this->response->view('register/index', [
                'errors' => [
                    'root' => [$e->getMessage()]
                ]
            ]);
        }
    }
}
