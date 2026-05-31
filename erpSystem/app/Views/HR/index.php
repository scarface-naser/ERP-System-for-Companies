<?php
// =========================================================================
// VIEW: HR EXECUTIVE DESK
// =========================================================================

use App\Helpers\Sanitizer;
use App\Helpers\SessionHelper;

$roleId = (int)SessionHelper::get('role_id', 0);
?>
<div class="page-header">
    <div class="breadcrumbs">
        <a href="/erpSystem/dashboard">Dashboard</a>
        <span>&bull;</span>
        <span>Human Resources Command</span>
    </div>
    <h1 style="margin-top: 0.5rem; font-size: 2.2rem; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">HR Command Desk</h1>
    <p style="color: var(--text-secondary); font-size: 0.95rem;">Manage employee rosters, dynamic attendance registers, leave approvals, and ledger-based payroll runs.</p>
</div>

<!-- TOP GRID: TIME CLOCK & LEAVE APPLICATION -->
<div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem; margin-bottom: 2.5rem;">
    
    <!-- 1. DYNAMIC TIME-CLOCK PANEL -->
    <div class="glass-panel" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 280px;">
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 0.3rem;">Digital Attendance Register</h2>
            <p style="color: var(--text-muted); font-size: 0.82rem; margin-bottom: 1.5rem;">Clock-in before 09:00 AM to register daily attendance status.</p>
        </div>

        <div style="text-align: center; padding: 1.5rem 0;">
            <?php if (!$clockState['checked_in']): ?>
                <button class="btn-submit" onclick="triggerClock('check_in')" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); max-width: 200px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);">
                    Clock In Duty
                </button>
            <?php elseif (!$clockState['checked_out']): ?>
                <div style="color: var(--color-success); font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem;">
                    Active Duty Logged since <?= Sanitizer::escape($clockState['time']) ?>
                </div>
                <button class="btn-submit" onclick="triggerClock('check_out')" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); max-width: 200px; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.35);">
                    Clock Out Duty
                </button>
            <?php else: ?>
                <div style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--color-success)" stroke-width="2.5" style="margin-bottom: 0.5rem;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <br>All Duties Completed (Out: <?= Sanitizer::escape($clockState['time']) ?>)
                </div>
            <?php endif; ?>
        </div>

        <div style="font-size: 0.78rem; color: var(--text-muted); border-top: 1px solid var(--border-color); padding-top: 0.8rem; text-align: center;">
            Current system server time: <?= date('h:i A') ?>
        </div>
    </div>

    <!-- 2. APPLY FOR LEAVE REQUEST -->
    <div class="glass-panel">
        <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Leave Request Submission</h2>
        <form id="leave-form" onsubmit="submitLeave(event)" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">LEAVE TYPE</label>
                <select name="leave_type" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                    <option value="annual">Annual Leave</option>
                    <option value="sick">Sick Leave</option>
                    <option value="unpaid">Unpaid Leave</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">START DATE</label>
                <input type="date" name="start_date" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">END DATE</label>
                <input type="date" name="end_date" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div style="grid-column: span 2;">
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">REASON</label>
                <input type="text" name="reason" placeholder="Enter reason for leave request" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div style="grid-column: span 2; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit" style="margin-top:0.5rem; max-width: 180px;">File Request</button>
            </div>
        </form>
    </div>
</div>

<!-- LEAVE APPROVAL DESK (Only visible to Super Admins & HR Managers) -->
<?php if ($roleId === 1 || $roleId === 3): ?>
<div class="glass-panel" style="margin-bottom: 2.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Pending Leave Clearance Desk</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">Employee</th>
                    <th style="padding:1rem;">Leave Type</th>
                    <th style="padding:1rem;">Duration</th>
                    <th style="padding:1rem;">Reason</th>
                    <th style="padding:1rem; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($leaveRequests)): ?>
                    <?php foreach ($leaveRequests as $req): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding:1.2rem 1rem; font-weight:bold;">
                            <?= Sanitizer::escape($req['first_name'] . ' ' . $req['last_name']) ?>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:normal;"><?= Sanitizer::escape($req['designation_title']) ?></div>
                        </td>
                        <td style="padding:1.2rem 1rem; text-transform: capitalize;"><?= Sanitizer::escape($req['leave_type']) ?></td>
                        <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($req['start_date']) ?> to <?= Sanitizer::escape($req['end_date']) ?></td>
                        <td style="padding:1.2rem 1rem; color:var(--text-muted);"><?= Sanitizer::escape($req['reason']) ?></td>
                        <td style="padding:1.2rem 1rem; text-align:right;">
                            <button onclick="clearLeave(<?= (int)$req['id'] ?>, 'approved')" style="background:var(--color-success); border:none; padding:0.4rem 1rem; border-radius:6px; font-weight:bold; cursor:pointer; color:#000; font-size:0.8rem; margin-right:0.5rem;">Approve</button>
                            <button onclick="clearLeave(<?= (int)$req['id'] ?>, 'rejected')" style="background:var(--color-danger); border:none; padding:0.4rem 1rem; border-radius:6px; font-weight:bold; cursor:pointer; color:#fff; font-size:0.8rem;">Reject</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding:2rem; text-align:center; color:var(--text-muted);">No pending leaves awaiting approval.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- EMPLOYEE ROSTER MATRIX -->
