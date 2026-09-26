<?php

namespace App\Core;

use App\Core\API;

class Router{
    private static array $routes = [];
    private static array $groupStack = [];

    public static function get(string $uri, $action): void { self::addRoute('GET', $uri, $action); }
    public static function post(string $uri, $action): void { self::addRoute('POST', $uri, $action); }
    public static function put(string $uri, $action): void { self::addRoute('PUT', $uri, $action); }
    public static function patch(string $uri, $action): void { self::addRoute('PATCH', $uri, $action); }
    public static function delete(string $uri, $action): void { self::addRoute('DELETE', $uri, $action); }
    public static function any(string $uri, $action): void { self::addRoute('ANY', $uri, $action); }

    public static function view(string $uri, string $templatePath): void {
        self::addRoute('GET', $uri, $templatePath, true);
    }

    public static function group(array $attributes, callable $callback): void {
        self::$groupStack[] = $attributes;
        $callback();
        array_pop(self::$groupStack);
    }

    public static function resource(string $name, string $controller): void {
        $uri = '/' . trim($name, '/');
        
        self::get($uri, [$controller, 'index']);
        self::post($uri, [$controller, 'store']);
        self::get($uri . '/{id}', [$controller, 'show']);
        self::put($uri . '/{id}', [$controller, 'update']);
        self::patch($uri . '/{id}', [$controller, 'update']);
        self::delete($uri . '/{id}', [$controller, 'destroy']);
    }

    private static function addRoute(string $method, string $uri, $action, bool $isView = false): void {
        $prefix = self::getCurrentPrefix();
        $uri = '/' . trim($prefix . '/' . trim($uri, '/'), '/');
        
        self::$routes[] = new Route($method, $uri, $action, $isView);
    }

    private static function getCurrentPrefix(): string
    {
        $prefix = '';
        foreach (self::$groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
        }
        return $prefix;
    }

    public static function dispatch(): void
    {
        $request = new Request();
        $uri = $request->getUri();
        $method = $request->getMethod();

        foreach (self::$routes as $route) {
            if ($route->matches($method, $uri)) {
                self::execute($route, $request);
                return;
            }
        }

        // 404 Not Found
        echo API::notFound("Route '{$uri}' [{$method}] not found");
    }

    private static function execute(Route $route, Request $request): void {
        $action = $route->getAction();
        $params = array_values($route->getParams());
        
        // Always pass Request as the first parameter
        $arguments = array_merge([$request], $params);

        if ($route->isView()) {
            self::renderView($action);
            return;
        }

        if (is_callable($action)) {
            echo call_user_func_array($action, $arguments);
            return;
        }

        if (is_array($action)) {
            [$controller, $method] = $action;
            if (is_string($controller)) {
                $controller = new $controller();
            }
            echo call_user_func_array([$controller, $method], $arguments);
            return;
        }

        // Support String format "Controller@method"
        if (is_string($action) && strpos($action, '@') !== false) {
            [$controllerName, $method] = explode('@', $action);
            $controller = new $controllerName();
            echo call_user_func_array([$controller, $method], $arguments);
            return;
        }

        throw new \Exception("Invalid route action for " . $route->getUri());
    }

    private static function renderView(string $path): void {
        // Assuming base path is root or defined elsewhere
        $fullPath = realpath(__DIR__ . '/../' . $path);
        
        if ($fullPath && file_exists($fullPath)) {
            // Send HTML header if it's a template
            header('Content-Type: text/html');
            include $fullPath;
        } else {
            http_response_code(500);
            echo "Template file not found: " . $path;
        }
    }
}