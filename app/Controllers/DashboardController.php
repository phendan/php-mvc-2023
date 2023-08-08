<?php

namespace App\Controllers;

use App\BaseController;
use App\Traits\HasProtectedRoutes;

class DashboardController extends BaseController {
    use HasProtectedRoutes;

    public function index()
    {
        $this->redirectAnonymousUsers();

        $posts = $this->user->getPosts();

        $this->response->view('dashboard/index', [
            'posts' => $posts
        ]);
    }
}
