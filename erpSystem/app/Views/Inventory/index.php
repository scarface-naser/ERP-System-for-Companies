<?php
// =========================================================================
// VIEW: INVENTORY COMMAND DESK
// =========================================================================

use App\Helpers\Sanitizer;
use App\Helpers\SessionHelper;

$roleId = (int)SessionHelper::get('role_id', 0);
?>
<div class="page-header">
    <div class="breadcrumbs">
        <a href="/erpSystem/dashboard">Dashboard</a>
        <span>&bull;</span>
        <span>Inventory Logistics Command</span>
    </div>
    <h1 style="margin-top: 0.5rem; font-size: 2.2rem; font-weight: 700; background: linear-gradient(135deg, #38bdf8 0%, #4f46e5 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Inventory Ledger Command</h1>
    <p style="color: var(--text-secondary); font-size: 0.95rem;">Track product catalogs, monitor SKU levels across warehouses, log damage reports, and trigger atomic warehouse-to-warehouse stock transfers.</p>
</div>

<!-- TOP GRID: ADJUSTMENTS & TRANSFERS -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
    
    <!-- 1. STOCK ADJUSTMENT MODULE -->
    <div class="glass-panel">
        <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Inventory Adjustment Console</h2>
        <form id="adjustment-form" onsubmit="submitAdjustment(event)" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">PRODUCT</label>
                <select name="product_id" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                    <?php foreach ($products as $prod): ?>
                    <option value="<?= (int)$prod['id'] ?>"><?= Sanitizer::escape($prod['name']) ?> (<?= Sanitizer::escape($prod['sku']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">WAREHOUSE</label>
                <select name="warehouse_id" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                    <?php foreach ($warehouses as $wh): ?>
                    <option value="<?= (int)$wh['id'] ?>"><?= Sanitizer::escape($wh['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">ADJUSTMENT TYPE</label>
                <select name="type" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                    <option value="addition">Addition (Stock-In)</option>
                    <option value="deduction">Deduction (Stock-Out)</option>
                    <option value="damage">Damage Report</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">QUANTITY</label>
                <input type="number" name="quantity" min="1" value="1" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">AUDITABLE REASON</label>
                <input type="text" name="reason" placeholder="e.g. Damage during load" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div style="grid-column: span 2; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit" style="margin-top:0.5rem; max-width: 180px;">Adjust Inventory</button>
            </div>
        </form>
    </div>

    <!-- 2. STOCK TRANSFER CONSOLE -->
    <div class="glass-panel">
        <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Warehouse Stock Transfer (Atomic)</h2>
        <form id="transfer-form" onsubmit="submitTransfer(event)" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">SELECT PRODUCT</label>
                <select name="product_id" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                    <?php foreach ($products as $prod): ?>
                    <option value="<?= (int)$prod['id'] ?>"><?= Sanitizer::escape($prod['name']) ?> (<?= Sanitizer::escape($prod['sku']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">SOURCE WAREHOUSE</label>
                <select name="from_warehouse_id" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                    <?php foreach ($warehouses as $wh): ?>
                    <option value="<?= (int)$wh['id'] ?>"><?= Sanitizer::escape($wh['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">TARGET WAREHOUSE</label>
                <select name="to_warehouse_id" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
                    <?php foreach ($warehouses as $wh): ?>
                    <option value="<?= (int)$wh['id'] ?>"><?= Sanitizer::escape($wh['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="grid-column: span 2;">
                <label style="display:block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.3rem;">TRANSFER QUANTITY</label>
                <input type="number" name="quantity" min="1" value="1" style="width:100%; background:rgba(15,23,42,0.6); border:1px solid var(--border-color); color:var(--text-primary); padding:0.7rem; border-radius:10px;" required>
            </div>
            <div style="grid-column: span 2; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit" style="margin-top:0.5rem; max-width: 180px; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">Commit Transfer</button>
            </div>
        </form>
    </div>
</div>

<!-- PRODUCT STOCK MATRIX -->
<div class="glass-panel" style="margin-bottom: 2.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Enterprise Catalog & Stock Matrix</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">SKU</th>
                    <th style="padding:1rem;">Product Name</th>
                    <th style="padding:1rem;">Category</th>
                    <th style="padding:1rem;">Price (Cost)</th>
                    <th style="padding:1rem;">Available Stock</th>
                    <th style="padding:1rem;">Reorder Target</th>
                    <th style="padding:1rem; text-align:right;">Alert Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $prod): 
                    $reorderAlert = ((int)$prod['total_stock'] <= (int)$prod['reorder_level']);
                    $statusBg = $reorderAlert ? 'rgba(248, 113, 113, 0.08)' : 'rgba(52, 211, 153, 0.08)';
                    $statusColor = $reorderAlert ? 'var(--color-danger)' : 'var(--color-success)';
                    $statusLabel = $reorderAlert ? 'REORDER ALERT' : 'STOCK STABLE';
                    
                    // Progressive bar ratio computation
                    $barRatio = ((int)$prod['reorder_level'] > 0) ? min(100, ((int)$prod['total_stock'] / ((int)$prod['reorder_level'] * 2)) * 100) : 100;
                    $barColor = $reorderAlert ? 'var(--color-danger)' : 'var(--color-primary)';
                ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:var(--text-primary);"><?= Sanitizer::escape($prod['sku']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:600;">
                        <?= Sanitizer::escape($prod['name']) ?>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:normal;">Barcode: <?= Sanitizer::escape($prod['barcode'] ?? 'N/A') ?></div>
                    </td>
                    <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($prod['category_name']) ?></td>
                    <td style="padding:1.2rem 1rem; font-weight:500;">
                        $<?= number_format($prod['price'], 2) ?>
                        <div style="font-size:0.75rem; color:var(--text-muted);">Cost: $<?= number_format($prod['cost'], 2) ?></div>
                    </td>
                    <td style="padding:1.2rem 1rem; font-weight:bold; color:<?= $reorderAlert ? 'var(--color-danger)' : 'var(--text-primary)' ?>;">
                        <?= (int)$prod['total_stock'] ?> Units
                        
                        <!-- Simple visual loading progress bar relative to alert thresholds -->
                        <div style="height: 4px; width: 100px; background: rgba(255,255,255,0.06); border-radius: 10px; margin-top:0.4rem; overflow:hidden;">
                            <div style="height:100%; width: <?= $barRatio ?>%; background: <?= $barColor ?>; border-radius:10px;"></div>
                        </div>
                    </td>
                    <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= (int)$prod['reorder_level'] ?> Units</td>
                    <td style="padding:1.2rem 1rem; text-align:right;">
                        <span style="font-size:0.78rem; font-weight:600; padding:0.3rem 0.8rem; border-radius:30px; background:<?= $statusBg ?>; color:<?= $statusColor ?>; border:1px solid rgba(255,255,255,0.05);">
                            <?= $statusLabel ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- AUDIT LOGS FOR STOCK ADJUSTMENTS -->
<div class="glass-panel" style="margin-bottom: 2.5rem;">
    <h2 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem;">Logistics Security Feed (Stock Adjustments)</h2>
    <div style="overflow-x: auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase;">
                    <th style="padding:1rem;">Audit ID</th>
                    <th style="padding:1rem;">Timestamp</th>
                    <th style="padding:1rem;">Product</th>
                    <th style="padding:1rem;">Warehouse</th>
                    <th style="padding:1rem;">Adjustment Action</th>
                    <th style="padding:1rem;">Reason / Memo</th>
                    <th style="padding:1rem;">Operator</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($adjustments)): ?>
                    <?php foreach ($adjustments as $adj): 
                        $badgeBg = 'rgba(56, 189, 248, 0.08)';
                        $badgeColor = 'var(--color-primary)';
                        
                        if ($adj['adjustment_type'] === 'damage') {
                            $badgeBg = 'rgba(248, 113, 113, 0.08)';
                            $badgeColor = 'var(--color-danger)';
                        } elseif ($adj['adjustment_type'] === 'deduction') {
                            $badgeBg = 'rgba(251, 191, 36, 0.08)';
                            $badgeColor = 'var(--color-warning)';
                        } elseif ($adj['adjustment_type'] === 'addition') {
                            $badgeBg = 'rgba(52, 211, 153, 0.08)';
                            $badgeColor = 'var(--color-success)';
                        }
                    ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding:1.2rem 1rem; font-weight:bold;">#<?= (int)$adj['id'] ?></td>
                        <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($adj['created_at']) ?></td>
                        <td style="padding:1.2rem 1rem; font-weight:600;">
                            <?= Sanitizer::escape($adj['product_name']) ?>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:normal;">SKU: <?= Sanitizer::escape($adj['sku']) ?></div>
                        </td>
                        <td style="padding:1.2rem 1rem; color:var(--text-secondary);"><?= Sanitizer::escape($adj['warehouse_name']) ?></td>
                        <td style="padding:1.2rem 1rem;">
                            <span style="font-size:0.78rem; font-weight:600; padding:0.3rem 0.8rem; border-radius:30px; background:<?= $badgeBg ?>; color:<?= $badgeColor ?>; border:1px solid rgba(255,255,255,0.05);">
                                <?= ucfirst(Sanitizer::escape($adj['adjustment_type'])) ?> (<?= (int)$adj['adjusted_quantity'] ?> units)
                            </span>
                        </td>
                        <td style="padding:1.2rem 1rem; color:var(--text-muted);"><?= Sanitizer::escape($adj['reason']) ?></td>
                        <td style="padding:1.2rem 1rem; font-weight:500;"><?= Sanitizer::escape($adj['adjusted_by_name']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="padding:2rem; text-align:center; color:var(--text-muted);">No stock adjustments logged.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- CLIENT JAVASCRIPT LOGIC -->
<script>
    // 1. Submit Stock Adjustment
    async function submitAdjustment(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('/erpSystem/api/inventory/adjust', {
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
                alert('Adjustment failed: ' + result.error);
            }
        } catch (e) {
            alert('Request failed: ' + e.message);
        }
    }

    // 2. Submit Warehouse Stock Transfer
    async function submitTransfer(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('/erpSystem/api/inventory/transfer', {
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
                alert('Transfer failed: ' + result.error);
            }
        } catch (e) {
            alert('Request failed: ' + e.message);
        }
    }
</script>
