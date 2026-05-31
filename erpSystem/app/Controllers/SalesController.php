<?php
// =========================================================================
// CONTROLLERS: COMMERCIAL SALES & FINANCE COMMAND
// =========================================================================

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Helpers\SessionHelper;
use App\Helpers\Sanitizer;
use Exception;

class SalesController extends Controller {
    /**
     * Display quotations, commercial orders, invoice registers, and credit sheets.
     */
    public function index(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();

        try {
            $db = Database::getInstance();

            // 1. Fetch Invoices with Customer Details
            $invoices = $db->fetchAll("
                SELECT i.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone
                FROM invoices i
                JOIN customers c ON i.customer_id = c.id
                ORDER BY i.invoice_date DESC
            ");

            // 2. Fetch Quotations with Customer Details
            $quotations = $db->fetchAll("
                SELECT q.*, c.name as customer_name
                FROM quotations q
                JOIN customers c ON q.customer_id = c.id
                ORDER BY q.quotation_date DESC
            ");

            // 3. Fetch Payments History
            $payments = $db->fetchAll("
                SELECT p.*, i.invoice_code, c.name as customer_name
                FROM payments p
                JOIN invoices i ON p.invoice_id = i.id
                JOIN customers c ON p.customer_id = c.id
                ORDER BY p.payment_date DESC
            ");

            $customers = $db->fetchAll("SELECT * FROM customers");
            $products  = $db->fetchAll("SELECT * FROM products WHERE status = 'active'");

            // Render view
            $this->render('Sales/index', [
                'title'      => 'Commercial Sales & Client Revenue Desk',
                'invoices'   => $invoices,
                'quotations' => $quotations,
                'payments'   => $payments,
                'customers'  => $customers,
                'products'   => $products
            ]);

        } catch (Exception $e) {
            error_log("Sales Controller Index Error: " . $e->getMessage());
            $response->html("<h1>Sales System Error</h1><p>{$e->getMessage()}</p>", 500);
        }
    }

    /**
     * Submit a customer invoice payment and post dynamic double-entry ledger journals atomically!
     */
    public function recordPayment(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        $userId = SessionHelper::get('user_id');

        $invoiceId     = (int)$request->get('invoice_id');
        $paymentAmount = (float)$request->get('amount');
        $method        = $request->get('payment_method');
        $reference     = $request->get('reference', '');

        try {
            if ($paymentAmount <= 0.00) {
                throw new Exception("Payment amount must be greater than zero.");
            }

            $db = Database::getInstance();
            $db->beginTransaction();

            // 1. Fetch target invoice details
            $invoice = $db->fetch("SELECT * FROM invoices WHERE id = :id FOR UPDATE", ['id' => $invoiceId]);
            if (!$invoice) {
                throw new Exception("Invoice record not found.");
            }

            $balance = (float)$invoice['balance_amount'];
            if ($paymentAmount > $balance) {
                throw new Exception("Payment exceeds outstanding balance. Balance: {$balance}.");
            }

            // 2. Insert payment record
            $paymentCode = 'PAY-' . date('Ymd') . '-' . rand(100, 999);
            $db->query("
                INSERT INTO payments (invoice_id, customer_id, payment_code, payment_date, payment_method, amount, reference) 
                VALUES (:invoice_id, :customer_id, :code, NOW(), :method, :amount, :ref)
            ", [
                'invoice_id'  => $invoiceId,
                'customer_id' => $invoice['customer_id'],
                'code'        => $paymentCode,
                'method'      => $method,
                'amount'      => $paymentAmount,
                'ref'         => $reference
            ]);

            // 3. Update invoice payment balances & status
            $newPaid = (float)$invoice['paid_amount'] + $paymentAmount;
            // Balance is generated always stored in MySQL so we just update the paid_amount
            $status = ($newPaid >= (float)$invoice['total_amount']) ? 'paid' : 'partially_paid';

            $db->query("
                UPDATE invoices 
                SET paid_amount = :paid, status = :status 
                WHERE id = :id
            ", [
                'paid'   => $newPaid,
                'status' => $status,
                'id'     => $invoiceId
            ]);

            // 4. Post Journal Entries (Double-Entry Bookkeeping)
            $journalCode = 'JV-REC-' . date('Ymd') . '-' . rand(100, 999);
            $description = "Receipt payment for invoice " . $invoice['invoice_code'] . " [Ref: {$reference}]";

            $db->query("
                INSERT INTO journals (journal_code, entry_date, description, status, created_by) 
                VALUES (:code, NOW(), :desc, 'posted', :by)
            ", [
                'code' => $journalCode,
                'desc' => $description,
                'by'   => $userId
            ]);

            $journalId = $db->lastInsertId();

            // Debit Cash/Bank Account (1020), Credit Accounts Receivable (1100)
            $bankAcc = $db->fetch("SELECT id FROM chart_of_accounts WHERE code = '1020'")['id'] ?? null;
            $arAcc   = $db->fetch("SELECT id FROM chart_of_accounts WHERE code = '1100'")['id'] ?? null;

            if (!$bankAcc || !$arAcc) {
                throw new Exception("Chart of accounts mapping missing for cash (1020) and AR (1100).");
            }

            // Debit Bank Account
            $db->query("
                INSERT INTO journal_entries (journal_id, account_id, debit, credit) 
                VALUES (:j_id, :acc_id, :debit, 0.00)
            ", [
                'j_id'   => $journalId,
                'acc_id' => $bankAcc,
                'debit'  => $paymentAmount
            ]);

            // Credit Accounts Receivable
            $db->query("
                INSERT INTO journal_entries (journal_id, account_id, debit, credit) 
                VALUES (:j_id, :acc_id, 0.00, :credit)
            ", [
                'j_id'   => $journalId,
                'acc_id' => $arAcc,
                'credit' => $paymentAmount
            ]);

            $db->commit();
            $response->json(['status' => 'success', 'message' => 'Payment registered successfully. Ledger entries updated.']);

        } catch (Exception $e) {
            $db->rollBack();
            $response->json(['status' => 'failed', 'error' => $e->getMessage()], 400);
        }
    }
}
