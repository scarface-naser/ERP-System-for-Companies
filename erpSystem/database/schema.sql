-- =========================================================================
-- ENTERPRISE ERP SYSTEM DATABASE SCHEMA
-- Target Database: erp_system
-- Compatibility: MySQL 8.0+ / MariaDB 10.4+
-- =========================================================================

CREATE DATABASE IF NOT EXISTS `erp_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `erp_system`;

-- Disable foreign key checks to allow drops if recreating
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `journal_entries`;
DROP TABLE IF EXISTS `journals`;
DROP TABLE IF EXISTS `chart_of_accounts`;
DROP TABLE IF EXISTS `goods_receipt_items`;
DROP TABLE IF EXISTS `goods_receipts`;
DROP TABLE IF EXISTS `purchase_order_items`;
DROP TABLE IF EXISTS `purchase_orders`;
DROP TABLE IF EXISTS `vendors`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `invoice_items`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `sales_order_items`;
DROP TABLE IF EXISTS `sales_orders`;
DROP TABLE IF EXISTS `quotation_items`;
DROP TABLE IF EXISTS `quotations`;
DROP TABLE IF EXISTS `stock_adjustments`;
DROP TABLE IF EXISTS `stock_transfer_items`;
DROP TABLE IF EXISTS `stock_transfers`;
DROP TABLE IF EXISTS `warehouse_stock`;
DROP TABLE IF EXISTS `warehouses`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `crm_interactions`;
DROP TABLE IF EXISTS `opportunities`;
DROP TABLE IF EXISTS `leads`;
DROP TABLE IF EXISTS `contacts`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `employee_performance`;
DROP TABLE IF EXISTS `employee_documents`;
DROP TABLE IF EXISTS `payroll`;
DROP TABLE IF EXISTS `leave_requests`;
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `designations`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `role_permissions`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `roles`;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================================
-- MODULE 1: AUTHENTICATION & AUTHORIZATION
-- =========================================================================

CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `category` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE `role_permissions` (
    `role_id` INT NOT NULL,
    `permission_id` INT NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role_id` INT NOT NULL,
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `remember_token` VARCHAR(100) NULL,
    `email_verified_at` TIMESTAMP NULL,
    `login_attempts` INT DEFAULT 0,
    `locked_until` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`),
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB;

CREATE TABLE `activity_logs` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `entity_name` VARCHAR(100) NULL,
    `entity_id` INT NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_logs_user_action` (`user_id`, `action`),
    INDEX `idx_logs_created_at` (`created_at`)
) ENGINE=InnoDB;

-- =========================================================================
-- MODULE 2: HUMAN RESOURCES (HR)
-- =========================================================================

CREATE TABLE `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `manager_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `designations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(100) NOT NULL,
    `grade` VARCHAR(10) NULL,
    `department_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `employees` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL UNIQUE,
    `employee_code` VARCHAR(20) NOT NULL UNIQUE,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NULL,
    `hire_date` DATE NOT NULL,
    `status` ENUM('active', 'terminated', 'on-leave') DEFAULT 'active',
    `department_id` INT NOT NULL,
    `designation_id` INT NOT NULL,
    `base_salary` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `bank_account` VARCHAR(100) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`),
    FOREIGN KEY (`designation_id`) REFERENCES `designations`(`id`),
    INDEX `idx_employees_code` (`employee_code`),
    INDEX `idx_employees_dept_desg` (`department_id`, `designation_id`)
) ENGINE=InnoDB;

-- Dynamic constraint linking back manager_id to employee
ALTER TABLE `departments` ADD CONSTRAINT `fk_dept_manager` FOREIGN KEY (`manager_id`) REFERENCES `employees`(`id`) ON DELETE SET NULL;

CREATE TABLE `attendance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `date` DATE NOT NULL,
    `check_in` TIME NOT NULL,
    `check_out` TIME NULL,
    `status` ENUM('present', 'absent', 'late', 'half-day') DEFAULT 'present',
    `notes` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_employee_date` (`employee_id`, `date`),
    INDEX `idx_attendance_date` (`date`)
) ENGINE=InnoDB;

CREATE TABLE `leave_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `leave_type` ENUM('annual', 'sick', 'unpaid', 'maternity', 'paternity') NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `reason` TEXT NOT NULL,
    `approved_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`approved_by`) REFERENCES `employees`(`id`),
    INDEX `idx_leave_dates` (`start_date`, `end_date`)
) ENGINE=InnoDB;

CREATE TABLE `payroll` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `pay_period_start` DATE NOT NULL,
    `pay_period_end` DATE NOT NULL,
    `base_salary` DECIMAL(15,2) NOT NULL,
    `allowances` DECIMAL(15,2) DEFAULT 0.00,
    `deductions` DECIMAL(15,2) DEFAULT 0.00,
    `net_salary` DECIMAL(15,2) GENERATED ALWAYS AS (base_salary + allowances - deductions) STORED,
    `payment_status` ENUM('pending', 'paid') DEFAULT 'pending',
    `paid_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`),
    INDEX `idx_payroll_period` (`pay_period_start`, `pay_period_end`)
) ENGINE=InnoDB;

CREATE TABLE `employee_documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `document_name` VARCHAR(100) NOT NULL,
    `document_type` VARCHAR(50) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `employee_performance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `reviewer_id` INT NOT NULL,
    `review_date` DATE NOT NULL,
    `score` INT CHECK (score BETWEEN 1 AND 5),
    `feedback` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`reviewer_id`) REFERENCES `employees`(`id`)
) ENGINE=InnoDB;

-- =========================================================================
-- MODULE 3: CRM
-- =========================================================================

CREATE TABLE `customers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NULL,
    `company_name` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `position` VARCHAR(100) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `leads` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `company_name` VARCHAR(100) NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `source` VARCHAR(50) NULL,
    `status` ENUM('new', 'contacted', 'qualified', 'lost') DEFAULT 'new',
    `assigned_to` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`assigned_to`) REFERENCES `employees`(`id`) ON DELETE SET NULL,
    INDEX `idx_leads_status` (`status`)
) ENGINE=InnoDB;

