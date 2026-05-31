<?php
// =========================================================================
// VIEW: GENERAL LEDGER & TRIAL BALANCE COMMAND DESK
// =========================================================================

use App\Helpers\Sanitizer;
?>
<div class="page-header">
    <div class="breadcrumbs">
        <a href="/erpSystem/dashboard">Dashboard</a>
        <span>&bull;</span>
        <span>General Ledger Command</span>
    </div>
    <h1 style="margin-top: 0.5rem; font-size: 2.2rem; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Financial Control Desk</h1>
    <p style="color: var(--text-secondary); font-size: 0.95rem;">Auditable chart of accounts, dynamic Trial Balance ledgers, and posted double-entry journal logs.</p>
</div>

<!-- TOP GRID: TRIAL BALANCE STATUS CARD -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
    
    <!-- 1. TRIAL BALANCE BALANCE SHEET INDICATOR -->
    <div class="glass-panel" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 200px;">
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 0.3rem;">Double-Entry Integrity Diagnostic</h2>
            <p style="color: var(--text-muted); font-size: 0.82rem; margin-bottom: 1.5rem;">Standard enterprise ledger diagnostics requiring absolute mathematical balancing (Debits = Credits).</p>
        </div>

        <div style="display: flex; justify-content: space-around; align-items: center; padding: 1.5rem 0;">
            <div style="text-align: center;">
                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Grand Total Debits</span>
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-primary);">$<?= number_format($grandDebit, 2) ?></div>
            </div>
            <div style="font-size: 1.8rem; color: var(--text-muted); font-weight: 300;">=</div>
            <div style="text-align: center;">
                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Grand Total Credits</span>
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-success);">$<?= number_format($grandCredit, 2) ?></div>
            </div>
        </div>

        <?php 
            $balanced = (abs($grandDebit - $grandCredit) < 0.001); 
            $badgeBg = $balanced ? 'rgba(52, 211, 153, 0.08)' : 'rgba(248, 113, 113, 0.08)';
            $badgeColor = $balanced ? 'var(--color-success)' : 'var(--color-danger)';
            $badgeText = $balanced ? 'Double-Entry Ledgers Balanced' : 'Out of Balance Alert!';
        ?>
        <div style="display: flex; justify-content: center; border-top: 1px solid var(--border-color); padding-top: 0.8rem;">
            <span style="font-size:0.78rem; font-weight:600; padding:0.3rem 1.2rem; border-radius:30px; background:<?= $badgeBg ?>; color:<?= $badgeColor ?>; border:1px solid rgba(255,255,255,0.05);">
                <?= $badgeText ?>
            </span>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="glass-panel" style="display: flex; flex-direction: column; justify-content: space-between;">
        <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 0.8rem;">Financial Diagnostics</h2>
        <div style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6;">
            <strong>Operating Cash Assets</strong>: Petty cash + Active Bank accounts hold liquid funds ready for deployments.<br><br>
            <strong>Accounts Receivable</strong>: Total credit extended to commercial corporate clients awaiting cash collection.
        </div>
    </div>
</div>

<!-- DYNAMIC TRIAL BALANCE WORKSHEET -->
<div class="glass-panel" style="margin-bottom: 2.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Dynamic General Ledger Trial Balance Worksheet</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">Account Code</th>
                    <th style="padding:1rem;">Account Name</th>
                    <th style="padding:1rem;">Type</th>
                    <th style="padding:1rem;">Subtype</th>
                    <th style="padding:1rem; text-align:right;">Trial Debits</th>
                    <th style="padding:1rem; text-align:right;">Trial Credits</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trialBalance as $row): 
                    $netDeb = (float)$row['net_debit'];
                    $netCred = (float)$row['net_credit'];
                ?>
                <tr style="border-bottom: 1px solid var(--border-color); transition: var(--transition-fast);" onmouseover="this.style.background='rgba(255,255,255,0.01)'" onmouseout="this.style.background='transparent'">
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--text-primary); font-family: monospace;"><?= Sanitizer::escape($row['code']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:600;"><?= Sanitizer::escape($row['name']) ?></td>
                    <td style="padding:1.2rem 1rem; text-transform: capitalize; color:var(--text-secondary);"><?= Sanitizer::escape($row['type']) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-muted); text-transform: capitalize;"><?= str_replace('_', ' ', Sanitizer::escape($row['subtype'])) ?></td>
                    <td style="padding:1.2rem 1rem; text-align:right; font-weight:bold; color:<?= ($netDeb > 0) ? 'var(--color-primary)' : 'var(--text-muted)' ?>;">
                        <?= ($netDeb > 0) ? '$' . number_format($netDeb, 2) : '-' ?>
                    </td>
                    <td style="padding:1.2rem 1rem; text-align:right; font-weight:bold; color:<?= ($netCred > 0) ? 'var(--color-success)' : 'var(--text-muted)' ?>;">
                        <?= ($netCred > 0) ? '$' . number_format($netCred, 2) : '-' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <!-- Dynamic Totals Summary Line -->
                <tr style="border-top:2px solid var(--border-color); border-bottom:2px solid var(--border-color); background:rgba(15,23,42,0.4); font-weight:bold;">
                    <td colspan="4" style="padding:1.5rem 1rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-primary);">Grand Balanced Totals</td>
                    <td style="padding:1.5rem 1rem; text-align:right; font-size:1.05rem; color:var(--color-primary);">$<?= number_format($grandDebit, 2) ?></td>
                    <td style="padding:1.5rem 1rem; text-align:right; font-size:1.05rem; color:var(--color-success);">$<?= number_format($grandCredit, 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- MANUAL & SYSTEM JOURNAL SLIPS REGISTER -->
<div class="glass-panel" style="margin-bottom: 1.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">General Journal Slips Ledger</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">Journal Code</th>
                    <th style="padding:1rem;">Entry Date</th>
                    <th style="padding:1rem;">Reference / Memo</th>
                    <th style="padding:1rem;">Status</th>
                    <th style="padding:1rem; text-align:right;">Audited By</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($journals as $j): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--text-primary);"><?= Sanitizer::escape($j['journal_code']) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($j['entry_date']) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-muted); font-size:0.88rem;"><?= Sanitizer::escape($j['description']) ?></td>
                    <td style="padding:1.2rem 1rem;">
                        <span style="font-size:0.75rem; font-weight:600; padding:0.3rem 0.8rem; border-radius:30px; background:rgba(52,211,153,0.08); color:var(--color-success); border:1px solid rgba(52,211,153,0.15);">
                            <?= ucfirst(Sanitizer::escape($j['status'])) ?>
                        </span>
                    </td>
                    <td style="padding:1.2rem 1rem; text-align:right; font-weight:500; color:var(--text-secondary);"><?= Sanitizer::escape($j['created_by_name'] ?? 'System Core') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
