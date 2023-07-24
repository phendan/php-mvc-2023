<?php

// Display all errors
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

// Constructs valid paths for the current operating system
function path(string $path) {
    return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}

// Dump and die
function dd(...$values) {
    echo '<pre>', var_dump(...$values), '</pre>';
    die();
}

require_once path(__DIR__ . '/../app/App.php');

$app = new App;
