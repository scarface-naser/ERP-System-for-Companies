<?php
// =========================================================================
// VIEW: CORE SYSTEM DASHBOARD (GLASSMORPHIC ANALYTICS DESK)
// =========================================================================

use App\Helpers\Sanitizer;
?>
<div class="page-header">
    <div class="breadcrumbs">
        <a href="/erpSystem/dashboard">ERP Platform</a>
        <span>&bull;</span>
        <span>Corporate Dashboard</span>
    </div>
    <h1 style="margin-top: 0.5rem; font-size: 2.2rem; font-weight: 700; background: linear-gradient(135deg, #38bdf8 0%, #a78bfa 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Corporate Command Desk</h1>
    <p style="color: var(--text-secondary); font-size: 0.95rem;">Real-time analytics overview, inventory metrics, and system security feed.</p>
</div>

<!-- 1. PREMIUM KPI DATA TILES -->
<section class="kpi-grid">
    <div class="glass-panel glass-panel-hover kpi-card">
        <div class="kpi-data">
            <h3>Registered Employees</h3>
            <div class="kpi-value"><?= (int)$employeesCount ?></div>
            <div class="kpi-trend trend-up">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>+2.4% MoM</span>
            </div>
        </div>
        <div class="kpi-icon kpi-icon-success">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
    </div>

    <div class="glass-panel glass-panel-hover kpi-card">
        <div class="kpi-data">
            <h3>Inventory Valuation</h3>
            <div class="kpi-value">$<?= number_format($stockValuation, 2) ?></div>
            <div class="kpi-trend trend-up">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>+4.8% (Stock In)</span>
            </div>
        </div>
        <div class="kpi-icon kpi-icon-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
        </div>
    </div>

    <div class="glass-panel glass-panel-hover kpi-card">
        <div class="kpi-data">
            <h3>Monthly Payroll Liability</h3>
            <div class="kpi-value">$<?= number_format($payrollLiability, 2) ?></div>
            <div class="kpi-trend" style="color: var(--text-muted);">
                <span>Stabilized budget</span>
            </div>
        </div>
        <div class="kpi-icon" style="background: rgba(251, 191, 36, 0.08); border: 1px solid rgba(251, 191, 36, 0.2); color: var(--color-warning);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
    </div>
</section>

<!-- 2. ANALYTICS GRAPHS & DETAILS PANEL -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
    
    <!-- Analytics chart and metrics panel -->
    <div class="glass-panel" style="min-height: 380px; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="font-size: 1.2rem; font-weight: 600;">System Performance & Activity Index</h2>
            <div style="display: flex; gap: 0.5rem; background: rgba(15, 23, 42, 0.4); padding: 0.3rem; border-radius: 8px;">
                <span style="font-size: 0.78rem; padding: 0.3rem 0.8rem; border-radius: 6px; background: var(--bg-surface); cursor: pointer; color: var(--text-primary); font-weight: 600;">Live Feed</span>
                <span style="font-size: 0.78rem; padding: 0.3rem 0.8rem; border-radius: 6px; cursor: pointer; color: var(--text-secondary);">Historical</span>
            </div>
        </div>

        <!-- Custom Beautiful CSS Interactive Mock Chart Representation -->
        <div style="flex-grow: 1; display: flex; align-items: flex-end; justify-content: space-between; gap: 1.5rem; padding: 2rem 1rem 1rem 1rem; position: relative;">
            <!-- Grid lines background -->
            <div style="position: absolute; top:0; left:0; width:100%; height:100%; border-bottom: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; pointer-events: none; opacity: 0.5;">
                <div style="border-bottom: 1px dashed var(--border-color); height:0; width:100%;"></div>
                <div style="border-bottom: 1px dashed var(--border-color); height:0; width:100%;"></div>
                <div style="border-bottom: 1px dashed var(--border-color); height:0; width:100%;"></div>
                <div style="height:0; width:100%;"></div>
            </div>

            <!-- Chart bar elements with premium gradients -->
            <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 100%;">
                <div style="height: 140px; width: 34px; background: linear-gradient(to top, rgba(56, 189, 248, 0.2), #38bdf8); border-radius: 8px; box-shadow: var(--shadow-primary); animation: grow 1s ease;"></div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.6rem;">Admin</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 100%;">
                <div style="height: 90px; width: 34px; background: linear-gradient(to top, rgba(79, 70, 229, 0.2), #4f46e5); border-radius: 8px; animation: grow 1s ease;"></div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.6rem;">HR Desk</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 100%;">
                <div style="height: 180px; width: 34px; background: linear-gradient(to top, rgba(52, 211, 153, 0.2), #34d399); border-radius: 8px; animation: grow 1s ease;"></div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.6rem;">Inventory</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 100%;">
                <div style="height: 110px; width: 34px; background: linear-gradient(to top, rgba(251, 191, 36, 0.2), #fbbf24); border-radius: 8px; animation: grow 1s ease;"></div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.6rem;">Finance</span>
            </div>
        </div>
        <style>
            @keyframes grow {
                from { height: 0; }
            }
        </style>
    </div>

    <!-- Right Side Panel: System Metrics details -->
    <div class="glass-panel" style="display: flex; flex-direction: column; justify-content: space-between;">
        <h2 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1.5rem;">Inventory Catalog Ratio</h2>
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem; flex-grow: 1; justify-content: center;">
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
                    <span style="color: var(--text-secondary);">Enterprise Tech Catalog</span>
                    <span style="font-weight: bold; color: var(--text-primary);">68%</span>
                </div>
                <div style="height: 6px; background: rgba(255,255,255,0.06); border-radius: 10px; overflow: hidden;">
                    <div style="height: 100%; width: 68%; background: var(--color-primary-gradient); border-radius: 10px;"></div>
                </div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
                    <span style="color: var(--text-secondary);">Office Facility Equipment</span>
                    <span style="font-weight: bold; color: var(--text-primary);">32%</span>
                </div>
                <div style="height: 6px; background: rgba(255,255,255,0.06); border-radius: 10px; overflow: hidden;">
                    <div style="height: 100%; width: 32%; background: var(--color-success-gradient); border-radius: 10px;"></div>
                </div>
            </div>
        </div>

        <div style="font-size: 0.8rem; color: var(--text-muted); border-top: 1px solid var(--border-color); padding-top: 1rem; text-align: center;">
            Active warehouses count: 2
        </div>
    </div>
