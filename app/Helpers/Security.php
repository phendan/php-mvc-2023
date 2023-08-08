<?php

namespace App\Helpers;

use App\Helpers\Str;
use App\Helpers\Session;

class Security {
    public static function csrfToken(): string
    {
        $csrfToken = Str::token();
        Session::set('csrfToken', $csrfToken);

        return $csrfToken;
    }
}
