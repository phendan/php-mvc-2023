<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;
use App\Models\FormValidation;
use App\Models\User;
use App\Helpers\Exception;
use App\Traits\HasProtectedRoutes;

class LoginController extends BaseController {
    use HasProtectedRoutes;

    public function index()
    {
        $this->redirectAuthenticatedUsers();

        $this->response->view('login/index');
    }

    public function create(Request $request)
    {
        $this->redirectAuthenticatedUsers();

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
                'errors' => $e->getData()
            ]);
        }
    }
}
