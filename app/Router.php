<?php

namespace App;

class Router
{
    private $routes = [];

    public function get($uri, $handler, $needAuth = false)
    {
        $this->addRoute('GET', $uri, $handler, $needAuth);
    }

    public function post($uri, $handler, $needAuth = false)
    {
        $this->addRoute('POST', $uri, $handler, $needAuth);
    }

    private function addRoute($method, $uri, $handler, $needAuth)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $this->normalizeUri($uri),
            'handler' => $handler,
            'needAuth' => $needAuth
        ];
    }

    public function handleRequest($uri, $method)
    {
        $normalizedUri = $this->normalizeUri($uri);

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $normalizedUri, $method)) {
                if ($route['needAuth'] && !isset($_SESSION['user_id'])) {
                    http_response_code(302);
                    header('Location: /login');
                    exit;
                }
                $this->dispatch($route['handler'], $this->extractParams($route['uri'], $normalizedUri));
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    private function normalizeUri($uri)
    {
        return trim($uri, '/');
    }

    private function matchRoute($route, $uri, $method)
    {
        if ($route['method'] !== $method) {
            return false;
        }

        $routeParts = explode('/', $route['uri']);
        $uriParts = explode('/', $uri);

        if (count($routeParts) !== count($uriParts)) {
            return false;
        }

        foreach ($routeParts as $index => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                continue; // Dynamic parameter
            }
            if ($part !== $uriParts[$index]) {
                return false;
            }
        }

        return true;
    }

    private function extractParams($routeUri, $requestUri)
    {
        $routeParts = explode('/', $routeUri);
        $uriParts = explode('/', $requestUri);

        $params = [];
        foreach ($routeParts as $index => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                $paramName = trim($part, '{}');
                $params[$paramName] = $uriParts[$index];
            }
        }

        return $params;
    }

    private function dispatch($handler, $params)
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_array($handler)) {
            list($controller, $method) = $handler;
            $controllerInstance = new $controller();
            call_user_func_array([$controllerInstance, $method], $params);
        }
    }
}
