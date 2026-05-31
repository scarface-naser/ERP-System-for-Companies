<?php
// =========================================================================
// MIDDLEWARE: DYNAMIC RBAC ROLE-BASED ACCESS CONTROL INTERCEPTOR
// =========================================================================

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Helpers\SessionHelper;

class RBACMiddleware implements MiddlewareInterface {
    /**
     * Intercept and authorize requests based on roles & permissions.
     */
    public function handle(Request $request, Response $response, callable $next, ?string $permissionSlug = null): void {
        SessionHelper::start();

        $userId = SessionHelper::get('user_id');
        $roleId = SessionHelper::get('role_id');

        if (!$userId || !$roleId) {
            if ($request->expectsJson()) {
                $response->json(['error' => 'Unauthorized access.', 'message' => 'Please login to perform this action.'], 401);
            } else {
                $response->redirect('/erpSystem/login?unauthorized=1');
            }
            return;
        }

        // 1. Super Admin (Role ID = 1) gets instant access to all areas
        if ((int)$roleId === 1) {
            $next();
            return;
        }

        // 2. If no specific permission is required, pass through
        if (empty($permissionSlug)) {
            $next();
            return;
        }

        // 3. Query the database to check if this role is granted the requested permission
        try {
            $db = Database::getInstance();
            $sql = "
                SELECT COUNT(*) as count 
                FROM role_permissions rp
                JOIN permissions p ON rp.permission_id = p.id
                WHERE rp.role_id = :role_id AND p.slug = :slug
            ";
            
            $result = $db->fetch($sql, [
                'role_id' => $roleId,
                'slug'    => $permissionSlug
            ]);

            if (isset($result['count']) && (int)$result['count'] > 0) {
                $next();
                return;
            }
        } catch (\Exception $e) {
            error_log("RBAC Middleware Database Error: " . $e->getMessage());
        }

        // 4. Forbidden response if role doesn't have permission
        if ($request->expectsJson()) {
            $response->json([
                'error'     => 'Forbidden.',
                'message'   => "You do not have the required permission ('{$permissionSlug}') to perform this action.",
                'forbidden' => true
            ], 403);
        } else {
            $response->html("
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <title>403 Forbidden</title>
                    <style>
                        body { background: #0f172a; color: #f8fafc; font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
                        .card { background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); padding: 3rem; border-radius: 16px; max-width: 500px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
                        h1 { color: #f87171; margin-top: 0; }
                        p { color: #94a3b8; line-height: 1.6; margin-bottom: 2rem; }
                        .btn { display: inline-block; background: #38bdf8; color: #0f172a; text-decoration: none; padding: 0.7rem 2rem; border-radius: 6px; font-weight: bold; transition: background 0.2s; }
                        .btn:hover { background: #0ea5e9; }
                    </style>
                </head>
                <body>
                    <div class='card'>
                        <h1>403 Access Denied</h1>
                        <p>You do not have administrative clearance to access this module.<br>Required permission: <strong>{$permissionSlug}</strong></p>
                        <a href='/erpSystem/dashboard' class='btn'>Back to Dashboard</a>
                    </div>
                </body>
                </html>
            ", 403);
        }
    }
}
