<?php

namespace App\Controllers;

use App\BaseController;

class HomeController extends BaseController {
    public function index()
    {
        $this->response->view('home/index');
    }
}
