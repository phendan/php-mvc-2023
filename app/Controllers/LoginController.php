<?php

class LoginController {
    public function index()
    {
        $response = new Response;
        $response->view('login');
    }
}
