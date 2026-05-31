<?php
// =========================================================================
// CONTROLLERS: DYNAMIC DASHBOARD COMMAND CONTROLLER
// =========================================================================

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Helpers\SessionHelper;

class DashboardController extends Controller {
    /**
     * Display corporate command desk dashboard populated with dynamic records.
     */
    public function index(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();

        try {
            $db = Database::getInstance();

            // 1. Fetch Dynamic ERP Performance Metrics
            $empStats = $db->fetch("SELECT COUNT(*) as count, SUM(base_salary) as salary_sum FROM employees");
            $employeesCount   = $empStats['count'] ?? 0;
            $payrollLiability = $empStats['salary_sum'] ?? 0.00;

            $stockStats = $db->fetch("
                SELECT SUM(p.cost * ws.quantity) as value 
                FROM products p 
                JOIN warehouse_stock ws ON p.id = ws.product_id
            ");
            $stockValuation = $stockStats['value'] ?? 0.00;

            // 2. Fetch Latest 5 Audit Logs with User Details
            $activityLogs = $db->fetchAll("
                SELECT al.*, u.username 
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC 
                LIMIT 5
            ");

            // 3. Render Dashboard View Template
            $this->render('Dashboard/index', [
                'title'            => 'Enterprise ERP Command Desk',
                'employeesCount'   => $employeesCount,
                'payrollLiability' => $payrollLiability,
                'stockValuation'   => $stockValuation,
                'activityLogs'     => $activityLogs
            ]);

        } catch (\Exception $e) {
            error_log("Dashboard Controller Diagnostic Failure: " . $e->getMessage());
            $response->html("
                <div style='font-family: system-ui, sans-serif; padding: 2rem; max-width: 600px; margin: 4rem auto; background: #fff5f5; border: 1px solid #feb2b2; border-radius: 8px;'>
                    <h1 style='color: #c53030; margin-top: 0;'>Dashboard Boot Failed</h1>
                    <p style='color: #2d3748;'>The custom database core failed to initialize dashboard metrics.</p>
                    <div style='background: #fff; padding: 1rem; border-radius: 4px; border: 1px solid #e2e8f0; font-family: monospace;'>{$e->getMessage()}</div>
                </div>
            ", 500);
        }
    }
}
