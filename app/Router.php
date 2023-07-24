<?php

class Router {
    private array $routes = [];

    private string $controller = NotFoundController::class;
    private string $method = 'index';
    private array $params = [];

    public function handleRequest(Request $request)
    {
        $requestUrl = $request->getUrl();
        $requestMethod = $request->getMethod();

        // Check all routes
        foreach ($this->routes[$requestMethod] as $routeUrl => $requestHandler) {
            $routeUrlSegments = explode('/', trim($routeUrl, '/'));
            $requestUrlSegments = explode('/', trim($requestUrl, '/'));

            if (count($routeUrlSegments) !== count($requestUrlSegments)) {
                continue;
            }

            $pageParams = [];

            // Compare url segments
            foreach ($routeUrlSegments as $index => $routeUrlSegment) {
                $requestUrlSegment = $requestUrlSegments[$index];

                // If the segment is a dynamic page parameter
                if (str_starts_with($routeUrlSegment, ':')) {
                    $paramName = trim($routeUrlSegment, ':');
                    $pageParams[$paramName] = $requestUrlSegment;
                    continue 1;
                }

                // If the segment isn't dynamic and is different from the request
                // this cannot be the correct route
                if ($routeUrlSegment !== $requestUrlSegment) {
                    // Skip the outer loop to the next route
                    continue 2;
                }
            }

            // If we reach here, we know the current route was correct
            $controller = $requestHandler[0];
            $method = $requestHandler[1];

            $this->controller = $controller;
            $this->method = $method;
            $this->params = $pageParams;
        }
    }

    public function getRequestedController(): string
    {
        return $this->controller;
    }

    public function getRequestedMethod(): string
    {
        return $this->method;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function get(string $route, array $requestHandler)
    {
        $this->routes['GET'][$route] = $requestHandler;
    }

    public function post(string $route, array $requestHandler)
    {
        $this->routes['POST'][$route] = $requestHandler;
    }
}
