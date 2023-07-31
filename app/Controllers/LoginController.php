<?php

class LoginController extends BaseController {
    public function index()
    {
        $this->response->view('login/index');
    }

    public function create(Request $request)
    {
        $formInput = $request->getInput();

        $validation = new FormValidation($formInput);

        $validation->setRules([
            'username' => 'required|min:3|max:64',
            'password' => 'required|min:6'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->response->view('login/index', [
                'errors' => $validation->getErrors()
            ]);
        }

        try {
            $user = new User($this->db);
            $user->login($formInput['username'], $formInput['password']);
            $this->response->redirectTo('/dashboard');
        } catch (Exception $e) {
            $this->response->view('login/index', [
                'errors' => [
                    'root' => [$e->getMessage()]
                ]
            ]);
        }
    }
}