<div class="glass-panel" style="margin-bottom: 2.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Employee Directory & Payroll Portal</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">ID Code</th>
                    <th style="padding:1rem;">Full Name</th>
                    <th style="padding:1rem;">Department / Designation</th>
                    <th style="padding:1rem;">Base Salary</th>
                    <th style="padding:1rem;">Hire Date</th>
                    <th style="padding:1rem;">Status</th>
                    <?php if ($roleId === 1 || $roleId === 3): ?>
                    <th style="padding:1rem; text-align:right;">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $emp): 
                    if ($emp['status'] === 'active') {
                        $badgeBg = 'rgba(52, 211, 153, 0.08)';
                        $badgeColor = 'var(--color-success)';
                    } else {
                        $badgeBg = 'rgba(251, 191, 36, 0.08)';
                        $badgeColor = 'var(--color-warning)';
                    }
                ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--text-primary);"><?= Sanitizer::escape($emp['employee_code']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:600;"><?= Sanitizer::escape($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-secondary);">
                        <?= Sanitizer::escape($emp['department_name']) ?>
                        <div style="font-size:0.75rem; color:var(--text-muted);"><?= Sanitizer::escape($emp['designation_title']) ?> (<?= Sanitizer::escape($emp['grade']) ?>)</div>
                    </td>
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--color-primary);">$<?= number_format($emp['base_salary'], 2) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-muted);"><?= Sanitizer::escape($emp['hire_date']) ?></td>
                    <td style="padding:1.2rem 1rem;">
                        <span style="font-size:0.78rem; font-weight:600; padding:0.3rem 0.8rem; border-radius:30px; background:<?= $badgeBg ?>; color:<?= $badgeColor ?>; border:1px solid rgba(255,255,255,0.05);">
                            <?= ucfirst(Sanitizer::escape($emp['status'])) ?>
                        </span>
                    </td>
                    <?php if ($roleId === 1 || $roleId === 3): ?>
                    <td style="padding:1.2rem 1rem; text-align:right;">
                        <button onclick="openPayrollModal(<?= (int)$emp['id'] ?>, '<?= Sanitizer::escape($emp['first_name'] . ' ' . $emp['last_name']) ?>')" style="background:rgba(56,189,248,0.1); border:1px solid rgba(56,189,248,0.25); padding:0.4rem 1rem; border-radius:6px; color:var(--color-primary); cursor:pointer; font-weight:bold; font-size:0.8rem;">Run Payroll</button>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- PAYROLL COMPUTATION DIALOG BOX (MODAL) -->
<div id="payroll-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); backdrop-filter:blur(5px); z-index:1000; justify-content:center; align-items:center;">
    <div class="glass-panel" style="max-width:460px; width:90%; padding:2.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Process Monthly Salary Slip</h2>
        <p id="payroll-emp-name" style="color:var(--text-secondary); font-size:0.9rem; margin-bottom:2rem;"></p>
        
        <form onsubmit="executePayroll(event)">
            <input type="hidden" id="payroll-emp-id" name="employee_id">
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display:block; font-size:0.78rem; color:var(--text-secondary); margin-bottom:0.3rem;">ALLOWANCES ($)</label>
                    <input type="number" step="0.01" name="allowances" value="0.00" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;">
                </div>
                <div>
                    <label style="display:block; font-size:0.78rem; color:var(--text-secondary); margin-bottom:0.3rem;">DEDUCTIONS ($)</label>
                    <input type="number" step="0.01" name="deductions" value="0.00" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;">
                </div>
                <div>
                    <label style="display:block; font-size:0.78rem; color:var(--text-secondary); margin-bottom:0.3rem;">PERIOD START</label>
                    <input type="date" name="start_period" value="<?= date('Y-m-01') ?>" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                </div>
                <div>
                    <label style="display:block; font-size:0.78rem; color:var(--text-secondary); margin-bottom:0.3rem;">PERIOD END</label>
                    <input type="date" name="end_period" value="<?= date('Y-m-t') ?>" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap: 1rem;">
                <button type="button" onclick="closePayrollModal()" style="background:transparent; border:1px solid var(--border-color); color:var(--text-secondary); padding:0.7rem 1.5rem; border-radius:10px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-submit" style="margin-top:0; max-width: 150px;">Post Ledger</button>
            </div>
        </form>
    </div>
</div>

<!-- CLIENT JAVASCRIPT LOGIC -->
<script>
    // 1. Trigger Attendance Check-In / Check-Out
    async function triggerClock(action) {
        try {
            const res = await fetch('/erpSystem/api/hr/clock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ action: action })
            });
            const result = await res.json();
            if (result.status === 'success') {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Attendance Error: ' + result.error);
            }
        } catch (e) {
            alert('Request failed: ' + e.message);
        }
    }

    // 2. Submit Leave Request
    async function submitLeave(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('/erpSystem/api/hr/apply-leave', {
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
                alert('Leave filing failed: ' + result.error);
            }
        } catch (e) {
            alert('Leave filing failed: ' + e.message);
        }
    }

    // 3. Clear/Process Leave Request (Approve/Reject)
    async function clearLeave(requestId, status) {
        try {
            const res = await fetch('/erpSystem/api/hr/action-leave', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ request_id: requestId, status: status })
            });
            const result = await res.json();
            if (result.status === 'success') {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Clearance failed: ' + result.error);
            }
        } catch (e) {
            alert('Request failed: ' + e.message);
        }
    }

    // 4. Modal management for Payroll Execution
    function openPayrollModal(empId, empName) {
        document.getElementById('payroll-emp-id').value = empId;
        document.getElementById('payroll-emp-name').innerText = "Running calculations for " + empName;
        document.getElementById('payroll-modal').style.display = 'flex';
    }

    function closePayrollModal() {
        document.getElementById('payroll-modal').style.display = 'none';
    }

    async function executePayroll(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('/erpSystem/api/hr/process-payroll', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();
            if (result.status === 'success') {
                alert(result.message);
                closePayrollModal();
                window.location.reload();
            } else {
                alert('Payroll execution aborted: ' + result.error);
            }
        } catch (e) {
            alert('Process failed: ' + e.message);
        }
    }
</script>
