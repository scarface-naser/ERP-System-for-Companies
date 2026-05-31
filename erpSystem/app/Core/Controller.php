<?php
// =========================================================================
// CUSTOM FRAMEWORK CORE: BASE CONTROLLER CLASS
// =========================================================================

namespace App\Core;

class Controller {
    /**
     * Render an HTML view file safely.
     * Integrates layout wrapping if needed, and injects template variables.
     */
    protected function render(string $viewPath, array $data = []): void {
        $viewFile = dirname(__DIR__, 2) . '/app/Views/' . $viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("Template view file not found at: {$viewFile}");
        }

        // Extract variables to be accessible within the view template
        extract($data);

        // Capture output buffer to allow full flexibility
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Render standard master template wrapper if available
        $layoutFile = dirname(__DIR__, 2) . '/app/Views/layouts/master.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
        exit;
    }

    /**
     * Helper to return standard API output JSON formats.
     */
    protected function jsonResponse(Response $response, array $payload, int $status = 200): void {
        $response->json($payload, $status);
    }
}
