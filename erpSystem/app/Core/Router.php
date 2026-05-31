<?php
// =========================================================================
// CUSTOM FRAMEWORK CORE: ROUTER & MIDDLEWARE RUNTIME CLASS
// =========================================================================

namespace App\Core;

class Router {
    private array $routes = [];
    private array $middleware = [];

    /**
     * Map a GET route.
     */
    public function get(string $path, $handler, array $middleware = []): void {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    /**
     * Map a POST route.
     */
    public function post(string $path, $handler, array $middleware = []): void {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    /**
     * Map a PUT route.
     */
    public function put(string $path, $handler, array $middleware = []): void {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    /**
     * Map a DELETE route.
     */
    public function delete(string $path, $handler, array $middleware = []): void {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    /**
     * Register global or route-specific middleware mapping.
     */
    public function registerMiddleware(string $name, string $middlewareClass): void {
        $this->middleware[$name] = $middlewareClass;
    }

    /**
     * Internal utility to register a route mapping.
     */
    private function addRoute(string $method, string $path, $handler, array $middleware): void {
        // Convert route path to matching regex pattern
        // e.g., "/users/{id}" -> "#^/users/(?P<id>[^/]+)$#s"
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#s';

        $this->routes[] = [
            'method'     => $method,
            'path'       => $path,
            'pattern'    => $pattern,
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }

    /**
     * Match current Request and execute handler with injected Middlewares.
     */
    public function dispatch(Request $request, Response $response): void {
        $reqMethod = $request->getMethod();
        $reqUri    = $request->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] === $reqMethod && preg_match($route['pattern'], $reqUri, $matches)) {
                // Extract route parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Execute middleware chain
                $this->executeMiddleware($route['middleware'], $request, $response, function() use ($route, $request, $response, $params) {
                    $this->invokeHandler($route['handler'], $request, $response, $params);
                });
                return;
            }
        }

        // Route not found fallback
        if ($request->expectsJson()) {
            $response->json(['error' => 'API endpoint not found', 'code' => 404], 404);
        } else {
            $response->html("<h1>404 Not Found</h1><p>The requested path '{$reqUri}' does not exist on this ERP platform.</p>", 404);
        }
    }

    /**
     * Execute registered route middleware chain sequentially.
     */
    private function executeMiddleware(array $middlewares, Request $request, Response $response, callable $next): void {
        if (empty($middlewares)) {
            $next();
            return;
        }

        $pipeline = $next;

        // Iterate backwards to wrap each middleware inside the next pipeline call
        for ($i = count($middlewares) - 1; $i >= 0; $i--) {
            $middlewareString = $middlewares[$i];
            $parts = explode(':', $middlewareString, 2);
            $middlewareName = $parts[0];
            $parameter = $parts[1] ?? null;

            if (!isset($this->middleware[$middlewareName])) {
                throw new \Exception("Registered middleware name '{$middlewareName}' does not exist.");
            }

            $class = $this->middleware[$middlewareName];
            $instance = new $class();

            // The inner function acts as the $next callable parameter
            $currentPipeline = $pipeline;
            $pipeline = function() use ($instance, $request, $response, $currentPipeline, $parameter) {
                $instance->handle($request, $response, $currentPipeline, $parameter);
            };
        }

        $pipeline();
    }

    /**
     * Invoke the matched controller action.
     */
    private function invokeHandler($handler, Request $request, Response $response, array $params): void {
        if (is_callable($handler)) {
            call_user_func_array($handler, [$request, $response, $params]);
            return;
        }

        if (is_string($handler) && str_contains($handler, '@')) {
            list($controllerName, $actionName) = explode('@', $handler);
            $fullControllerClass = "\\App\\Controllers\\" . $controllerName;

            if (!class_exists($fullControllerClass)) {
                throw new \Exception("Controller class '{$fullControllerClass}' not found.");
            }

            $controller = new $fullControllerClass();
            if (!method_exists($controller, $actionName)) {
                throw new \Exception("Action '{$actionName}' not found in controller '{$fullControllerClass}'.");
            }

            // Call the controller action, injecting Request, Response, and URL parameters
            call_user_func_array([$controller, $actionName], [$request, $response, $params]);
            return;
        }

        throw new \Exception("Invalid route handler defined.");
    }
}
