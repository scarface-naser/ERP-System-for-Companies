<?php
// =========================================================================
// SERVICES: HR DOMAIN BUSINESS LOGIC LAYER
// =========================================================================

namespace App\Services;

use App\Core\Database;
use Exception;

class HRService {
    /**
     * Record dynamic daily employee attendance check-ins and check-outs.
     */
    public function recordAttendance(int $employeeId, string $action): string {
        $db   = Database::getInstance();
        $date = date('Y-m-d');
        $time = date('H:i:s');

        // Fetch existing attendance for today
        $existing = $db->fetch("
            SELECT * FROM attendance 
            WHERE employee_id = :employee_id AND date = :date
        ", ['employee_id' => $employeeId, 'date' => $date]);

        if ($action === 'check_in') {
            if ($existing) {
                throw new Exception("You have already checked in for today ({$existing['check_in']}).");
            }
            
            // Determine present/late status based on 09:00 AM check-in
            $status = (strtotime($time) > strtotime('09:05:00')) ? 'late' : 'present';

            $db->query("
                INSERT INTO attendance (employee_id, date, check_in, status) 
                VALUES (:employee_id, :date, :check_in, :status)
            ", [
                'employee_id' => $employeeId,
                'date'        => $date,
                'check_in'    => $time,
                'status'      => $status
            ]);

            return "Clock-in recorded successfully at {$time}. Status: " . ucfirst($status) . ".";
        }

        if ($action === 'check_out') {
            if (!$existing) {
                throw new Exception("You must check in before recording a clock-out.");
            }
            if ($existing['check_out'] !== null) {
                throw new Exception("You have already recorded a clock-out for today ({$existing['check_out']}).");
            }

            $db->query("
                UPDATE attendance 
                SET check_out = :check_out 
                WHERE id = :id
            ", [
                'check_out' => $time,
                'id'        => $existing['id']
            ]);

            return "Clock-out recorded successfully at {$time}. Have a great evening!";
        }

        throw new Exception("Invalid attendance action specified.");
    }

    /**
     * Submit an employee leave application.
     */
    public function submitLeave(int $employeeId, string $leaveType, string $startDate, string $endDate, string $reason): void {
        $db = Database::getInstance();

        if (strtotime($startDate) > strtotime($endDate)) {
            throw new Exception("Leave start date cannot be after the end date.");
        }

        $db->query("
            INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, reason) 
            VALUES (:employee_id, :leave_type, :start_date, :end_date, :reason)
        ", [
            'employee_id' => $employeeId,
            'leave_type'  => $leaveType,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'reason'      => $reason
        ]);
    }

    /**
     * Approve or reject a leave request (manager action).
     */
    public function processLeave(int $requestId, int $approverId, string $status): void {
        $db = Database::getInstance();

        if (!in_array($status, ['approved', 'rejected'])) {
            throw new Exception("Invalid leave status parameter.");
        }

        $db->query("
            UPDATE leave_requests 
            SET status = :status, approved_by = :approved_by 
            WHERE id = :id
        ", [
            'status'      => $status,
            'approved_by' => $approverId,
            'id'          => $requestId
        ]);
    }

    /**
     * Process employee monthly payroll and post financial journal double-entry ledgers!
     */
    public function generatePayroll(int $employeeId, float $allowances, float $deductions, string $startPeriod, string $endPeriod): void {
        $db = Database::getInstance();

        // Start secure database transaction
        $db->beginTransaction();

        try {
            // Fetch employee profile details
            $employee = $db->fetch("SELECT * FROM employees WHERE id = :id", ['id' => $employeeId]);
            if (!$employee) {
                throw new Exception("Employee record not found.");
            }

            $baseSalary = (float)$employee['base_salary'];
            $netSalary  = $baseSalary + $allowances - $deductions;

            // 1. Insert Payroll Record
            $db->query("
                INSERT INTO payroll (employee_id, pay_period_start, pay_period_end, base_salary, allowances, deductions, payment_status, paid_at) 
                VALUES (:employee_id, :start, :end, :base, :allow, :deduct, 'paid', NOW())
            ", [
                'employee_id' => $employeeId,
                'start'       => $startPeriod,
                'end'         => $endPeriod,
                'base'        => $baseSalary,
                'allow'       => $allowances,
                'deduct'      => $deductions
            ]);

            // 2. Post Dynamic Financial Journal Entry (Double-Entry Bookkeeping)
            $journalCode = 'JV-PAY-' . date('Ymd') . '-' . $employeeId;
            $description = "Monthly payroll run for " . $employee['first_name'] . ' ' . $employee['last_name'] . " ({$startPeriod} to {$endPeriod})";

            $db->query("
                INSERT INTO journals (journal_code, entry_date, description, status, created_by) 
                VALUES (:code, NOW(), :desc, 'posted', 1)
            ", [
                'code' => $journalCode,
                'desc' => $description
            ]);

            $journalId = $db->lastInsertId();

            // Fetch Account IDs from Chart of Accounts codes
            // Salary Expense (code: 5020), Operating Bank Account (code: 1020)
            $salaryExpenseAcc = $db->fetch("SELECT id FROM chart_of_accounts WHERE code = '5020'")['id'] ?? null;
            $bankAcc          = $db->fetch("SELECT id FROM chart_of_accounts WHERE code = '1020'")['id'] ?? null;

            if (!$salaryExpenseAcc || !$bankAcc) {
                throw new Exception("Chart of Accounts mapping error. Please ensure codes 5020 and 1020 exist.");
            }

            // Debit Salary Expense Account
            $db->query("
                INSERT INTO journal_entries (journal_id, account_id, debit, credit) 
                VALUES (:j_id, :acc_id, :debit, 0.00)
            ", [
                'j_id'   => $journalId,
                'acc_id' => $salaryExpenseAcc,
                'debit'  => $netSalary
            ]);

            // Credit Operating Bank Account
            $db->query("
                INSERT INTO journal_entries (journal_id, account_id, debit, credit) 
                VALUES (:j_id, :acc_id, 0.00, :credit)
            ", [
                'j_id'   => $journalId,
                'acc_id' => $bankAcc,
                'credit' => $netSalary
            ]);

            // Commit Transaction
            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