</div>

<!-- 3. LIVE SECURITY FEED / RECENT ACTIVITY AUDIT TRAIL -->
<div class="glass-panel" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.2rem; font-weight: 600;">Corporate Security Feed & Audit Logs</h2>
        <span style="font-size: 0.8rem; background: rgba(248, 113, 113, 0.08); border: 1px solid rgba(248, 113, 113, 0.2); color: var(--color-danger); padding: 0.3rem 0.8rem; border-radius: 30px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
            <span style="width: 6px; height: 6px; background-color: var(--color-danger); border-radius: 50%; box-shadow: 0 0 5px var(--color-danger);"></span>
            Secure Audit Trail Enabled
        </span>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 1rem;">Log ID</th>
                    <th style="padding: 1rem;">Timestamp</th>
                    <th style="padding: 1rem;">Actor</th>
                    <th style="padding: 1rem;">Module/Entity</th>
                    <th style="padding: 1rem;">Action Triggered</th>
                    <th style="padding: 1rem;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($activityLogs)): ?>
                    <?php foreach ($activityLogs as $log): 
                        $badgeBg = 'rgba(56, 189, 248, 0.08)';
                        $badgeBorder = 'rgba(56, 189, 248, 0.2)';
                        $badgeColor = 'var(--color-primary)';
                        
                        if (str_contains($log['action'], 'Failed') || str_contains($log['action'], 'Lockout')) {
                            $badgeBg = 'rgba(248, 113, 113, 0.08)';
                            $badgeBorder = 'rgba(248, 113, 113, 0.2)';
                            $badgeColor = 'var(--color-danger)';
                        } elseif (str_contains($log['action'], 'Success')) {
                            $badgeBg = 'rgba(52, 211, 153, 0.08)';
                            $badgeBorder = 'rgba(52, 211, 153, 0.2)';
                            $badgeColor = 'var(--color-success)';
                        }
                    ?>
                    <tr style="border-bottom: 1px solid var(--border-color); transition: var(--transition-fast);" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.2rem 1rem; font-weight: bold; color: var(--text-primary);">#<?= (int)$log['id'] ?></td>
                        <td style="padding: 1.2rem 1rem; color: var(--text-secondary);"><?= Sanitizer::escape($log['created_at']) ?></td>
                        <td style="padding: 1.2rem 1rem; font-weight: 500; color: var(--text-primary);"><?= Sanitizer::escape($log['username'] ?? 'Anonymous/System') ?></td>
                        <td style="padding: 1.2rem 1rem; color: var(--text-muted); text-transform: capitalize;"><?= Sanitizer::escape($log['entity_name'] ?? 'System Core') ?></td>
                        <td style="padding: 1.2rem 1rem;">
                            <span style="font-size: 0.78rem; font-weight: 600; padding: 0.3rem 0.8rem; border-radius: 30px; background: <?= $badgeBg ?>; border: 1px solid <?= $badgeBorder ?>; color: <?= $badgeColor ?>;">
                                <?= Sanitizer::escape($log['action']) ?>
                            </span>
                        </td>
                        <td style="padding: 1.2rem 1rem; font-family: monospace; color: var(--text-secondary);"><?= Sanitizer::escape($log['ip_address']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-muted);">No logs registered in database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
