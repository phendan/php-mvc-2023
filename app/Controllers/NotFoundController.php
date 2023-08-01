<?php

namespace App\Controllers;

use App\BaseController;

class NotFoundController extends BaseController {
    public function index()
    {
        $this->response->view(statusCode: 404, path: '404');
    }
}
