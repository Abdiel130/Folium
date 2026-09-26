<?php

namespace App\Core;

/**
 * Standardized Request object to hold all incoming data.
 */
class Request
{
    private string $method;
    private string $uri;
    private array $queryParams;
    private array $body;
    private array $headers;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->queryParams = $_GET;
        $this->headers = $this->extractHeaders();
        $this->body = $this->extractBody();

        // Handle Laravel style _method override
        if ($this->method === 'POST' && isset($this->body['_method'])) {
            $this->method = strtoupper($this->body['_method']);
        }
    }

    private function extractHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }

    private function extractBody(): array
    {
        $body = [];
        
        // Handle JSON
        $input = file_get_contents('php://input');
        $decoded = json_decode($input, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            $body = $decoded;
        } else {
            // Fallback to $_POST for form-data
            $body = $_POST;
        }

        return $body;
    }

    public function getMethod(): string { return $this->method; }
    public function getUri(): string { return $this->uri; }
    public function query(?string $key = null, $default = null) 
    {
        if ($key === null) return $this->queryParams;
        return $this->queryParams[$key] ?? $default;
    }

    public function input(?string $key = null, $default = null)
    {
        if ($key === null) return $this->body;
        return $this->body[$key] ?? $default;
    }

    public function header(?string $key = null, $default = null)
    {
        if ($key === null) return $this->headers;
        return $this->headers[$key] ?? $default;
    }

    /**
     * Helper to get all data (query + body)
     */
    public function all(): array
    {
        return array_merge($this->queryParams, $this->body);
    }
}
