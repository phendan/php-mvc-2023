<?php

namespace App;

use App\Router;
use App\Request;

use App\Controllers\{
    HomeController,
    DashboardController,
    LoginController,
    LogoutController,
    PostController,
    RegisterController
};

class App {
    public function __construct()
    {
        // $this->autoloadClasses();

        $router = new Router;
        $this->defineRoutes($router);

        $request = new Request;
        $router->handleRequest($request);

        $requestedController = $router->getRequestedController();
        $requestedMethod = $router->getRequestedMethod();
        $pageParams = $router->getParams();

        $request->setParams($pageParams);

        $controller = new $requestedController;
        $controller->{$requestedMethod}($request);
    }

    private function defineRoutes(Router $router)
    {
        $router->get('/', [HomeController::class, 'index']);

        $router->get('/login', [LoginController::class, 'index']);
        $router->post('/login', [LoginController::class, 'create']);

        $router->get('/register', [RegisterController::class, 'index']);
        $router->post('/register', [RegisterController::class, 'create']);

        $router->get('/logout', [LogoutController::class, 'index']);

        $router->get('/dashboard', [DashboardController::class, 'index']);

        $router->get('/post/create', [PostController::class, 'show']);
        $router->post('/post/create', [PostController::class, 'create']);

        $router->get('/post/:id', [PostController::class, 'index']);

        $router->get('/post/edit/:id', [PostController::class, 'edit']);
        $router->post('/post/edit/:id', [PostController::class, 'update']);

        $router->get('/post/delete/:id', [PostController::class, 'delete']);

        $router->get('/post/like/:id', [PostController::class, 'like']);
        $router->get('/post/dislike/:id', [PostController::class, 'dislike']);

        $router->get('/posts', [PostController::class, 'list']);
    }

    private function autoloadClasses()
    {
        spl_autoload_register(function (string $namespace) {
            $projectNamespace = 'App\\';
            $className = str_replace($projectNamespace, '', $namespace);
            $filePath = path(__DIR__ . '/' . $className . '.php');

            if (file_exists($filePath)) {
                require_once $filePath;
            }
        });
    }
}
