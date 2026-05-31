<?php
// =========================================================================
// SERVICES: INVENTORY & LOGISTICS STOCK CONTROLLER
// =========================================================================

namespace App\Services;

use App\Core\Database;
use Exception;

class StockService {
    /**
     * Perform atomic stock adjustments (additions, deductions, damages).
     */
    public function adjustStock(int $warehouseId, int $productId, int $adjustedQty, string $type, string $reason, int $userId): void {
        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            // 1. Fetch current stock levels in the target warehouse
            $stock = $db->fetch("
                SELECT * FROM warehouse_stock 
                WHERE warehouse_id = :w_id AND product_id = :p_id
            ", ['w_id' => $warehouseId, 'p_id' => $productId]);

            $currentQty = $stock ? (int)$stock['quantity'] : 0;
            $newQty     = $currentQty;

            if ($type === 'addition') {
                $newQty += $adjustedQty;
            } elseif ($type === 'deduction' || $type === 'damage') {
                if ($currentQty < $adjustedQty) {
                    throw new Exception("Insufficient stock in warehouse for this deduction. Available: {$currentQty}.");
                }
                $newQty -= $adjustedQty;
            } else {
                throw new Exception("Invalid stock adjustment type: {$type}.");
            }

            // 2. Insert or update stock record
            if ($stock) {
                $db->query("
                    UPDATE warehouse_stock 
                    SET quantity = :qty 
                    WHERE warehouse_id = :w_id AND product_id = :p_id
                ", ['qty' => $newQty, 'w_id' => $warehouseId, 'p_id' => $productId]);
            } else {
                $db->query("
                    INSERT INTO warehouse_stock (warehouse_id, product_id, quantity) 
                    VALUES (:w_id, :p_id, :qty)
                ", ['w_id' => $warehouseId, 'p_id' => $productId, 'qty' => $newQty]);
            }

            // 3. Record Security Audit Log
            $db->query("
                INSERT INTO stock_adjustments (warehouse_id, product_id, adjusted_quantity, adjustment_type, reason, adjusted_by) 
                VALUES (:w_id, :p_id, :adj_qty, :type, :reason, :by)
            ", [
                'w_id'    => $warehouseId,
                'p_id'    => $productId,
                'adj_qty' => $adjustedQty,
                'type'    => $type,
                'reason'  => $reason,
                'by'      => $userId
            ]);

            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Atomic stock transfer between two warehouses inside a single transaction block.
     */
    public function transferStock(int $fromWhId, int $toWhId, int $productId, int $qty, int $userId): void {
        if ($fromWhId === $toWhId) {
            throw new Exception("Source and destination warehouses cannot be the same.");
        }

        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            // 1. Verify source stock levels
            $sourceStock = $db->fetch("
                SELECT * FROM warehouse_stock 
                WHERE warehouse_id = :w_id AND product_id = :p_id
            ", ['w_id' => $fromWhId, 'p_id' => $productId]);

            if (!$sourceStock || (int)$sourceStock['quantity'] < $qty) {
                $available = $sourceStock ? $sourceStock['quantity'] : 0;
                throw new Exception("Insufficient stock in source warehouse. Available: {$available}.");
            }

            // 2. Deduct from source warehouse
            $db->query("
                UPDATE warehouse_stock 
                SET quantity = quantity - :qty 
                WHERE warehouse_id = :w_id AND product_id = :p_id
            ", ['qty' => $qty, 'w_id' => $fromWhId, 'p_id' => $productId]);

            // 3. Add to destination warehouse (insert if no catalog link exists)
            $destStock = $db->fetch("
                SELECT * FROM warehouse_stock 
                WHERE warehouse_id = :w_id AND product_id = :p_id
            ", ['w_id' => $toWhId, 'p_id' => $productId]);

            if ($destStock) {
                $db->query("
                    UPDATE warehouse_stock 
                    SET quantity = quantity + :qty 
                    WHERE warehouse_id = :w_id AND product_id = :p_id
                ", ['qty' => $qty, 'w_id' => $toWhId, 'p_id' => $productId]);
            } else {
                $db->query("
                    INSERT INTO warehouse_stock (warehouse_id, product_id, quantity) 
                    VALUES (:w_id, :p_id, :qty)
                ", ['w_id' => $toWhId, 'p_id' => $productId, 'qty' => $qty]);
            }

            // 4. Record stock transfer ledger logs
            $db->query("
                INSERT INTO stock_transfers (from_warehouse_id, to_warehouse_id, transfer_date, status, created_by) 
                VALUES (:from, :to, NOW(), 'completed', :by)
            ", [
                'from' => $fromWhId,
                'to'   => $toWhId,
                'by'   => $userId
            ]);

            $transferId = $db->lastInsertId();

            $db->query("
                INSERT INTO stock_transfer_items (transfer_id, product_id, quantity) 
                VALUES (:t_id, :p_id, :qty)
            ", [
                't_id' => $transferId,
                'p_id' => $productId,
                'qty'  => $qty
            ]);

            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
