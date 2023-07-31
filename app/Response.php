<?php

class Response {
    public function view(string $path, array $data = [], int $statusCode = 200)
    {
        http_response_code($statusCode);

        extract($data);

        require_once path(__DIR__ . '/../views/partials/header.php');
        require_once path(__DIR__ . "/../views/pages/{$path}.php");
        require_once path(__DIR__ . '/../views/partials/footer.php');

        exit();
    }

    public function redirectTo(string $path)
    {
        header('Location: ' . $path);
        exit();
    }
}
