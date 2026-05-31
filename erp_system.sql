-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2026 at 04:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `entity_name` varchar(100) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `ip_address`, `user_agent`, `entity_name`, `entity_id`, `old_values`, `new_values`, `created_at`) VALUES
(1, 1, 'User Login (Failed Password)', '::1', 'Unknown', 'users', 1, NULL, NULL, '2026-05-31 01:57:33'),
(2, 1, 'User Login (Failed Password)', '::1', 'Unknown', 'users', 1, NULL, NULL, '2026-05-31 01:58:06'),
(3, 1, 'User Login (Success)', '::1', 'Unknown', 'users', 1, NULL, NULL, '2026-05-31 01:58:35'),
(4, 1, 'User Login (Success)', '::1', 'Unknown', 'users', 1, NULL, NULL, '2026-05-31 01:59:51'),
(5, 1, 'User Login (Success)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'users', 1, NULL, NULL, '2026-05-31 02:03:59'),
(6, 1, 'User Login (Success)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'users', 1, NULL, NULL, '2026-05-31 02:04:16'),
(7, 1, 'User Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'users', 1, NULL, NULL, '2026-05-31 02:04:30'),
(8, 1, 'User Login (Success)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'users', 1, NULL, NULL, '2026-05-31 02:04:32'),
(9, 1, 'User Login (Success)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'users', 1, NULL, NULL, '2026-05-31 02:06:19'),
(10, 1, 'User Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'users', 1, NULL, NULL, '2026-05-31 02:06:36'),
(11, 1, 'User Login (Success)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'users', 1, NULL, NULL, '2026-05-31 02:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `check_in` time NOT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','absent','late','half-day') DEFAULT 'present',
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `parent_id`, `created_at`) VALUES
(1, 'Enterprise Electronics', 'Computers, office hardware, and accessories.', NULL, '2026-05-31 01:54:44'),
(2, 'Office & Facility Supplies', 'Stationery, chairs, desks, and other consumables.', NULL, '2026-05-31 01:54:44'),
(3, 'Network & Infrastructure', 'Servers, switches, routers, and cabling systems.', 1, '2026-05-31 01:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `code`, `name`, `type`, `subtype`, `status`, `created_at`) VALUES
(1, '1010', 'Petty Cash', 'asset', 'current_asset', 'active', '2026-05-31 01:54:44'),
(2, '1020', 'Operating Bank Account', 'asset', 'current_asset', 'active', '2026-05-31 01:54:44'),
(3, '1100', 'Accounts Receivable (AR)', 'asset', 'current_asset', 'active', '2026-05-31 01:54:44'),
(4, '1200', 'Merchandise Inventory', 'asset', 'current_asset', 'active', '2026-05-31 01:54:44'),
(5, '2010', 'Accounts Payable (AP)', 'liability', 'current_liability', 'active', '2026-05-31 01:54:44'),
(6, '2100', 'Sales Tax Payable', 'liability', 'current_liability', 'active', '2026-05-31 01:54:44'),
(7, '2200', 'Accrued Payroll', 'liability', 'current_liability', 'active', '2026-05-31 01:54:44'),
(8, '3010', 'Owner Share Capital', 'equity', 'equity', 'active', '2026-05-31 01:54:44'),
(9, '3020', 'Retained Earnings', 'equity', 'equity', 'active', '2026-05-31 01:54:44'),
(10, '4010', 'Sales Revenue', 'revenue', 'revenue', 'active', '2026-05-31 01:54:44'),
(11, '4020', 'Service Consultation Income', 'revenue', 'revenue', 'active', '2026-05-31 01:54:44'),
(12, '5010', 'Cost of Goods Sold (COGS)', 'expense', 'expense', 'active', '2026-05-31 01:54:44'),
(13, '5020', 'Employee Salary Expense', 'expense', 'expense', 'active', '2026-05-31 01:54:44'),
(14, '5030', 'Facility Rent Expense', 'expense', 'expense', 'active', '2026-05-31 01:54:44'),
(15, '5040', 'Office Utility Expense', 'expense', 'expense', 'active', '2026-05-31 01:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `customer_id`, `name`, `email`, `phone`, `position`, `created_at`) VALUES
(1, 1, 'Markus Brody', 'mbrody@deltatech.com', '+15550203', 'Procurement Director', '2026-05-31 02:12:02'),
(2, 2, 'Elena Rostova', 'erostova@nexuscorp.com', '+15550204', 'VP Commercial Accounts', '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `crm_interactions`
--

CREATE TABLE `crm_interactions` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `type` enum('email','call','meeting','task') NOT NULL,
  `notes` text NOT NULL,
  `interaction_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_interactions`
--

INSERT INTO `crm_interactions` (`id`, `customer_id`, `lead_id`, `employee_id`, `type`, `notes`, `interaction_date`, `created_at`) VALUES
(1, NULL, 1, 4, 'meeting', 'Initial scoping meeting. Presented licensing overview.', '2026-05-28 10:00:00', '2026-05-31 02:12:02'),
(2, 1, NULL, 4, 'call', 'Followed up on server switch quotation. Client reviewing pricing.', '2026-05-30 14:30:00', '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `company_name`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Delta Technologies LLC', 'billing@deltatech.com', '+15550201', 'Delta Tech', 'Suite 100, Innovation Way, Seattle', 'active', '2026-05-31 02:12:02', '2026-05-31 02:12:02'),
(2, 'Nexus Global Corporation', 'finance@nexuscorp.com', '+15550202', 'Nexus Global', 'Floor 40, Highrise Ave, Chicago', 'active', '2026-05-31 02:12:02', '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `manager_id`, `created_at`, `updated_at`) VALUES
(1, 'Administration', 'Executive leadership and company-wide strategy.', 1, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(2, 'Human Resources', 'Recruitment, payroll, employee welfare, and training.', 2, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(3, 'Finance & Accounting', 'Bookkeeping, tax compliance, budgeting, and financial reports.', 3, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(4, 'Sales & Marketing', 'Customer relations, lead conversions, quotations, and order processing.', 4, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(5, 'Inventory & Warehousing', 'Product warehousing, stock auditing, logistics, and shipments.', 5, '2026-05-31 01:54:44', '2026-05-31 01:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `title`, `grade`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 'Chief Executive Officer', 'G-10', 1, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(2, 'HR Lead Specialist', 'G-7', 2, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(3, 'Payroll Administrator', 'G-5', 2, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(4, 'Chief Financial Officer', 'G-9', 3, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(5, 'Senior Bookkeeper', 'G-6', 3, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(6, 'Sales Executive Consultant', 'G-6', 4, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(7, 'Warehouse Supervisor', 'G-5', 5, '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(8, 'Logistics Officer', 'G-4', 5, '2026-05-31 01:54:44', '2026-05-31 01:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_code` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `status` enum('active','terminated','on-leave') DEFAULT 'active',
  `department_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `base_salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bank_account` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `first_name`, `last_name`, `email`, `phone`, `hire_date`, `status`, `department_id`, `designation_id`, `base_salary`, `bank_account`, `created_at`, `updated_at`) VALUES
(1, 1, 'EMP-0001', 'Super', 'Admin', 'admin@erp.local', '+15550100', '2020-01-15', 'active', 1, 1, 9500.00, 'US-CHASE-1122334455', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(2, 2, 'EMP-0002', 'Sarah', 'Jenkins', 'hr@erp.local', '+15550102', '2021-03-10', 'active', 2, 2, 5500.00, 'US-BOA-9988776655', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(3, 3, 'EMP-0003', 'David', 'Vance', 'finance@erp.local', '+15550103', '2021-08-01', 'active', 3, 4, 7500.00, 'US-WF-4455667788', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(4, 4, 'EMP-0004', 'Robert', 'Miller', 'sales@erp.local', '+15550104', '2022-02-14', 'active', 4, 6, 4800.00, 'US-CITI-3344556677', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(5, 5, 'EMP-0005', 'Maria', 'Gomez', 'inventory@erp.local', '+15550105', '2022-09-01', 'active', 5, 7, 4500.00, 'US-CHASE-7788990011', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(6, 6, 'EMP-0006', 'John', 'Doe', 'john.doe@erp.local', '+15550106', '2023-05-10', 'active', 4, 6, 4000.00, 'US-BOA-1234567890', '2026-05-31 01:54:44', '2026-05-31 01:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `employee_documents`
--

CREATE TABLE `employee_documents` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `document_name` varchar(100) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance`
--

CREATE TABLE `employee_performance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `review_date` date NOT NULL,
  `score` int(11) DEFAULT NULL CHECK (`score` between 1 and 5),
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipts`
--

CREATE TABLE `goods_receipts` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `grn_code` varchar(50) NOT NULL,
  `receipt_date` datetime NOT NULL,
  `received_by` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipt_items`
--

CREATE TABLE `goods_receipt_items` (
  `id` int(11) NOT NULL,
  `goods_receipt_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_ordered` int(11) NOT NULL,
  `quantity_received` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `invoice_code` varchar(50) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('unpaid','partially_paid','paid','overdue','cancelled') DEFAULT 'unpaid',
  `total_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `balance_amount` decimal(15,2) GENERATED ALWAYS AS (`total_amount` - `paid_amount`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `sales_order_id`, `customer_id`, `invoice_code`, `invoice_date`, `due_date`, `status`, `total_amount`, `paid_amount`, `created_at`) VALUES
(1, NULL, 1, 'INV-2026-001', '2026-05-25', '2026-06-25', 'unpaid', 14500.00, 0.00, '2026-05-31 02:12:02'),
(2, NULL, 2, 'INV-2026-002', '2026-05-27', '2026-06-27', 'partially_paid', 8000.00, 3000.00, '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) DEFAULT 0.00,
  `total` decimal(15,2) GENERATED ALWAYS AS (`quantity` * (`unit_price` - `discount`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journals`
--

CREATE TABLE `journals` (
  `id` int(11) NOT NULL,
  `journal_code` varchar(50) NOT NULL,
  `entry_date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `status` enum('draft','posted') DEFAULT 'draft',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journals`
--

INSERT INTO `journals` (`id`, `journal_code`, `entry_date`, `description`, `reference`, `status`, `created_by`, `created_at`) VALUES
(101, 'JV-SLS-2026-001', '2026-05-25', 'Sales Invoice INV-2026-001 Delta Tech', NULL, 'posted', 1, '2026-05-31 02:12:02'),
(102, 'JV-SLS-2026-002', '2026-05-27', 'Sales Invoice INV-2026-002 Nexus Global', NULL, 'posted', 1, '2026-05-31 02:12:02'),
(103, 'JV-REC-2026-001', '2026-05-28', 'Payment Receipt for INV-2026-002 Nexus', NULL, 'posted', 1, '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00
) ;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `journal_id`, `account_id`, `debit`, `credit`) VALUES
(1, 101, 3, 14500.00, 0.00),
(2, 101, 10, 0.00, 14500.00),
(3, 102, 3, 8000.00, 0.00),
(4, 102, 10, 0.00, 8000.00),
(5, 103, 2, 3000.00, 0.00),
(6, 103, 3, 0.00, 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `status` enum('new','contacted','qualified','lost') DEFAULT 'new',
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `first_name`, `last_name`, `company_name`, `email`, `phone`, `source`, `status`, `assigned_to`, `created_at`, `updated_at`) VALUES
(1, 'Alice', 'Smith', 'Initech Corp', 'asmith@initech.local', '+15550301', 'website', 'qualified', 4, '2026-05-31 02:12:02', '2026-05-31 02:12:02'),
(2, 'Bob', 'Vance', 'Vance Refrigeration', 'bvance@vanceair.local', '+15550302', 'referral', 'new', 4, '2026-05-31 02:12:02', '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_type` enum('annual','sick','unpaid','maternity','paternity') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `reason` text NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','danger','success') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE `opportunities` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `expected_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stage` enum('qualification','proposal','negotiation','closed_won','closed_lost') DEFAULT 'qualification',
  `probability` int(11) DEFAULT NULL CHECK (`probability` between 0 and 100),
  `close_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opportunities`
--

INSERT INTO `opportunities` (`id`, `lead_id`, `customer_id`, `title`, `expected_value`, `stage`, `probability`, `close_date`, `created_at`) VALUES
(1, 1, NULL, 'Initech Enterprise Software Licensing', 25000.00, 'proposal', 70, '2026-06-30', '2026-05-31 02:12:02'),
(2, NULL, 1, 'Delta Tech Server Switch Upgrade', 9000.00, 'qualification', 40, '2026-07-15', '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `payment_code` varchar(50) NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_method` enum('cash','bank_transfer','credit_card','cheque') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `customer_id`, `payment_code`, `payment_date`, `payment_method`, `amount`, `reference`, `created_at`) VALUES
(1, 2, 2, 'PAY-2026-001', '2026-05-28 11:15:00', 'bank_transfer', 3000.00, 'WIRE-99881122', '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `base_salary` decimal(15,2) NOT NULL,
  `allowances` decimal(15,2) DEFAULT 0.00,
  `deductions` decimal(15,2) DEFAULT 0.00,
  `net_salary` decimal(15,2) GENERATED ALWAYS AS (`base_salary` + `allowances` - `deductions`) STORED,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `category`, `description`) VALUES
(1, 'Manage Users', 'users.manage', 'System', 'Create, edit, suspend, and delete users.'),
(2, 'View Logs', 'logs.view', 'System', 'Access the security and activity logs.'),
(3, 'Manage Employees', 'hr.employees', 'Human Resources', 'Create and manage employee profiles.'),
(4, 'Manage Attendance', 'hr.attendance', 'Human Resources', 'View and adjust attendance records.'),
(5, 'Approve Leaves', 'hr.leaves', 'Human Resources', 'Approve or reject employee leave requests.'),
(6, 'Process Payroll', 'hr.payroll', 'Human Resources', 'Calculate, verify, and execute monthly payroll.'),
(7, 'Manage CRM', 'crm.manage', 'CRM', 'Track leads, opportunities, customers, and interactions.'),
(8, 'Manage Products', 'inventory.products', 'Inventory', 'Create, edit, and categorize products.'),
(9, 'Manage Warehouses', 'inventory.warehouses', 'Inventory', 'Setup warehouses and perform stock transfers/adjustments.'),
(10, 'Manage Sales', 'sales.manage', 'Sales', 'Create quotations, sales orders, invoices, and record payments.'),
(11, 'Manage Purchasing', 'purchasing.manage', 'Purchasing', 'Manage vendors, purchase orders, and goods receipts.'),
(12, 'Manage Accounts', 'accounting.accounts', 'Accounting', 'Setup chart of accounts, view ledgers and financial statements.'),
(13, 'Manage Journals', 'accounting.journals', 'Accounting', 'Create and post journal entries.'),
(14, 'View Reports', 'reports.view', 'Reports', 'Generate and export PDF/CSV business analytics reports.');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `reorder_level` int(11) NOT NULL DEFAULT 10,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `barcode`, `name`, `description`, `category_id`, `price`, `cost`, `reorder_level`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PROD-DELL-L5420', '884116365420', 'Dell Latitude 5420 Laptop', 'Intel Core i5-1145G7, 16GB DDR4, 512GB NVMe SSD, 14-inch FHD Display.', 1, 1200.00, 850.00, 15, 'active', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(2, 'PROD-ERGO-CHAIR', '716253443322', 'Ergonomic Premium Office Chair', 'Fully adjustable lumbar support, 3D armrests, mesh back, high-density foam cushion.', 2, 350.00, 180.00, 10, 'active', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(3, 'PROD-LOGI-MXM3', '097855156321', 'Logitech MX Master 3S Mouse', 'Wireless ergonomic mouse, ultra-fast magspeed scroll wheel, 8K DPI tracking.', 1, 100.00, 55.00, 20, 'active', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(4, 'PROD-CISCO-C9300', '192837465012', 'Cisco Catalyst 9300 Switch', 'Layer 3 Network Switch, 48 Ports POE+, dual modular power supplies.', 3, 4500.00, 3100.00, 5, 'active', '2026-05-31 01:54:44', '2026-05-31 01:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `po_code` varchar(50) NOT NULL,
  `po_date` date NOT NULL,
  `expected_delivery` date DEFAULT NULL,
  `status` enum('draft','ordered','partially_received','received','cancelled') DEFAULT 'draft',
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `total` decimal(15,2) GENERATED ALWAYS AS (`quantity` * `unit_cost`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `quotation_code` varchar(50) NOT NULL,
  `quotation_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('draft','sent','accepted','declined','expired') DEFAULT 'draft',
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `customer_id`, `quotation_code`, `quotation_date`, `expiry_date`, `status`, `total_amount`, `created_by`, `created_at`) VALUES
(1, 1, 'QTN-2026-101', '2026-05-20', '2026-06-20', 'accepted', 6400.00, 1, '2026-05-31 02:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) DEFAULT 0.00,
  `total` decimal(15,2) GENERATED ALWAYS AS (`quantity` * (`unit_price` - `discount`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'Full access to all system functions and administrative controls.', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(2, 'Administrator', 'General administration access, excluding low-level system settings.', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(3, 'HR Manager', 'Access to employees, designations, attendance, leave, and payroll.', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(4, 'Finance Manager', 'Access to general ledger, chart of accounts, journals, invoices, and reports.', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(5, 'Sales Manager', 'Access to leads, opportunities, quotations, sales orders, and invoices.', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(6, 'Inventory Manager', 'Access to products, warehouses, stock adjustments, and goods receipts.', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(7, 'Employee', 'Self-service access (view own attendance, submit leave requests).', '2026-05-31 01:54:44', '2026-05-31 01:54:44'),
(8, 'Read-Only User', 'General access to view datasets across modules without editing rights.', '2026-05-31 01:54:44', '2026-05-31 01:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(2, 1),
(2, 3),
(2, 4),
(2, 5),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 14),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 14),
(4, 10),
(4, 11),
(4, 12),
(4, 13),
(4, 14),
(5, 7),
(5, 10),
(5, 14),
(6, 8),
(6, 9),
(6, 11),
(6, 14);

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` int(11) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `order_date` date NOT NULL,
  `status` enum('pending','processing','shipped','cancelled','completed') DEFAULT 'pending',
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_items`
--

CREATE TABLE `sales_order_items` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) DEFAULT 0.00,
  `total` decimal(15,2) GENERATED ALWAYS AS (`quantity` * (`unit_price` - `discount`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `adjusted_quantity` int(11) NOT NULL,
  `adjustment_type` enum('addition','deduction','damage') NOT NULL,
  `reason` varchar(255) NOT NULL,
  `adjusted_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` int(11) NOT NULL,
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `transfer_date` datetime NOT NULL,
  `status` enum('pending','in-transit','completed','cancelled') DEFAULT 'pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_items`
--

CREATE TABLE `stock_transfer_items` (
  `transfer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role_id`, `status`, `remember_token`, `email_verified_at`, `login_attempts`, `locked_until`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@erp.local', '$2y$10$FjgiDwDbONhcZeVMxZCnv.DzS1aCW2e5eG6xZYN/mkZpJBjk70R/.', 1, 'active', NULL, NULL, 0, NULL, '2026-05-31 01:54:44', '2026-05-31 01:58:35'),
(2, 'hr_manager', 'hr@erp.local', '$2y$10$FjgiDwDbONhcZeVMxZCnv.DzS1aCW2e5eG6xZYN/mkZpJBjk70R/.', 3, 'active', NULL, NULL, 0, NULL, '2026-05-31 01:54:44', '2026-05-31 01:58:28'),
(3, 'finance_manager', 'finance@erp.local', '$2y$10$FjgiDwDbONhcZeVMxZCnv.DzS1aCW2e5eG6xZYN/mkZpJBjk70R/.', 4, 'active', NULL, NULL, 0, NULL, '2026-05-31 01:54:44', '2026-05-31 01:58:28'),
(4, 'sales_manager', 'sales@erp.local', '$2y$10$FjgiDwDbONhcZeVMxZCnv.DzS1aCW2e5eG6xZYN/mkZpJBjk70R/.', 5, 'active', NULL, NULL, 0, NULL, '2026-05-31 01:54:44', '2026-05-31 01:58:28'),
(5, 'inventory_manager', 'inventory@erp.local', '$2y$10$FjgiDwDbONhcZeVMxZCnv.DzS1aCW2e5eG6xZYN/mkZpJBjk70R/.', 6, 'active', NULL, NULL, 0, NULL, '2026-05-31 01:54:44', '2026-05-31 01:58:28'),
(6, 'jdoe', 'john.doe@erp.local', '$2y$10$FjgiDwDbONhcZeVMxZCnv.DzS1aCW2e5eG6xZYN/mkZpJBjk70R/.', 7, 'active', NULL, NULL, 0, NULL, '2026-05-31 01:54:44', '2026-05-31 01:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `location`, `capacity`, `created_at`) VALUES
(1, 'Central Warehouse A', 'Building 10, Commerce Industrial Park, NY', 5000, '2026-05-31 01:54:44'),
(2, 'North Distribution Center', 'Suite 200, Logistics Blvd, Chicago', 3000, '2026-05-31 01:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_stock`
--

CREATE TABLE `warehouse_stock` (
  `warehouse_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouse_stock`
--

INSERT INTO `warehouse_stock` (`warehouse_id`, `product_id`, `quantity`) VALUES
(1, 1, 45),
(1, 2, 28),
(1, 3, 62),
(1, 4, 8),
(2, 1, 15),
(2, 2, 10),
(2, 3, 30),
(2, 4, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_user_action` (`user_id`,`action`),
  ADD KEY `idx_logs_created_at` (`created_at`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_employee_date` (`employee_id`,`date`),
  ADD KEY `idx_attendance_date` (`date`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_coa_code` (`code`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `crm_interactions`
--
ALTER TABLE `crm_interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `idx_interaction_date` (`interaction_date`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_dept_manager` (`manager_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `designation_id` (`designation_id`),
  ADD KEY `idx_employees_code` (`employee_code`),
  ADD KEY `idx_employees_dept_desg` (`department_id`,`designation_id`);

--
-- Indexes for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee_performance`
--
ALTER TABLE `employee_performance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grn_code` (`grn_code`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `received_by` (`received_by`);

--
-- Indexes for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_id` (`goods_receipt_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_code` (`invoice_code`),
  ADD KEY `sales_order_id` (`sales_order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `idx_invoices_status` (`status`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_code` (`journal_code`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_journal_status` (`status`),
  ADD KEY `idx_journal_date` (`entry_date`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_id` (`journal_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `idx_leads_status` (`status`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_leave_dates` (`start_date`,`end_date`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_unread` (`user_id`,`is_read`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `idx_opp_stage` (`stage`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_code` (`payment_code`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `idx_payroll_period` (`pay_period_start`,`pay_period_end`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_products_sku` (`sku`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_code` (`po_code`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_po_status` (`status`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotation_code` (`quotation_code`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id` (`quotation_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `quotation_id` (`quotation_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_sales_orders_status` (`status`);

--
-- Indexes for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_id` (`sales_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_id` (`warehouse_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `adjusted_by` (`adjusted_by`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_warehouse_id` (`from_warehouse_id`),
  ADD KEY `to_warehouse_id` (`to_warehouse_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD PRIMARY KEY (`transfer_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_status` (`status`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_name` (`company_name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  ADD PRIMARY KEY (`warehouse_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `crm_interactions`
--
ALTER TABLE `crm_interactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_documents`
--
ALTER TABLE `employee_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_performance`
--
ALTER TABLE `employee_performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `crm_interactions`
--
ALTER TABLE `crm_interactions`
  ADD CONSTRAINT `crm_interactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `crm_interactions_ibfk_2` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `crm_interactions_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `fk_dept_manager` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`);

--
-- Constraints for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD CONSTRAINT `employee_documents_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_performance`
--
ALTER TABLE `employee_performance`
  ADD CONSTRAINT `employee_performance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_performance_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD CONSTRAINT `goods_receipts_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `goods_receipts_ibfk_2` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD CONSTRAINT `goods_receipt_items_ibfk_1` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipt_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `journals`
--
ALTER TABLE `journals`
  ADD CONSTRAINT `journals_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_ibfk_1` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `journal_entries_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`);

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD CONSTRAINT `opportunities_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `opportunities_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `quotations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotation_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD CONSTRAINT `sales_orders_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `sales_orders_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD CONSTRAINT `sales_order_items_ibfk_1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `stock_adjustments_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_adjustments_ibfk_3` FOREIGN KEY (`adjusted_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `stock_transfers_ibfk_1` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `stock_transfers_ibfk_2` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `stock_transfers_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD CONSTRAINT `stock_transfer_items_ibfk_1` FOREIGN KEY (`transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_transfer_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  ADD CONSTRAINT `warehouse_stock_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warehouse_stock_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
