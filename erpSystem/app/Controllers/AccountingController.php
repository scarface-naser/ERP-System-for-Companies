<?php
// =========================================================================
// CONTROLLERS: DYNAMIC CORPORATE LEDGER & BOOKKEEPING CONTROL
// =========================================================================

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Helpers\SessionHelper;
use Exception;

class AccountingController extends Controller {
    /**
     * Show general ledger accounts, dynamic Trial Balance worksheets, and journal books.
     */
    public function index(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();

        try {
            $db = Database::getInstance();

            // 1. Fetch dynamic Trial Balance stats (sum of debits and credits grouped by account)
            $trialBalance = $db->fetchAll("
                SELECT coa.id, coa.code, coa.name, coa.type, coa.subtype,
                       COALESCE(SUM(je.debit), 0.00) as total_debit,
                       COALESCE(SUM(je.credit), 0.00) as total_credit
                FROM chart_of_accounts coa
                LEFT JOIN journal_entries je ON coa.id = je.account_id
                LEFT JOIN journals j ON je.journal_id = j.id AND j.status = 'posted'
                WHERE coa.status = 'active'
                GROUP BY coa.id
                ORDER BY coa.code ASC
            ");

            // Compute ledger net balances depending on account types
            // Assets & Expenses increase on Debit, Liabilities, Equity & Revenues increase on Credit.
            $grandDebit = 0.00;
            $grandCredit = 0.00;

            foreach ($trialBalance as &$row) {
                $type = $row['type'];
                $deb  = (float)$row['total_debit'];
                $cred = (float)$row['total_credit'];

                $row['net_debit']  = 0.00;
                $row['net_credit'] = 0.00;

                if (in_array($type, ['asset', 'expense'])) {
                    $balance = $deb - $cred;
                    if ($balance >= 0) {
                        $row['net_debit'] = $balance;
                    } else {
                        $row['net_credit'] = abs($balance);
                    }
                } else {
                    $balance = $cred - $deb;
                    if ($balance >= 0) {
                        $row['net_credit'] = $balance;
                    } else {
                        $row['net_debit'] = abs($balance);
                    }
                }

                $grandDebit  += $row['net_debit'];
                $grandCredit += $row['net_credit'];
            }

            // 2. Fetch Latest 10 posted Journal entries
            $journals = $db->fetchAll("
                SELECT j.*, u.username as created_by_name 
                FROM journals j
                LEFT JOIN users u ON j.created_by = u.id
                ORDER BY j.entry_date DESC
                LIMIT 10
            ");

            // Render view
            $this->render('Accounting/index', [
                'title'        => 'Corporate Ledger Worksheets & Trial Balance',
                'trialBalance' => $trialBalance,
                'grandDebit'   => $grandDebit,
                'grandCredit'  => $grandCredit,
                'journals'     => $journals
            ]);

        } catch (Exception $e) {
            error_log("Accounting Controller Index Error: " . $e->getMessage());
            $response->html("<h1>Accounting System Error</h1><p>{$e->getMessage()}</p>", 500);
        }
    }
}
