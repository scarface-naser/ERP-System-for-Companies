<?php
// =========================================================================
// VIEW: CRM CLIENT RELATIONSHIP DESK
// =========================================================================

use App\Helpers\Sanitizer;
?>
<div class="page-header">
    <div class="breadcrumbs">
        <a href="/erpSystem/dashboard">Dashboard</a>
        <span>&bull;</span>
        <span>CRM Command</span>
    </div>
    <h1 style="margin-top: 0.5rem; font-size: 2.2rem; font-weight: 700; background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">CRM & Leads Pipeline</h1>
    <p style="color: var(--text-secondary); font-size: 0.95rem;">Track qualified leads, manage corporate account opportunities, and check secure touchpoint contact interactions.</p>
</div>

<!-- TOP GRID: NEW LEAD & STATS -->
<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
    
    <!-- 1. RECORD NEW LEAD -->
    <div class="glass-panel">
        <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Register Corporate Lead</h2>
        <form id="lead-form" onsubmit="submitLead(event)" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">FIRST NAME</label>
                <input type="text" name="first_name" placeholder="Alice" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">LAST NAME</label>
                <input type="text" name="last_name" placeholder="Smith" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">COMPANY NAME</label>
                <input type="text" name="company_name" placeholder="Initech Corp" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;">
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">CONTACT EMAIL</label>
                <input type="email" name="email" placeholder="asmith@initech.com" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">LEAD SOURCE</label>
                <select name="source" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;">
                    <option value="website">Website Portal</option>
                    <option value="referral">Client Referral</option>
                    <option value="cold_call">Cold Outreach</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">ASSIGNED TO</label>
                <select name="assigned_to" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                    <?php foreach ($employees as $emp): ?>
                    <option value="<?= (int)$emp['id'] ?>"><?= Sanitizer::escape($emp['first_name'] . ' ' . $emp['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="grid-column: span 2; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit" style="margin-top:0.5rem; max-width: 180px; background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); box-shadow: 0 4px 15px rgba(124, 58, 237, 0.35);">Add to Pipeline</button>
            </div>
        </form>
    </div>

    <!-- 2. PIPELINE CONTEXT STATS -->
    <div class="glass-panel" style="display: flex; flex-direction: column; justify-content: space-between;">
        <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Pipeline Valuation Indices</h2>
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem; flex-grow: 1; justify-content: center;">
            <div style="text-align: center; margin-bottom: 1rem;">
                <span style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Expected Opportunities Value</span>
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--color-primary); margin-top: 0.5rem;">$34,000.00</div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
                    <span style="color: var(--text-secondary);">Conversion Success Ratio</span>
                    <span style="font-weight: bold; color: var(--text-primary);">74%</span>
                </div>
                <div style="height: 6px; background: rgba(255,255,255,0.06); border-radius: 10px; overflow: hidden;">
                    <div style="height: 100%; width: 74%; background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%); border-radius: 10px;"></div>
                </div>
            </div>
        </div>

        <div style="font-size: 0.8rem; color: var(--text-muted); border-top: 1px solid var(--border-color); padding-top: 1rem; text-align: center;">
            Total corporate leads: <?= count($leads) ?>
        </div>
    </div>
</div>

<!-- LEADS PIPELINE REGISTER -->
<div class="glass-panel" style="margin-bottom: 2.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Corporate Client Leads Roster</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">Lead ID</th>
                    <th style="padding:1rem;">Full Name</th>
                    <th style="padding:1rem;">Company</th>
                    <th style="padding:1rem;">Contact Info</th>
                    <th style="padding:1rem;">Lead Source</th>
                    <th style="padding:1rem;">Status</th>
                    <th style="padding:1rem; text-align:right;">Assigned Operator</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $lead): 
                    $badgeBg = 'rgba(56, 189, 248, 0.08)';
                    $badgeColor = 'var(--color-primary)';
                    
                    if ($lead['status'] === 'qualified') {
                        $badgeBg = 'rgba(52, 211, 153, 0.08)';
                        $badgeColor = 'var(--color-success)';
                    } elseif ($lead['status'] === 'lost') {
                        $badgeBg = 'rgba(248, 113, 113, 0.08)';
                        $badgeColor = 'var(--color-danger)';
                    }
                ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--text-primary);">#<?= (int)$lead['id'] ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:600;"><?= Sanitizer::escape($lead['first_name'] . ' ' . $lead['last_name']) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($lead['company_name'] ?? 'Individual client') ?></td>
                    <td style="padding:1.2rem 1rem; font-family: monospace;">
                        <?= Sanitizer::escape($lead['email']) ?>
                        <div style="font-size:0.75rem; color:var(--text-muted);"><?= Sanitizer::escape($lead['phone'] ?? 'N/A') ?></div>
                    </td>
                    <td style="padding:1.2rem 1rem; text-transform: capitalize; color:var(--text-muted);"><?= Sanitizer::escape($lead['source']) ?></td>
                    <td style="padding:1.2rem 1rem;">
                        <span style="font-size:0.78rem; font-weight:600; padding:0.3rem 0.8rem; border-radius:30px; background:<?= $badgeBg ?>; color:<?= $badgeColor ?>; border:1px solid rgba(255,255,255,0.05);">
                            <?= ucfirst(Sanitizer::escape($lead['status'])) ?>
                        </span>
                    </td>
                    <td style="padding:1.2rem 1rem; text-align:right; font-weight:500;"><?= Sanitizer::escape($lead['employee_first'] . ' ' . $lead['employee_last']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- LATEST INTERACTION FEED -->
<div class="glass-panel" style="margin-bottom: 1.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Touchpoint Interaction History Feed</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">Date</th>
                    <th style="padding:1rem;">Contact Target</th>
                    <th style="padding:1rem;">Method</th>
                    <th style="padding:1rem;">Interaction Audit Notes</th>
                    <th style="padding:1rem; text-align:right;">Employee</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($interactions as $ci): 
                    $targetName = $ci['customer_name'] ?? ($ci['lead_first'] . ' ' . $ci['lead_last']);
                ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($ci['interaction_date']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:600;"><?= Sanitizer::escape($targetName) ?></td>
                    <td style="padding:1.2rem 1rem;">
                        <span style="font-size:0.75rem; font-weight:600; text-transform:uppercase; color:var(--color-primary);">
                            <?= Sanitizer::escape($ci['type']) ?>
                        </span>
                    </td>
                    <td style="padding:1.2rem 1rem; color:var(--text-muted); font-size:0.88rem;"><?= Sanitizer::escape($ci['notes']) ?></td>
                    <td style="padding:1.2rem 1rem; text-align:right; font-weight:500;"><?= Sanitizer::escape($ci['employee_first']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    async function submitLead(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('/erpSystem/api/crm/lead', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();
            if (result.status === 'success') {
                alert(result.message);
                form.reset();
                window.location.reload();
            } else {
                alert('Add lead failed: ' + result.error);
            }
        } catch (e) {
            alert('Request failed: ' + e.message);
        }
    }
</script>
