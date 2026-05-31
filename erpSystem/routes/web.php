<?php
// =========================================================================
// APPLICATION WEB ROUTES
// =========================================================================

/** @var \App\Core\Router $router */

$router->get('/', 'DashboardController@index', ['auth']);
$router->get('/dashboard', 'DashboardController@index', ['auth']);
$router->get('/hr', 'HRController@index', ['auth']);
$router->get('/inventory', 'InventoryController@index', ['auth']);
$router->get('/crm', 'CRMController@index', ['auth']);
$router->get('/sales', 'SalesController@index', ['auth']);
$router->get('/accounting', 'AccountingController@index', ['auth']);

// Authentication Routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
