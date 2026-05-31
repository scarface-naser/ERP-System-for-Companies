<?php
// =========================================================================
// CONTROLLERS: INVENTORY & WAREHOUSING COMMAND CONTROLLER
// =========================================================================

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Models\Product;
use App\Services\StockService;
use App\Helpers\SessionHelper;
use Exception;

class InventoryController extends Controller {
    /**
     * Display the dynamic stock ledger, catalog controls, and warehouse allocations.
     */
    public function index(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();

        try {
            $db = Database::getInstance();

            // 1. Fetch products stock ledger
            $products = Product::getStockLedger();

            // 2. Fetch warehouses & categories for select dropdown options
            $warehouses = $db->fetchAll("SELECT * FROM warehouses");
            $categories = $db->fetchAll("SELECT * FROM categories");

            // 3. Fetch historical audit logs for adjustments
            $adjustments = Product::getAdjustments();

            // Render view template
            $this->render('Inventory/index', [
                'title'       => 'Corporate Inventory Command Ledger',
                'products'    => $products,
                'warehouses'  => $warehouses,
                'categories'  => $categories,
                'adjustments' => $adjustments
            ]);

        } catch (Exception $e) {
            error_log("Inventory Controller Error: " . $e->getMessage());
            $response->html("<h1>Inventory System Error</h1><p>{$e->getMessage()}</p>", 500);
        }
    }

    /**
     * Handle POST stock adjustment requests (additions, deductions, damages).
     */
    public function adjust(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        $userId = SessionHelper::get('user_id');

        $warehouseId = (int)$request->get('warehouse_id');
        $productId   = (int)$request->get('product_id');
        $quantity    = (int)$request->get('quantity');
        $type        = $request->get('type');
        $reason      = $request->get('reason');

        try {
            if ($quantity <= 0) {
                throw new Exception("Adjustment quantity must be greater than zero.");
            }
            if (empty($reason)) {
                throw new Exception("Auditable reason is required for adjustments.");
            }

            $service = new StockService();
            $service->adjustStock($warehouseId, $productId, $quantity, $type, $reason, $userId);

            $response->json(['status' => 'success', 'message' => 'Stock level adjusted successfully. Logs recorded.']);

        } catch (Exception $e) {
            $response->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Handle POST stock transfers between warehouses atomically.
     */
    public function transfer(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        $userId = SessionHelper::get('user_id');

        $fromWarehouseId = (int)$request->get('from_warehouse_id');
        $toWarehouseId   = (int)$request->get('to_warehouse_id');
        $productId       = (int)$request->get('product_id');
        $quantity        = (int)$request->get('quantity');

        try {
            if ($quantity <= 0) {
                throw new Exception("Transfer quantity must be greater than zero.");
            }

            $service = new StockService();
            $service->transferStock($fromWarehouseId, $toWarehouseId, $productId, $quantity, $userId);

            $response->json(['status' => 'success', 'message' => 'Inventory transferred successfully. Transactions committed.']);

        } catch (Exception $e) {
            $response->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
