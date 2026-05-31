-- =========================================================================
-- SYSTEM SEED RECORDS FOR ERP SYSTEM
-- Target Database: erp_system
-- =========================================================================

USE `erp_system`;

-- Disable foreign key checks to avoid check issues during load
SET FOREIGN_KEY_CHECKS = 0;

-- Clear any existing seeds
TRUNCATE TABLE `role_permissions`;
TRUNCATE TABLE `permissions`;
TRUNCATE TABLE `users`;
TRUNCATE TABLE `roles`;
TRUNCATE TABLE `departments`;
TRUNCATE TABLE `designations`;
TRUNCATE TABLE `employees`;
TRUNCATE TABLE `warehouses`;
TRUNCATE TABLE `categories`;
TRUNCATE TABLE `products`;
TRUNCATE TABLE `chart_of_accounts`;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================================
-- 1. SEED ROLES
-- =========================================================================
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Super Admin', 'Full access to all system functions and administrative controls.'),
(2, 'Administrator', 'General administration access, excluding low-level system settings.'),
(3, 'HR Manager', 'Access to employees, designations, attendance, leave, and payroll.'),
(4, 'Finance Manager', 'Access to general ledger, chart of accounts, journals, invoices, and reports.'),
(5, 'Sales Manager', 'Access to leads, opportunities, quotations, sales orders, and invoices.'),
(6, 'Inventory Manager', 'Access to products, warehouses, stock adjustments, and goods receipts.'),
(7, 'Employee', 'Self-service access (view own attendance, submit leave requests).'),
(8, 'Read-Only User', 'General access to view datasets across modules without editing rights.');

-- =========================================================================
-- 2. SEED PERMISSIONS
-- =========================================================================
INSERT INTO `permissions` (`id`, `name`, `slug`, `category`, `description`) VALUES
-- System/Users
(1, 'Manage Users', 'users.manage', 'System', 'Create, edit, suspend, and delete users.'),
(2, 'View Logs', 'logs.view', 'System', 'Access the security and activity logs.'),
-- HR
(3, 'Manage Employees', 'hr.employees', 'Human Resources', 'Create and manage employee profiles.'),
(4, 'Manage Attendance', 'hr.attendance', 'Human Resources', 'View and adjust attendance records.'),
(5, 'Approve Leaves', 'hr.leaves', 'Human Resources', 'Approve or reject employee leave requests.'),
(6, 'Process Payroll', 'hr.payroll', 'Human Resources', 'Calculate, verify, and execute monthly payroll.'),
-- CRM
(7, 'Manage CRM', 'crm.manage', 'CRM', 'Track leads, opportunities, customers, and interactions.'),
-- Inventory
(8, 'Manage Products', 'inventory.products', 'Inventory', 'Create, edit, and categorize products.'),
(9, 'Manage Warehouses', 'inventory.warehouses', 'Inventory', 'Setup warehouses and perform stock transfers/adjustments.'),
-- Sales
(10, 'Manage Sales', 'sales.manage', 'Sales', 'Create quotations, sales orders, invoices, and record payments.'),
-- Purchasing
(11, 'Manage Purchasing', 'purchasing.manage', 'Purchasing', 'Manage vendors, purchase orders, and goods receipts.'),
-- Accounting
(12, 'Manage Accounts', 'accounting.accounts', 'Accounting', 'Setup chart of accounts, view ledgers and financial statements.'),
(13, 'Manage Journals', 'accounting.journals', 'Accounting', 'Create and post journal entries.'),
-- Reports
(14, 'View Reports', 'reports.view', 'Reports', 'Generate and export PDF/CSV business analytics reports.');

-- =========================================================================
-- 3. SEED ROLE PERMISSIONS (Super Admin has all)
-- =========================================================================
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), (1, 11), (1, 12), (1, 13), (1, 14), -- Super Admin (All)
(2, 1), (2, 3), (2, 4), (2, 5), (2, 7), (2, 8), (2, 9), (2, 10), (2, 11), (2, 14), -- Administrator (Non-System-Configs)
(3, 3), (3, 4), (3, 5), (3, 6), (3, 14), -- HR Manager
(4, 10), (4, 11), (4, 12), (4, 13), (4, 14), -- Finance Manager
(5, 7), (5, 10), (5, 14), -- Sales Manager
(6, 8), (6, 9), (6, 11), (6, 14); -- Inventory Manager

