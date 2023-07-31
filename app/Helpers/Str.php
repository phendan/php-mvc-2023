<?php

class Str {
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
