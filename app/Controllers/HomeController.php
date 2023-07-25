<?php

class HomeController {
    public function index()
    {
        $response = new Response;
        $response->view('home/index');
    }
}
