<?php

class DashboardController extends BaseController {
    public function index()
    {
        $this->response->view('dashboard/index');
    }
}
