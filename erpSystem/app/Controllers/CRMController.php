<?php
// =========================================================================
// CONTROLLERS: CRM PIPELINE CONTROLLER
// =========================================================================

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Helpers\SessionHelper;
use Exception;

class CRMController extends Controller {
    /**
     * Display leads pipeline, active opportunities catalog, and client contact interactions.
     */
    public function index(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();

        try {
            $db = Database::getInstance();

            // 1. Fetch leads
            $leads = $db->fetchAll("
                SELECT l.*, e.first_name as employee_first, e.last_name as employee_last 
                FROM leads l
                LEFT JOIN employees e ON l.assigned_to = e.id
                ORDER BY l.created_at DESC
            ");

            // 2. Fetch active opportunities
            $opportunities = $db->fetchAll("
                SELECT o.*, l.first_name as lead_first, l.last_name as lead_last, c.name as customer_name
                FROM opportunities o
                LEFT JOIN leads l ON o.lead_id = l.id
                LEFT JOIN customers c ON o.customer_id = c.id
                ORDER BY o.created_at DESC
            ");

            // 3. Fetch latest interactions log
            $interactions = $db->fetchAll("
                SELECT ci.*, c.name as customer_name, l.first_name as lead_first, l.last_name as lead_last, e.first_name as employee_first
                FROM crm_interactions ci
                LEFT JOIN customers c ON ci.customer_id = c.id
                LEFT JOIN leads l ON ci.lead_id = l.id
                JOIN employees e ON ci.employee_id = e.id
                ORDER BY ci.interaction_date DESC
                LIMIT 10
            ");

            // Fetch employees for lead assignment
            $employees = $db->fetchAll("SELECT id, first_name, last_name FROM employees WHERE status = 'active'");

            // Render view
            $this->render('CRM/index', [
                'title'         => 'Corporate CRM & Leads Pipeline',
                'leads'         => $leads,
                'opportunities' => $opportunities,
                'interactions'  => $interactions,
                'employees'     => $employees
            ]);

        } catch (Exception $e) {
            error_log("CRM Controller Error: " . $e->getMessage());
            $response->html("<h1>CRM System Error</h1><p>{$e->getMessage()}</p>", 500);
        }
    }

    /**
     * Submit new customer lead to pipeline.
     */
    public function addLead(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();

        $firstName = $request->get('first_name');
        $lastName  = $request->get('last_name');
        $company   = $request->get('company_name');
        $email     = $request->get('email');
        $phone     = $request->get('phone');
        $source    = $request->get('source');
        $assignee  = (int)$request->get('assigned_to');

        try {
            if (empty($firstName) || empty($lastName) || empty($email)) {
                throw new Exception("First name, last name, and contact email are required.");
            }

            $db = Database::getInstance();
            $db->query("
                INSERT INTO leads (first_name, last_name, company_name, email, phone, source, status, assigned_to) 
                VALUES (:first, :last, :company, :email, :phone, :source, 'new', :assigned_to)
            ", [
                'first'       => $firstName,
                'last'        => $lastName,
                'company'     => $company,
                'email'       => $email,
                'phone'       => $phone,
                'source'      => $source,
                'assigned_to' => $assignee > 0 ? $assignee : null
            ]);

            $response->json(['status' => 'success', 'message' => 'New lead recorded successfully. Assigned to pipeline.']);

        } catch (Exception $e) {
            $response->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
