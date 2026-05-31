<?php
// =========================================================================
// APPLICATION API ROUTES (REST/JSON ENDPOINTS)
// =========================================================================

/** @var \App\Core\Router $router */

// Auth API
$router->post('/api/auth/login', 'AuthController@login');
$router->post('/api/auth/logout', 'AuthController@logout');

// Core ERP Modules APIs (Protected by Auth and RBAC Permissions Middleware)
$router->get('/api/users', 'UserController@index', ['auth', 'rbac:users.manage']);
$router->post('/api/users', 'UserController@store', ['auth', 'rbac:users.manage']);

$router->post('/api/hr/clock', 'HRController@clock', ['auth']);
$router->post('/api/hr/apply-leave', 'HRController@applyLeave', ['auth']);
$router->post('/api/hr/action-leave', 'HRController@actionLeave', ['auth', 'rbac:hr.leaves']);
$router->post('/api/hr/process-payroll', 'HRController@processPayroll', ['auth', 'rbac:hr.payroll']);
$router->post('/api/inventory/adjust', 'InventoryController@adjust', ['auth', 'rbac:inventory.warehouses']);
$router->post('/api/inventory/transfer', 'InventoryController@transfer', ['auth', 'rbac:inventory.warehouses']);
$router->post('/api/crm/lead', 'CRMController@addLead', ['auth']);
$router->post('/api/sales/payment', 'SalesController@recordPayment', ['auth']);
