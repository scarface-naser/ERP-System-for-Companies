<?php
// =========================================================================
// CUSTOM FRAMEWORK CORE: RESPONSE WRAPPER CLASS
// =========================================================================

namespace App\Core;

class Response {
    /**
     * Send raw string or HTML response.
     */
    public function html(string $content, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=utf-8');
        echo $content;
        exit;
    }

    /**
     * Send JSON response. Ideal for REST APIs or AJAX calls.
     */
    public function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        // Add security headers (OWASP recommended)
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Perform HTTP redirect.
     */
    public function redirect(string $url, int $statusCode = 302): void {
        http_response_code($statusCode);
        header("Location: " . $url);
        exit;
    }

    /**
     * Output error response.
     */
    public function error(string $message, int $statusCode = 500, bool $json = false): void {
        if ($json) {
            $this->json(['error' => $message, 'status' => $statusCode], $statusCode);
        } else {
            $this->html("<h1>Error {$statusCode}</h1><p>{$message}</p>", $statusCode);
        }
    }
}
