<?php
// =========================================================================
// MIDDLEWARE: AUTHENTICATION INTERCEPTOR
// =========================================================================

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Helpers\SessionHelper;

class AuthMiddleware implements MiddlewareInterface {
    /**
     * Handle the incoming request. Intercepts non-authenticated requests.
     */
    public function handle(Request $request, Response $response, callable $next, ?string $parameter = null): void {
        SessionHelper::start();

        if (SessionHelper::has('user_id')) {
            $next();
            return;
        }

        // Unauthenticated fallback
        if ($request->expectsJson()) {
            $response->json([
                'error'        => 'Unauthorized access.',
                'message'      => 'Authentication is required to access this resource.',
                'unauthorized' => true
            ], 401);
        } else {
            $response->redirect('/erpSystem/login?unauthorized=1');
        }
    }
}
