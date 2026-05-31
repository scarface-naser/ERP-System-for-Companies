<?php
// =========================================================================
// VIEW: SALES & RECEIVABLES COMMAND
// =========================================================================

use App\Helpers\Sanitizer;
?>
<div class="page-header">
    <div class="breadcrumbs">
        <a href="/erpSystem/dashboard">Dashboard</a>
        <span>&bull;</span>
        <span>Commercial Sales</span>
    </div>
    <h1 style="margin-top: 0.5rem; font-size: 2.2rem; font-weight: 700; background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Commercial Revenue Desk</h1>
    <p style="color: var(--text-secondary); font-size: 0.95rem;">Track estimations, commercial orders, invoice registers, and cash receipt payments posted directly to general ledger accounts.</p>
</div>

<!-- 1. SALES OUTSTANDING SUMMARY CARDS -->
<section class="kpi-grid">
    <div class="glass-panel kpi-card">
        <div class="kpi-data">
            <h3>Invoice Revenue</h3>
            <div class="kpi-value">$22,500.00</div>
            <div class="kpi-trend trend-up">
                <span>Total billings</span>
            </div>
        </div>
        <div class="kpi-icon kpi-icon-success">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
        </div>
    </div>

    <div class="glass-panel kpi-card">
        <div class="kpi-data">
            <h3>Accounts Receivables</h3>
            <div class="kpi-value">$19,500.00</div>
            <div class="kpi-trend trend-down">
                <span>Outstanding credit</span>
            </div>
        </div>
        <div class="kpi-icon" style="background:rgba(239, 68, 68, 0.08); border:1px solid rgba(239, 68, 68, 0.2); color:var(--color-danger)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
    </div>

    <div class="glass-panel kpi-card">
        <div class="kpi-data">
            <h3>Cash Collected</h3>
            <div class="kpi-value">$3,000.00</div>
            <div class="kpi-trend trend-up">
                <span>Bank liquid cash</span>
            </div>
        </div>
        <div class="kpi-icon kpi-icon-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
    </div>
</section>

<!-- INVOICES REGISTER WITH CASH CLEARANCE -->
<div class="glass-panel" style="margin-bottom: 2.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Outstanding Customer Invoice Registers</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">Invoice Code</th>
                    <th style="padding:1rem;">Client / Customer</th>
                    <th style="padding:1rem;">Invoice Date</th>
                    <th style="padding:1rem;">Due Date</th>
                    <th style="padding:1rem;">Total Amount</th>
                    <th style="padding:1rem;">Outstanding Balance</th>
                    <th style="padding:1rem;">Status</th>
                    <th style="padding:1rem; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $inv): 
                    $badgeBg = 'rgba(248, 113, 113, 0.08)';
                    $badgeColor = 'var(--color-danger)';
                    
                    if ($inv['status'] === 'paid') {
                        $badgeBg = 'rgba(52, 211, 153, 0.08)';
                        $badgeColor = 'var(--color-success)';
                    } elseif ($inv['status'] === 'partially_paid') {
                        $badgeBg = 'rgba(251, 191, 36, 0.08)';
                        $badgeColor = 'var(--color-warning)';
                    }
                ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--text-primary);"><?= Sanitizer::escape($inv['invoice_code']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:600;">
                        <?= Sanitizer::escape($inv['customer_name']) ?>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:normal;"><?= Sanitizer::escape($inv['customer_email']) ?></div>
                    </td>
                    <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($inv['invoice_date']) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-muted);"><?= Sanitizer::escape($inv['due_date']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:bold;">$<?= number_format($inv['total_amount'], 2) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:<?= ((float)$inv['balance_amount'] > 0) ? 'var(--color-danger)' : 'var(--color-success)' ?>;">
                        $<?= number_format($inv['balance_amount'], 2) ?>
                    </td>
                    <td style="padding:1.2rem 1rem;">
                        <span style="font-size:0.78rem; font-weight:600; padding:0.3rem 0.8rem; border-radius:30px; background:<?= $badgeBg ?>; color:<?= $badgeColor ?>; border:1px solid rgba(255,255,255,0.05);">
                            <?= str_replace('_', ' ', ucfirst(Sanitizer::escape($inv['status']))) ?>
                        </span>
                    </td>
                    <td style="padding:1.2rem 1rem; text-align:right;">
                        <?php if ((float)$inv['balance_amount'] > 0.00): ?>
                        <button onclick="openPaymentModal(<?= (int)$inv['id'] ?>, '<?= Sanitizer::escape($inv['invoice_code']) ?>', '<?= Sanitizer::escape($inv['customer_name']) ?>', <?= (float)$inv['balance_amount'] ?>)" style="background:var(--color-success); border:none; padding:0.4rem 1rem; border-radius:6px; color:#000; font-weight:bold; font-size:0.8rem; cursor:pointer;">Record Receipt</button>
                        <?php else: ?>
                        <span style="font-size:0.8rem; color:var(--text-muted); font-weight:600;">Cleared</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- PAYMENTS HISTORY LEDGER -->
