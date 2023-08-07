<?php

namespace App\Controllers;

use App\BaseController;

class DashboardController extends BaseController {
    public function index()
    {
        if (!$this->user->isLoggedIn()) {
            $_SESSION['message'] = 'You must be logged in to view this page.';
            $this->response->redirectTo('/login');
        }

        $posts = $this->user->getPosts();

        $this->response->view('dashboard/index', [
            'posts' => $posts
        ]);
    }
}
