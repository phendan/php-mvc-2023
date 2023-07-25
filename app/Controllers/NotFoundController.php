<?php

class NotFoundController {
    public function index()
    {
        $response = new Response;
        $response->view(statusCode: 404, path: '404');
    }
}