-- =========================================================================
-- 4. SEED MASTER ADMINISTRATIVE USERS
-- Password for all seed users is 'admin123', hashed using BCRYPT
-- =========================================================================
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role_id`, `status`) VALUES
(1, 'admin', 'admin@erp.local', '$2y$10$AHylpFZYZNXG2uLQ5UDl8ekRe7xhMUX/qt7qcnmcd7oxuTFBznZaW', 1, 'active'),
(2, 'hr_manager', 'hr@erp.local', '$2y$10$AHylpFZYZNXG2uLQ5UDl8ekRe7xhMUX/qt7qcnmcd7oxuTFBznZaW', 3, 'active'),
(3, 'finance_manager', 'finance@erp.local', '$2y$10$AHylpFZYZNXG2uLQ5UDl8ekRe7xhMUX/qt7qcnmcd7oxuTFBznZaW', 4, 'active'),
(4, 'sales_manager', 'sales@erp.local', '$2y$10$AHylpFZYZNXG2uLQ5UDl8ekRe7xhMUX/qt7qcnmcd7oxuTFBznZaW', 5, 'active'),
(5, 'inventory_manager', 'inventory@erp.local', '$2y$10$AHylpFZYZNXG2uLQ5UDl8ekRe7xhMUX/qt7qcnmcd7oxuTFBznZaW', 6, 'active'),
(6, 'jdoe', 'john.doe@erp.local', '$2y$10$AHylpFZYZNXG2uLQ5UDl8ekRe7xhMUX/qt7qcnmcd7oxuTFBznZaW', 7, 'active');

-- =========================================================================
-- 5. SEED HR DEPARTMENTS & DESIGNATIONS
-- =========================================================================
INSERT INTO `departments` (`id`, `name`, `description`, `manager_id`) VALUES
(1, 'Administration', 'Executive leadership and company-wide strategy.', NULL),
(2, 'Human Resources', 'Recruitment, payroll, employee welfare, and training.', NULL),
(3, 'Finance & Accounting', 'Bookkeeping, tax compliance, budgeting, and financial reports.', NULL),
(4, 'Sales & Marketing', 'Customer relations, lead conversions, quotations, and order processing.', NULL),
(5, 'Inventory & Warehousing', 'Product warehousing, stock auditing, logistics, and shipments.', NULL);

INSERT INTO `designations` (`id`, `title`, `grade`, `department_id`) VALUES
(1, 'Chief Executive Officer', 'G-10', 1),
(2, 'HR Lead Specialist', 'G-7', 2),
(3, 'Payroll Administrator', 'G-5', 2),
(4, 'Chief Financial Officer', 'G-9', 3),
(5, 'Senior Bookkeeper', 'G-6', 3),
(6, 'Sales Executive Consultant', 'G-6', 4),
(7, 'Warehouse Supervisor', 'G-5', 5),
(8, 'Logistics Officer', 'G-4', 5);

-- =========================================================================
-- 6. SEED EMPLOYEES
-- =========================================================================
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `first_name`, `last_name`, `email`, `phone`, `hire_date`, `status`, `department_id`, `designation_id`, `base_salary`, `bank_account`) VALUES
(1, 1, 'EMP-0001', 'Super', 'Admin', 'admin@erp.local', '+15550100', '2020-01-15', 'active', 1, 1, 9500.00, 'US-CHASE-1122334455'),
(2, 2, 'EMP-0002', 'Sarah', 'Jenkins', 'hr@erp.local', '+15550102', '2021-03-10', 'active', 2, 2, 5500.00, 'US-BOA-9988776655'),
(3, 3, 'EMP-0003', 'David', 'Vance', 'finance@erp.local', '+15550103', '2021-08-01', 'active', 3, 4, 7500.00, 'US-WF-4455667788'),
(4, 4, 'EMP-0004', 'Robert', 'Miller', 'sales@erp.local', '+15550104', '2022-02-14', 'active', 4, 6, 4800.00, 'US-CITI-3344556677'),
(5, 5, 'EMP-0005', 'Maria', 'Gomez', 'inventory@erp.local', '+15550105', '2022-09-01', 'active', 5, 7, 4500.00, 'US-CHASE-7788990011'),
(6, 6, 'EMP-0006', 'John', 'Doe', 'john.doe@erp.local', '+15550106', '2023-05-10', 'active', 4, 6, 4000.00, 'US-BOA-1234567890');

-- Set managers for departments now that employees exist
UPDATE `departments` SET `manager_id` = 1 WHERE `id` = 1;
UPDATE `departments` SET `manager_id` = 2 WHERE `id` = 2;
UPDATE `departments` SET `manager_id` = 3 WHERE `id` = 3;
UPDATE `departments` SET `manager_id` = 4 WHERE `id` = 4;
UPDATE `departments` SET `manager_id` = 5 WHERE `id` = 5;

-- =========================================================================
-- 7. SEED WAREHOUSES, CATEGORIES & PRODUCTS
-- =========================================================================
INSERT INTO `warehouses` (`id`, `name`, `location`, `capacity`) VALUES
(1, 'Central Warehouse A', 'Building 10, Commerce Industrial Park, NY', 5000),
(2, 'North Distribution Center', 'Suite 200, Logistics Blvd, Chicago', 3000);

INSERT INTO `categories` (`id`, `name`, `description`, `parent_id`) VALUES
(1, 'Enterprise Electronics', 'Computers, office hardware, and accessories.', NULL),
(2, 'Office & Facility Supplies', 'Stationery, chairs, desks, and other consumables.', NULL),
(3, 'Network & Infrastructure', 'Servers, switches, routers, and cabling systems.', 1);

INSERT INTO `products` (`id`, `sku`, `barcode`, `name`, `description`, `category_id`, `price`, `cost`, `reorder_level`, `status`) VALUES
(1, 'PROD-DELL-L5420', '884116365420', 'Dell Latitude 5420 Laptop', 'Intel Core i5-1145G7, 16GB DDR4, 512GB NVMe SSD, 14-inch FHD Display.', 1, 1200.00, 850.00, 15, 'active'),
(2, 'PROD-ERGO-CHAIR', '716253443322', 'Ergonomic Premium Office Chair', 'Fully adjustable lumbar support, 3D armrests, mesh back, high-density foam cushion.', 2, 350.00, 180.00, 10, 'active'),
(3, 'PROD-LOGI-MXM3', '097855156321', 'Logitech MX Master 3S Mouse', 'Wireless ergonomic mouse, ultra-fast magspeed scroll wheel, 8K DPI tracking.', 1, 100.00, 55.00, 20, 'active'),
(4, 'PROD-CISCO-C9300', '192837465012', 'Cisco Catalyst 9300 Switch', 'Layer 3 Network Switch, 48 Ports POE+, dual modular power supplies.', 3, 4500.00, 3100.00, 5, 'active');

INSERT INTO `warehouse_stock` (`warehouse_id`, `product_id`, `quantity`) VALUES
(1, 1, 45),
(1, 2, 28),
(1, 3, 62),
(1, 4, 8),
(2, 1, 15),
(2, 2, 10),
(2, 3, 30),
(2, 4, 3);

-- =========================================================================
-- 8. SEED CHART OF ACCOUNTS (Accounting & Finance)
-- =========================================================================
INSERT INTO `chart_of_accounts` (`id`, `code`, `name`, `type`, `subtype`, `status`) VALUES
-- Assets (1000 - 1999)
(1, '1010', 'Petty Cash', 'asset', 'current_asset', 'active'),
(2, '1020', 'Operating Bank Account', 'asset', 'current_asset', 'active'),
(3, '1100', 'Accounts Receivable (AR)', 'asset', 'current_asset', 'active'),
(4, '1200', 'Merchandise Inventory', 'asset', 'current_asset', 'active'),
-- Liabilities (2000 - 2999)
(5, '2010', 'Accounts Payable (AP)', 'liability', 'current_liability', 'active'),
(6, '2100', 'Sales Tax Payable', 'liability', 'current_liability', 'active'),
(7, '2200', 'Accrued Payroll', 'liability', 'current_liability', 'active'),
-- Equity (3000 - 3999)
(8, '3010', 'Owner Share Capital', 'equity', 'equity', 'active'),
(9, '3020', 'Retained Earnings', 'equity', 'equity', 'active'),
-- Revenue (4000 - 4999)
(10, '4010', 'Sales Revenue', 'revenue', 'revenue', 'active'),
(11, '4020', 'Service Consultation Income', 'revenue', 'revenue', 'active'),
-- Expenses (5000 - 5999)
(12, '5010', 'Cost of Goods Sold (COGS)', 'expense', 'expense', 'active'),
(13, '5020', 'Employee Salary Expense', 'expense', 'expense', 'active'),
(14, '5030', 'Facility Rent Expense', 'expense', 'expense', 'active'),
(15, '5040', 'Office Utility Expense', 'expense', 'expense', 'active');
