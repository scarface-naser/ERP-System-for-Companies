<?php
// =========================================================================
// ENTERPRISE ERP SYSTEM - FRONT CONTROLLER ENTRY POINT
// =========================================================================

// 1. Enable secure error reporting (Log to file, suppress screen errors in production)
error_reporting(E_ALL);
ini_set('display_errors', '1'); // Set to '0' in production
ini_set('log_errors', '1');
ini_set('error_log', dirname(__DIR__) . '/logs/error.log');

// 2. Register Custom PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Not in App namespace
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 3. Initialize Secure Session Handler
\App\Helpers\SessionHelper::start();

// 4. Instantiate Core Components
$request  = new \App\Core\Request();
$response = new \App\Core\Response();
$router   = new \App\Core\Router();

// 5. Register Middleware Definitions
$router->registerMiddleware('auth', \App\Middleware\AuthMiddleware::class);
$router->registerMiddleware('rbac', \App\Middleware\RBACMiddleware::class);

// 6. Load Web and API Routes
require_once dirname(__DIR__) . '/routes/web.php';
require_once dirname(__DIR__) . '/routes/api.php';

// 7. Dispatch the HTTP Request through Router
try {
    $router->dispatch($request, $response);
} catch (\Exception $e) {
    // Log exception details
    error_log("Unhandled Application Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    
    // Output error response
    if ($request->expectsJson()) {
        $response->json([
            'error'   => 'An unexpected server error occurred.',
            'message' => $e->getMessage()
        ], 500);
    } else {
        $response->html("
            <div style='font-family: system-ui, -apple-system, sans-serif; padding: 2rem; max-width: 600px; margin: 4rem auto; background: #fff5f5; border: 1px solid #feb2b2; border-radius: 8px;'>
                <h1 style='color: #c53030; margin-top: 0;'>Application Error</h1>
                <p style='color: #2d3748; line-height: 1.6;'>An unhandled exception occurred in the system engine.</p>
                <div style='background: #fff; padding: 1rem; border-radius: 4px; border: 1px solid #e2e8f0; font-family: monospace; white-space: pre-wrap; font-size: 0.875rem;'>{$e->getMessage()}</div>
            </div>
        ", 500);
    }
}
