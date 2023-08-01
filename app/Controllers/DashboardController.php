<?php

namespace App\Controllers;

use App\BaseController;

class DashboardController extends BaseController {
    public function index()
    {
        $this->response->view('dashboard/index');
    }
}