CREATE TABLE `opportunities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lead_id` INT NULL,
    `customer_id` INT NULL,
    `title` VARCHAR(100) NOT NULL,
    `expected_value` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `stage` ENUM('qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost') DEFAULT 'qualification',
    `probability` INT CHECK (probability BETWEEN 0 AND 100),
    `close_date` DATE NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL,
    INDEX `idx_opp_stage` (`stage`)
) ENGINE=InnoDB;

CREATE TABLE `crm_interactions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NULL,
    `lead_id` INT NULL,
    `employee_id` INT NOT NULL,
    `type` ENUM('email', 'call', 'meeting', 'task') NOT NULL,
    `notes` TEXT NOT NULL,
    `interaction_date` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lead_id`) REFERENCES `leads`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`),
    INDEX `idx_interaction_date` (`interaction_date`)
) ENGINE=InnoDB;

-- =========================================================================
-- MODULE 4: INVENTORY
-- =========================================================================

CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `parent_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sku` VARCHAR(50) NOT NULL UNIQUE,
    `barcode` VARCHAR(100) NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `category_id` INT NOT NULL,
    `price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `cost` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `reorder_level` INT NOT NULL DEFAULT 10,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`),
    INDEX `idx_products_sku` (`sku`)
) ENGINE=InnoDB;

CREATE TABLE `warehouses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `location` VARCHAR(255) NOT NULL,
    `capacity` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `warehouse_stock` (
    `warehouse_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 0,
    PRIMARY KEY (`warehouse_id`, `product_id`),
    FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `stock_transfers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `from_warehouse_id` INT NOT NULL,
    `to_warehouse_id` INT NOT NULL,
    `transfer_date` DATETIME NOT NULL,
    `status` ENUM('pending', 'in-transit', 'completed', 'cancelled') DEFAULT 'pending',
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses`(`id`),
    FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `stock_transfer_items` (
    `transfer_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    PRIMARY KEY (`transfer_id`, `product_id`),
    FOREIGN KEY (`transfer_id`) REFERENCES `stock_transfers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `stock_adjustments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `warehouse_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `adjusted_quantity` INT NOT NULL,
    `adjustment_type` ENUM('addition', 'deduction', 'damage') NOT NULL,
    `reason` VARCHAR(255) NOT NULL,
    `adjusted_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`adjusted_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- =========================================================================
-- MODULE 5: SALES
-- =========================================================================

CREATE TABLE `quotations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `quotation_code` VARCHAR(50) NOT NULL UNIQUE,
    `quotation_date` DATE NOT NULL,
    `expiry_date` DATE NOT NULL,
    `status` ENUM('draft', 'sent', 'accepted', 'declined', 'expired') DEFAULT 'draft',
    `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `quotation_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `quotation_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(15,2) NOT NULL,
    `discount` DECIMAL(15,2) DEFAULT 0.00,
    `total` DECIMAL(15,2) GENERATED ALWAYS AS (quantity * (unit_price - discount)) STORED,
    FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `sales_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `quotation_id` INT NULL,
    `customer_id` INT NOT NULL,
    `order_code` VARCHAR(50) NOT NULL UNIQUE,
    `order_date` DATE NOT NULL,
    `status` ENUM('pending', 'processing', 'shipped', 'cancelled', 'completed') DEFAULT 'pending',
    `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`quotation_id`) REFERENCES `quotations`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    INDEX `idx_sales_orders_status` (`status`)
) ENGINE=InnoDB;

CREATE TABLE `sales_order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sales_order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(15,2) NOT NULL,
    `discount` DECIMAL(15,2) DEFAULT 0.00,
    `total` DECIMAL(15,2) GENERATED ALWAYS AS (quantity * (unit_price - discount)) STORED,
    FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `invoices` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sales_order_id` INT NULL,
    `customer_id` INT NOT NULL,
    `invoice_code` VARCHAR(50) NOT NULL UNIQUE,
    `invoice_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `status` ENUM('unpaid', 'partially_paid', 'paid', 'overdue', 'cancelled') DEFAULT 'unpaid',
    `total_amount` DECIMAL(15,2) NOT NULL,
    `paid_amount` DECIMAL(15,2) DEFAULT 0.00,
    `balance_amount` DECIMAL(15,2) GENERATED ALWAYS AS (total_amount - paid_amount) STORED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
    INDEX `idx_invoices_status` (`status`)
) ENGINE=InnoDB;

