<?php
// =========================================================================
// CUSTOM FRAMEWORK CORE: REQUEST WRAPPER CLASS
// =========================================================================

namespace App\Core;

class Request {
    private array $params = [];
    private string $method;
    private string $uri;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri    = $this->parseUri();
        $this->bootstrapParams();
    }

    /**
     * Get the current HTTP request method (GET, POST, etc.)
     */
    public function getMethod(): string {
        return strtoupper($this->method);
    }

    /**
     * Get the sanitized request URI.
     */
    public function getUri(): string {
        return $this->uri;
    }

    /**
     * Determine if request is an AJAX/API call expecting JSON response.
     */
    public function expectsJson(): bool {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return str_contains($accept, 'application/json') || str_starts_with($this->uri, '/api');
    }

    /**
     * Retrieve all input parameters (sanitized).
     */
    public function all(): array {
        return $this->params;
    }

    /**
     * Retrieve a specific input parameter (sanitized), falling back to a default value.
     */
    public function get(string $key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    /**
     * Validate if specific keys exist in the input payload.
     */
    public function has(array $keys): bool {
        foreach ($keys as $key) {
            if (!isset($this->params[$key])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Parse and sanitize the current URL path.
     */
    private function parseUri(): string {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        // Strip query string if present
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        // Handle sub-directory environments (e.g. /erpSystem/public/)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath   = dirname($scriptName);
        
        // Normalize slashes
        $basePath = str_replace('\\', '/', $basePath);
        $uri = str_replace('\\', '/', $uri);

        if ($basePath !== '/' && $basePath !== '') {
            if (str_starts_with($uri, $basePath)) {
                $uri = substr($uri, strlen($basePath));
            } else {
                // Try stripping the parent directory (e.g. /erpSystem)
                $parentBase = dirname($basePath);
                $parentBase = str_replace('\\', '/', $parentBase);
                if ($parentBase !== '/' && $parentBase !== '' && str_starts_with($uri, $parentBase)) {
                    $uri = substr($uri, strlen($parentBase));
                }
            }
        }

        // Ensure leading slash and remove trailing slash for clean matching
        $uri = '/' . trim($uri, '/');
        return $uri;
    }

    /**
     * Populate and sanitize GET, POST, and raw JSON input bodies.
     */
    private function bootstrapParams(): void {
        // 1. Sanitize GET parameters
        foreach ($_GET as $key => $value) {
            $this->params[$key] = $this->sanitize($value);
        }

        // 2. Sanitize POST parameters
        foreach ($_POST as $key => $value) {
            $this->params[$key] = $this->sanitize($value);
        }

        // 3. Process raw JSON bodies (for advanced REST API requests)
        if ($this->getMethod() === 'POST' || $this->getMethod() === 'PUT' || $this->getMethod() === 'DELETE') {
            $rawBody = file_get_contents('php://input');
            if (!empty($rawBody)) {
                $json = json_decode($rawBody, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                    foreach ($json as $key => $value) {
                        $this->params[$key] = $this->sanitize($value);
                    }
                }
            }
        }
    }

    /**
     * Clean strings from XSS payloads.
     */
    private function sanitize($value) {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->sanitize($v);
            }
            return $value;
        }

        if (is_string($value)) {
            // Apply standard output protection and strip basic tags
            return htmlspecialchars(trim(strip_tags($value)), ENT_QUOTES, 'UTF-8');
        }

        return $value;
    }
}
