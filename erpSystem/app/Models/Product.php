<?php
// =========================================================================
// DOMAIN MODELS: INVENTORY PRODUCT ACTIVE RECORD DATA MAPPER
// =========================================================================

namespace App\Models;

use App\Core\Database;

class Product {
    /**
     * Retrieve all products from the catalog with their parent categories.
     */
    public static function getAll(): array {
        $db = Database::getInstance();
        $sql = "
            SELECT p.*, c.name as category_name 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            ORDER BY p.name ASC
        ";
        return $db->fetchAll($sql);
    }

    /**
     * Retrieve detailed catalog entries along with current aggregated warehouse stock counts.
     */
    public static function getStockLedger(): array {
        $db = Database::getInstance();
        $sql = "
            SELECT p.id, p.sku, p.barcode, p.name, p.price, p.cost, p.reorder_level, p.status, c.name as category_name,
                   COALESCE(SUM(ws.quantity), 0) as total_stock
            FROM products p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN warehouse_stock ws ON p.id = ws.product_id
            GROUP BY p.id
            ORDER BY p.name ASC
        ";
        return $db->fetchAll($sql);
    }

    /**
     * Fetch the breakdown of quantities of a product across all warehouses.
     */
    public static function getWarehouseBreakdown(int $productId): array {
        $db = Database::getInstance();
        $sql = "
            SELECT w.name as warehouse_name, ws.quantity 
            FROM warehouse_stock ws
            JOIN warehouses w ON ws.warehouse_id = w.id
            WHERE ws.product_id = :product_id
        ";
        return $db->fetchAll($sql, ['product_id' => $productId]);
    }

    /**
     * Fetch historical stock adjustments for auditing.
     */
    public static function getAdjustments(): array {
        $db = Database::getInstance();
        $sql = "
            SELECT sa.*, p.name as product_name, p.sku, w.name as warehouse_name, u.username as adjusted_by_name
            FROM stock_adjustments sa
            JOIN products p ON sa.product_id = p.id
            JOIN warehouses w ON sa.warehouse_id = w.id
            JOIN users u ON sa.adjusted_by = u.id
            ORDER BY sa.created_at DESC
            LIMIT 15
        ";
        return $db->fetchAll($sql);
    }
}
