<?php
// =========================================================================
// CONTROLLERS: HR OFFICE EXECUTIVE CONTROLLER
// =========================================================================

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Models\Employee;
use App\Services\HRService;
use App\Helpers\SessionHelper;
use App\Helpers\Sanitizer;
use Exception;

class HRController extends Controller {
    /**
     * Show the main HR Desk interface loaded with dynamic employee matrices and check-in panels.
     */
    public function index(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        $userId = SessionHelper::get('user_id');

        try {
            $db = Database::getInstance();

            // 1. Fetch Logged-in Employee Profile (to determine Clock-in state)
            $employee = Employee::getByUserId($userId);
            $clockState = ['checked_in' => false, 'checked_out' => false, 'time' => null];

            if ($employee) {
                $todayAttendance = $db->fetch("
                    SELECT * FROM attendance 
                    WHERE employee_id = :emp_id AND date = CURDATE()
                ", ['emp_id' => $employee['id']]);

                if ($todayAttendance) {
                    $clockState['checked_in'] = true;
                    $clockState['time']       = $todayAttendance['check_in'];
                    if ($todayAttendance['check_out'] !== null) {
                        $clockState['checked_out'] = true;
                        $clockState['time']        = $todayAttendance['check_out'];
                    }
                }
            }

            // 2. Fetch All Employee Directory (Depending on roles, Super Admin or HR sees all)
            $employees = Employee::getAll();

            // 3. Fetch All Pending Leave Requests
            $leaveRequests = $db->fetchAll("
                SELECT lr.*, e.first_name, e.last_name, dg.title as designation_title
                FROM leave_requests lr
                JOIN employees e ON lr.employee_id = e.id
                JOIN designations dg ON e.designation_id = dg.id
                WHERE lr.status = 'pending'
                ORDER BY lr.created_at ASC
            ");

            // 4. Fetch departments & designations for creation dropdowns
            $departments   = $db->fetchAll("SELECT * FROM departments");
            $designations  = $db->fetchAll("SELECT * FROM designations");

            // Render view
            $this->render('HR/index', [
                'title'         => 'Corporate Human Resources Command',
                'employee'      => $employee,
                'clockState'    => $clockState,
                'employees'     => $employees,
                'leaveRequests' => $leaveRequests,
                'departments'   => $departments,
                'designations'  => $designations
            ]);

        } catch (Exception $e) {
            error_log("HR Controller Error: " . $e->getMessage());
            $response->html("<h1>HR Error</h1><p>{$e->getMessage()}</p>", 500);
        }
    }

    /**
     * Trigger Daily Time-Clock Check-In / Check-Out.
     */
    public function clock(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        $userId = SessionHelper::get('user_id');
        $action = $request->get('action');

        try {
            $employee = Employee::getByUserId($userId);
            if (!$employee) {
                throw new Exception("You must have an associated Employee Profile to use the Time-Clock.");
            }

            $service = new HRService();
            $msg = $service->recordAttendance($employee['id'], $action);

            $response->json(['status' => 'success', 'message' => $msg]);

        } catch (Exception $e) {
            $response->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Submit leave request.
     */
    public function applyLeave(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        $userId = SessionHelper::get('user_id');
        
        $leaveType = $request->get('leave_type');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
        $reason    = $request->get('reason');

        try {
            $employee = Employee::getByUserId($userId);
            if (!$employee) {
                throw new Exception("No active employee profile linked.");
            }

            $service = new HRService();
            $service->submitLeave($employee['id'], $leaveType, $startDate, $endDate, $reason);

            $response->json(['status' => 'success', 'message' => 'Leave application submitted successfully.']);

        } catch (Exception $e) {
            $response->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Process Leave Request (Approve/Reject) (Manager Action).
     */
    public function actionLeave(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        $userId = SessionHelper::get('user_id');

        $requestId = (int)$request->get('request_id');
        $status    = $request->get('status');

        try {
            $employee = Employee::getByUserId($userId);
            if (!$employee) {
                throw new Exception("Approver must have an active employee profile.");
            }

            $service = new HRService();
            $service->processLeave($requestId, $employee['id'], $status);

            $response->json(['status' => 'success', 'message' => "Leave request has been successfully " . $status . "."]);

        } catch (Exception $e) {
            $response->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Process Employee Monthly Payroll (Finance/HR Action).
     */
    public function processPayroll(Request $request, Response $response, array $params = []): void {
        $employeeId  = (int)$request->get('employee_id');
        $allowances  = (float)$request->get('allowances', 0.00);
        $deductions  = (float)$request->get('deductions', 0.00);
        $startPeriod = $request->get('start_period');
        $endPeriod   = $request->get('end_period');

        try {
            if (empty($startPeriod) || empty($endPeriod)) {
                throw new Exception("Pay period boundaries are required.");
            }

            $service = new HRService();
            $service->generatePayroll($employeeId, $allowances, $deductions, $startPeriod, $endPeriod);

            $response->json(['status' => 'success', 'message' => 'Monthly payroll calculations verified and posted to general ledger.']);

        } catch (Exception $e) {
            $response->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
