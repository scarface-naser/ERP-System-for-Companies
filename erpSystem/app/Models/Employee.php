<?php
// =========================================================================
// DOMAIN MODELS: HR EMPLOYEE ACTIVE RECORD DATA MAPPER
// =========================================================================

namespace App\Models;

use App\Core\Database;

class Employee {
    /**
     * Retrieve all active employee profiles from database with designations and departments.
     */
    public static function getAll(): array {
        $db = Database::getInstance();
        $sql = "
            SELECT e.*, d.name as department_name, dg.title as designation_title, dg.grade
            FROM employees e
            JOIN departments d ON e.department_id = d.id
            JOIN designations dg ON e.designation_id = dg.id
            ORDER BY e.employee_code ASC
        ";
        return $db->fetchAll($sql);
    }

    /**
     * Retrieve a single employee by database ID.
     */
    public static function getById(int $id): ?array {
        $db = Database::getInstance();
        $sql = "
            SELECT e.*, d.name as department_name, dg.title as designation_title, dg.grade, u.username
            FROM employees e
            JOIN departments d ON e.department_id = d.id
            JOIN designations dg ON e.designation_id = dg.id
            LEFT JOIN users u ON e.user_id = u.id
            WHERE e.id = :id
        ";
        return $db->fetch($sql, ['id' => $id]);
    }

    /**
     * Retrieve a single employee by their system user account ID.
     */
    public static function getByUserId(int $userId): ?array {
        $db = Database::getInstance();
        $sql = "
            SELECT e.*, d.name as department_name, dg.title as designation_title, dg.grade
            FROM employees e
            JOIN departments d ON e.department_id = d.id
            JOIN designations dg ON e.designation_id = dg.id
            WHERE e.user_id = :user_id
        ";
        return $db->fetch($sql, ['user_id' => $userId]);
    }

    /**
     * Fetch attendance records for a specific employee within a date range.
     */
    public static function getAttendance(int $employeeId, string $startDate, string $endDate): array {
        $db = Database::getInstance();
        $sql = "
            SELECT * FROM attendance 
            WHERE employee_id = :employee_id AND date BETWEEN :start_date AND :end_date
            ORDER BY date DESC
        ";
        return $db->fetchAll($sql, [
            'employee_id' => $employeeId,
            'start_date'  => $startDate,
            'end_date'    => $endDate
        ]);
    }

    /**
     * Fetch leave requests history for a specific employee.
     */
    public static function getLeaves(int $employeeId): array {
        $db = Database::getInstance();
        $sql = "
            SELECT lr.*, e.first_name as approved_first_name, e.last_name as approved_last_name
            FROM leave_requests lr
            LEFT JOIN employees e ON lr.approved_by = e.id
            WHERE lr.employee_id = :employee_id
            ORDER BY lr.created_at DESC
        ";
        return $db->fetchAll($sql, ['employee_id' => $employeeId]);
    }
}