<div class="glass-panel" style="margin-bottom: 1.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Dynamic Cash Receipts Payment Register</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">Receipt Code</th>
                    <th style="padding:1rem;">Timestamp</th>
                    <th style="padding:1rem;">Source Invoice</th>
                    <th style="padding:1rem;">Client Customer</th>
                    <th style="padding:1rem;">Receipt Method</th>
                    <th style="padding:1rem;">Payment Reference</th>
                    <th style="padding:1rem; text-align:right;">Cash Received</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $pay): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--text-primary);"><?= Sanitizer::escape($pay['payment_code']) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($pay['payment_date']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--color-primary);"><?= Sanitizer::escape($pay['invoice_code']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:500;"><?= Sanitizer::escape($pay['customer_name']) ?></td>
                    <td style="padding:1.2rem 1rem; text-transform: uppercase; font-size:0.8rem; font-weight:600; color:var(--text-secondary);"><?= str_replace('_', ' ', Sanitizer::escape($pay['payment_method'])) ?></td>
                    <td style="padding:1.2rem 1rem; color:var(--text-muted); font-family: monospace;"><?= Sanitizer::escape($pay['reference'] ?? 'N/A') ?></td>
                    <td style="padding:1.2rem 1rem; text-align:right; font-weight:bold; color:var(--color-success);">$<?= number_format($pay['amount'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- PAYMENTS DIALOG BOX (MODAL) -->
<div id="payment-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); backdrop-filter:blur(5px); z-index:1000; justify-content:center; align-items:center;">
    <div class="glass-panel" style="max-width:440px; width:90%; padding:2.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Record Client Cash Receipt</h2>
        <p id="pay-details" style="color:var(--text-secondary); font-size:0.9rem; margin-bottom:2rem;"></p>
        
        <form onsubmit="submitPayment(event)">
            <input type="hidden" id="pay-invoice-id" name="invoice_id">
            
            <div style="display:flex; flex-direction:column; gap: 1.2rem; margin-bottom: 1.8rem;">
                <div>
                    <label style="display:block; font-size:0.78rem; color:var(--text-secondary); margin-bottom:0.3rem;">PAYMENT METHOD</label>
                    <select name="payment_method" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                        <option value="bank_transfer">Bank Transfer (Wire)</option>
                        <option value="cash">Petty Cash</option>
                        <option value="credit_card">Corporate Credit Card</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.78rem; color:var(--text-secondary); margin-bottom:0.3rem;">PAYMENT REFERENCE</label>
                    <input type="text" name="reference" placeholder="e.g. Wire Reference #" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                </div>
                <div>
                    <label style="display:block; font-size:0.78rem; color:var(--text-secondary); margin-bottom:0.3rem;">AMOUNT TO RECEIVE ($)</label>
                    <input type="number" step="0.01" min="0.01" id="pay-amount-input" name="amount" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap: 1rem;">
                <button type="button" onclick="closePaymentModal()" style="background:transparent; border:1px solid var(--border-color); color:var(--text-secondary); padding:0.7rem 1.5rem; border-radius:10px; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-submit" style="margin-top:0; max-width: 150px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);">Record Payment</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPaymentModal(invoiceId, code, client, balance) {
        document.getElementById('pay-invoice-id').value = invoiceId;
        document.getElementById('pay-details').innerText = "Registering cash collection for " + client + " (Invoice: " + code + ")";
        document.getElementById('pay-amount-input').value = balance.toFixed(2);
        document.getElementById('pay-amount-input').max = balance;
        document.getElementById('payment-modal').style.display = 'flex';
    }

    function closePaymentModal() {
        document.getElementById('payment-modal').style.display = 'none';
    }

    async function submitPayment(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('/erpSystem/api/sales/payment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();
            if (result.status === 'success') {
                alert(result.message);
                closePaymentModal();
                window.location.reload();
            } else {
                alert('Payment registry aborted: ' + result.error);
            }
        } catch (e) {
            alert('Request failed: ' + e.message);
        }
    }
</script>
