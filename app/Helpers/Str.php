<?php

namespace App\Helpers;

class Str {
    public static function token(): string
    {
        return bin2hex(random_bytes(16));
    }

    public static function toCamelCase(string $subject): string
    {
        $words = explode('_', $subject);
        // $words = array_map(fn ($word) => ucfirst($word), $words);
        $words = array_map(function ($word) {
            return ucfirst($word);
        }, $words);
        $subject = lcfirst(implode('', $words));

        return $subject;
    }
}
