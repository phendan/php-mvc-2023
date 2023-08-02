<?php

namespace App\Helpers;

use App\Helpers\Str;

class Security {
    public static function csrfToken(): string
    {
        $csrfToken = Str::token();
        $_SESSION['csrfToken'] = $csrfToken;

        return $csrfToken;
    }
}
