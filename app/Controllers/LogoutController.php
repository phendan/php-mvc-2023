<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\BaseController;

class LogoutController extends BaseController {
    public function index()
    {
        $this->user->logout();
        Session::flash('success', "You've been successfully logged out.");
        $this->response->redirectTo('/login');
    }
}
