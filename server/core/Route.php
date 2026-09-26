<?php

namespace App\Core;

/**
 * Represents a single Route in the system.
 */
class Route
{
    private string $method;
    private string $uri;
    private $action;
    private array $params = [];
    private bool $isView = false;

    public function __construct(string $method, string $uri, $action, bool $isView = false)
    {
        $this->method = strtoupper($method);
        $this->uri = $this->normalizeUri($uri);
        $this->action = $action;
        $this->isView = $isView;
    }

    /**
     * Normalizes the URI by adding leading slash and removing trailing slash.
     */
    private function normalizeUri(string $uri): string
    {
        $uri = '/' . trim($uri, '/');
        return $uri === '/' ? $uri : rtrim($uri, '/');
    }

    /**
     * Checks if this route matches the given method and URI.
     */
    public function matches(string $method, string $uri): bool
    {
        if ($this->method !== strtoupper($method) && $this->method !== 'ANY') {
            return false;
        }

        $uri = $this->normalizeUri($uri);
        
        // Convert route pattern to regex
        // Example: /users/:id -> ^/users/([^/]+)$
        $pattern = preg_replace('/:([a-zA-Z0-9_]+)/', '(?P<$1>[^/]+)', $this->uri);
        // Also support {id} format
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $uri, $matches)) {
            $this->params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }

    public function getMethod(): string { return $this->method; }
    public function getUri(): string { return $this->uri; }
    public function getAction() { return $this->action; }
    public function getParams(): array { return $this->params; }
    public function isView(): bool { return $this->isView; }
}
