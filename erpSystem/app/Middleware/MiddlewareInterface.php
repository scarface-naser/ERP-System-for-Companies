<?php
// =========================================================================
// CUSTOM FRAMEWORK CORE: MIDDLEWARE INTERFACE
// =========================================================================

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

interface MiddlewareInterface {
    /**
     * Handle the incoming request and pass to the next stage in the pipeline.
     * Optionally accepts a dynamic parameter (e.g. permission slugs).
     */
    public function handle(Request $request, Response $response, callable $next, ?string $parameter = null): void;
}
