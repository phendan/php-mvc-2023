<?php

namespace App;

use App\Models\Database;
use App\Models\User;

class BaseController {
    protected Database $db;
    protected User $user;
    protected Response $response;

    public function __construct()
    {
        $this->db = new Database;

        $this->user = new User($this->db);
        if ($this->user->isLoggedIn()) $this->user->find();

        $this->response = new Response($this->user);
    }
}
