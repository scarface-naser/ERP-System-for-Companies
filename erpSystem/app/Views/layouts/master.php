<?php
// =========================================================================
// VIEW MASTER LAYOUT TEMPLATE WRAPPER (OWASP COMPLIANT)
// =========================================================================

use App\Helpers\SessionHelper;
use App\Helpers\Sanitizer;

// Verify secure session variables
$username = SessionHelper::get('username', 'Guest User');
$roleName = SessionHelper::get('role_name', 'Visitor');
$roleId   = (int)SessionHelper::get('role_id', 0);
$initials = strtoupper(substr($username, 0, 2));

// Safe escaping
$escapedUsername = Sanitizer::escape($username);
$escapedRole     = Sanitizer::escape($roleName);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= Sanitizer::escape($title ?? 'Enterprise ERP System') ?></title>
    <!-- Dynamic CSS stylesheets -->
    <link rel="stylesheet" href="/erpSystem/public/assets/css/main.css">
</head>
<body>

<div class="app-wrapper">
    <!-- 1. PROFESSIONAL SIDEBAR NAVIGATION -->
    <aside class="app-sidebar">
        <div>
            <div class="sidebar-brand">
                <div class="sidebar-logo">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                </div>
                <h2>Enterprise ERP</h2>
            </div>

            <?php
            $currentUri = $_SERVER['REQUEST_URI'] ?? '';
            $isDashboardActive = str_contains($currentUri, '/dashboard') || $currentUri === '/erpSystem' || $currentUri === '/erpSystem/' || $currentUri === '/';
            $isHRActive = str_contains($currentUri, '/hr');
            $isInventoryActive = str_contains($currentUri, '/inventory');
            $isCRMActive = str_contains($currentUri, '/crm');
            $isSalesActive = str_contains($currentUri, '/sales');
            $isAccountingActive = str_contains($currentUri, '/accounting');
            ?>
            <ul class="sidebar-menu">
                <li>
                    <a href="/erpSystem/dashboard" class="sidebar-link <?= $isDashboardActive ? 'active' : '' ?>">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1"></rect><rect x="14" y="3" width="7" height="5" rx="1"></rect><rect x="14" y="12" width="7" height="9" rx="1"></rect><rect x="3" y="16" width="7" height="5" rx="1"></rect></svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- DYNAMIC ROLE-BASED NAVIGATION ITEMS -->
                <?php if ($roleId === 1 || $roleId === 2): // Admin & Super Admin ?>
                <li>
                    <a href="#" class="sidebar-link">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>User Management</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($roleId === 1 || $roleId === 3): // HR Managers ?>
                <li>
                    <a href="/erpSystem/hr" class="sidebar-link <?= $isHRActive ? 'active' : '' ?>">
                        <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        <span>Human Resources</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($roleId === 1 || $roleId === 5 || $roleId === 3): // CRM & Sales ?>
                <li>
                    <a href="/erpSystem/crm" class="sidebar-link <?= $isCRMActive ? 'active' : '' ?>">
                        <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                        <span>CRM & Leads</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($roleId === 1 || $roleId === 5 || $roleId === 4): // Commercial Sales & Revenue ?>
                <li>
                    <a href="/erpSystem/sales" class="sidebar-link <?= $isSalesActive ? 'active' : '' ?>">
                        <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="12" y1="4" x2="12" y2="20"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg>
                        <span>Sales & Billing</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($roleId === 1 || $roleId === 6 || $roleId === 4): // Inventory & Purchases ?>
                <li>
                    <a href="/erpSystem/inventory" class="sidebar-link <?= $isInventoryActive ? 'active' : '' ?>">
                        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Inventory</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($roleId === 1 || $roleId === 4): // Finance & Accounting ?>
                <li>
                    <a href="/erpSystem/accounting" class="sidebar-link <?= $isAccountingActive ? 'active' : '' ?>">
                        <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <span>Accounting</span>
                    </a>
                </li>
                <?php endif; ?>

                <li>
                    <a href="#" class="sidebar-link">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="user-avatar"><?= $initials ?></div>
            <div class="user-info">
                <h4><?= $escapedUsername ?></h4>
                <p><?= $escapedRole ?></p>
            </div>
            <a href="/erpSystem/logout" style="margin-left: auto;" title="Logout Secure Session">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </a>
        </div>
    </aside>

    <!-- 2. MAIN APPLICATION CONTENT PANEL -->
    <main class="app-content">
        <!-- GLOBAL APP TOP BAR -->
        <header class="app-header">
            <div class="header-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Search ERP modules, products, and employee records...">
            </div>

            <div class="header-controls">
                <!-- Theme Toggle Switch -->
                <button class="theme-toggle-btn" id="theme-toggle" title="Toggle Visual Theme">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-sun">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                </button>

                <!-- Notifications Bell Drawer -->
                <div class="notify-bell" title="System Alerts">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
            </div>
        </header>

        <!-- Page Target Content Outlet -->
        <div class="page-content">
            <?= $content ?>
        </div>
    </main>
</div>

<!-- Core javascript scripts -->
<script>
    // 1. Dynamic Theme Management (Dark / Light Mode)
    const themeBtn = document.getElementById('theme-toggle');
    const htmlEl = document.documentElement;

    // Load active cached theme or default to dark
    const cachedTheme = localStorage.getItem('erp_theme') || 'dark';
    htmlEl.setAttribute('data-theme', cachedTheme);

    themeBtn.addEventListener('click', () => {
        const currentTheme = htmlEl.getAttribute('data-theme');
        const targetTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        htmlEl.setAttribute('data-theme', targetTheme);
        localStorage.setItem('erp_theme', targetTheme);
        
        // Dynamic micro-animation trigger
        themeBtn.style.transform = 'rotate(180deg)';
        setTimeout(() => { themeBtn.style.transform = 'none'; }, 300);
    });

    // 2. Subtle Micro-animations on Sidebar Hover
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('mouseenter', () => {
            const svg = link.querySelector('svg');
            if (svg) svg.style.transform = 'scale(1.15) rotate(2deg)';
        });
        link.addEventListener('mouseleave', () => {
            const svg = link.querySelector('svg');
            if (svg) svg.style.transform = 'none';
        });
    });
</script>
</body>
</html>