CREATE TABLE `invoice_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `invoice_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(15,2) NOT NULL,
    `discount` DECIMAL(15,2) DEFAULT 0.00,
    `total` DECIMAL(15,2) GENERATED ALWAYS AS (quantity * (unit_price - discount)) STORED,
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `invoice_id` INT NOT NULL,
    `customer_id` INT NOT NULL,
    `payment_code` VARCHAR(50) NOT NULL UNIQUE,
    `payment_date` DATETIME NOT NULL,
    `payment_method` ENUM('cash', 'bank_transfer', 'credit_card', 'cheque') NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `reference` VARCHAR(100) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`)
) ENGINE=InnoDB;

-- =========================================================================
-- MODULE 6: PURCHASING
-- =========================================================================

CREATE TABLE `vendors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `company_name` VARCHAR(100) NOT NULL UNIQUE,
    `contact_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `tax_number` VARCHAR(50) NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `purchase_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `vendor_id` INT NOT NULL,
    `po_code` VARCHAR(50) NOT NULL UNIQUE,
    `po_date` DATE NOT NULL,
    `expected_delivery` DATE NULL,
    `status` ENUM('draft', 'ordered', 'partially_received', 'received', 'cancelled') DEFAULT 'draft',
    `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`vendor_id`) REFERENCES `vendors`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    INDEX `idx_po_status` (`status`)
) ENGINE=InnoDB;

CREATE TABLE `purchase_order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `purchase_order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `unit_cost` DECIMAL(15,2) NOT NULL,
    `total` DECIMAL(15,2) GENERATED ALWAYS AS (quantity * unit_cost) STORED,
    FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `goods_receipts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `purchase_order_id` INT NOT NULL,
    `grn_code` VARCHAR(50) NOT NULL UNIQUE,
    `receipt_date` DATETIME NOT NULL,
    `received_by` INT NOT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders`(`id`),
    FOREIGN KEY (`received_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE `goods_receipt_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `goods_receipt_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity_ordered` INT NOT NULL,
    `quantity_received` INT NOT NULL,
    FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB;

-- =========================================================================
-- MODULE 7: ACCOUNTING & FINANCE
-- =========================================================================

CREATE TABLE `chart_of_accounts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(20) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `type` ENUM('asset', 'liability', 'equity', 'revenue', 'expense') NOT NULL,
    `subtype` VARCHAR(50) NOT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_coa_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE `journals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `journal_code` VARCHAR(50) NOT NULL UNIQUE,
    `entry_date` DATE NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `reference` VARCHAR(100) NULL,
    `status` ENUM('draft', 'posted') DEFAULT 'draft',
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    INDEX `idx_journal_status` (`status`),
    INDEX `idx_journal_date` (`entry_date`)
) ENGINE=InnoDB;

CREATE TABLE `journal_entries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `journal_id` INT NOT NULL,
    `account_id` INT NOT NULL,
    `debit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `credit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (`journal_id`) REFERENCES `journals`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts`(`id`),
    CONSTRAINT `chk_debit_credit` CHECK (
        (debit > 0.00 AND credit = 0.00) OR 
        (credit > 0.00 AND debit = 0.00)
    )
) ENGINE=InnoDB;

-- =========================================================================
-- MODULE 9: SYSTEM NOTIFICATIONS & ALERTS
-- =========================================================================

CREATE TABLE `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `type` ENUM('info', 'warning', 'danger', 'success') DEFAULT 'info',
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_notifications_unread` (`user_id`, `is_read`)
) ENGINE=InnoDB;
