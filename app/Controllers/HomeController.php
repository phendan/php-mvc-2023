<?php

class HomeController extends BaseController {
    public function index()
    {
        $user = new User($this->db);
        $user->find('philip');
        $this->response->view('home/index');
    }
}
