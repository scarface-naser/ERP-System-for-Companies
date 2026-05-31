<?php
// =========================================================================
// CONTROLLERS: SECURE ENTERPRISE AUTHENTICATION CONTROLLER (OWASP COMPLIANT)
// =========================================================================

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Helpers\SessionHelper;
use App\Helpers\CSRFHelper;

class AuthController extends Controller {
    /**
     * Display the premium, beautifully designed glassmorphic login screen.
     */
    public function showLogin(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        if (SessionHelper::has('user_id')) {
            $response->redirect('/erpSystem/dashboard');
        }

        // Generate a fresh CSRF token
        $csrfToken = CSRFHelper::getToken();

        // Get status flags from URL parameters
        $unauthorized = $request->get('unauthorized') ? true : false;
        $timeout      = $request->get('timeout') ? true : false;
        $errorMsg     = $request->get('error');

        // Capture any error messages
        $statusMessage = '';
        $messageType   = 'info';

        if ($unauthorized) {
            $statusMessage = 'Administrative clearance required. Please log in.';
            $messageType   = 'warning';
        } elseif ($timeout) {
            $statusMessage = 'Your session has expired due to inactivity. Please log in again.';
            $messageType   = 'warning';
        } elseif ($errorMsg === 'csrf') {
            $statusMessage = 'Security validation failed (CSRF). Please try again.';
            $messageType   = 'danger';
        } elseif ($errorMsg === 'locked') {
            $statusMessage = 'This account has been locked due to too many failed attempts. Try again in 15 minutes.';
            $messageType   = 'danger';
        } elseif ($errorMsg === 'invalid') {
            $statusMessage = 'Invalid username or password.';
            $messageType   = 'danger';
        } elseif ($errorMsg === 'suspended') {
            $statusMessage = 'Your account has been suspended. Please contact system support.';
            $messageType   = 'danger';
        }

        $response->html("
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Enterprise ERP Platform - Secure Authentication</title>
                <link rel='preconnect' href='https://fonts.googleapis.com'>
                <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                <link href='https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap' rel='stylesheet'>
                <style>
                    :root {
                        --bg-gradient: radial-gradient(circle at 10% 20%, rgb(15, 23, 42) 0%, rgb(9, 13, 26) 90%);
                        --card-bg: rgba(30, 41, 59, 0.45);
                        --card-border: rgba(255, 255, 255, 0.08);
                        --primary: #38bdf8;
                        --primary-glow: rgba(56, 189, 248, 0.4);
                        --text: #f8fafc;
                        --text-muted: #94a3b8;
                    }
                    * {
                        box-sizing: border-box;
                        margin: 0;
                        padding: 0;
                    }
                    body {
                        font-family: 'Outfit', sans-serif;
                        background: var(--bg-gradient);
                        color: var(--text);
                        min-height: 100vh;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        overflow-x: hidden;
                        padding: 1.5rem;
                    }
                    .glow-orb {
                        position: absolute;
                        width: 500px;
                        height: 500px;
                        border-radius: 50%;
                        background: radial-gradient(circle, rgba(56, 189, 248, 0.12) 0%, rgba(56, 189, 248, 0) 70%);
                        z-index: 1;
                        pointer-events: none;
                    }
                    .orb-left { top: -10%; left: -10%; }
                    .orb-right { bottom: -10%; right: -10%; }

                    .login-container {
                        position: relative;
                        z-index: 10;
                        width: 100%;
                        max-width: 440px;
                    }
                    .brand {
                        text-align: center;
                        margin-bottom: 2rem;
                    }
                    .brand h2 {
                        font-size: 2rem;
                        font-weight: 700;
                        letter-spacing: -0.02em;
                        background: linear-gradient(135deg, #38bdf8, #818cf8);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        margin-bottom: 0.3rem;
                    }
                    .brand p {
                        font-size: 0.95rem;
                        color: var(--text-muted);
                    }
                    .glass-card {
                        background: var(--card-bg);
                        backdrop-filter: blur(20px);
                        -webkit-backdrop-filter: blur(20px);
                        border: 1px solid var(--card-border);
                        border-radius: 24px;
                        padding: 3rem 2.5rem;
                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                    }
                    h3 {
                        font-size: 1.35rem;
                        font-weight: 600;
                        margin-bottom: 1.5rem;
                        letter-spacing: -0.01em;
                    }
                    .alert {
                        padding: 1rem 1.2rem;
                        border-radius: 12px;
                        font-size: 0.88rem;
                        line-height: 1.4;
                        margin-bottom: 1.8rem;
                        display: flex;
                        align-items: center;
                        gap: 0.8rem;
                        border-left: 4px solid transparent;
                    }
                    .alert-warning {
                        background: rgba(245, 158, 11, 0.1);
                        color: #f59e0b;
                        border-left-color: #f59e0b;
                        border-top: 1px solid rgba(245, 158, 11, 0.15);
                        border-right: 1px solid rgba(245, 158, 11, 0.15);
                        border-bottom: 1px solid rgba(245, 158, 11, 0.15);
                    }
                    .alert-danger {
                        background: rgba(239, 68, 68, 0.1);
                        color: #ef4444;
                        border-left-color: #ef4444;
                        border-top: 1px solid rgba(239, 68, 68, 0.15);
                        border-right: 1px solid rgba(239, 68, 68, 0.15);
                        border-bottom: 1px solid rgba(239, 68, 68, 0.15);
                    }
                    .form-group {
                        margin-bottom: 1.5rem;
                        position: relative;
                    }
                    .form-group label {
                        display: block;
                        font-size: 0.85rem;
                        font-weight: 500;
                        color: var(--text-muted);
                        margin-bottom: 0.5rem;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                    }
                    .input-wrapper {
                        position: relative;
                    }
                    input[type='text'], input[type='password'] {
                        width: 100%;
                        background: rgba(15, 23, 42, 0.6);
                        border: 1px solid rgba(255, 255, 255, 0.12);
                        padding: 0.9rem 1.2rem;
                        border-radius: 12px;
                        color: var(--text);
                        font-family: inherit;
                        font-size: 0.95rem;
                        transition: all 0.2s ease-in-out;
                    }
                    input:focus {
                        outline: none;
                        border-color: var(--primary);
                        box-shadow: 0 0 0 4px var(--primary-glow);
                        background: rgba(15, 23, 42, 0.8);
                    }
                    .btn-submit {
                        width: 100%;
                        background: linear-gradient(135deg, #38bdf8 0%, #4f46e5 100%);
                        color: var(--text);
                        border: none;
                        padding: 1rem;
                        border-radius: 12px;
                        font-family: inherit;
                        font-size: 1rem;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.25s ease;
                        margin-top: 1rem;
                        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
                    }
                    .btn-submit:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 6px 20px rgba(56, 189, 248, 0.45);
                    }
                    .btn-submit:active {
                        transform: translateY(0);
                    }
                    .footer-links {
                        margin-top: 2rem;
                        text-align: center;
                        font-size: 0.85rem;
                        color: var(--text-muted);
                    }
                    .footer-links a {
                        color: var(--primary);
                        text-decoration: none;
                        transition: color 0.2s;
                    }
                    .footer-links a:hover {
                        color: #7dd3fc;
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <div class='glow-orb orb-left'></div>
                <div class='glow-orb orb-right'></div>

                <div class='login-container'>
                    <div class='brand'>
                        <h2>Enterprise ERP System</h2>
                        <p>Production-Grade Management Platform</p>
                    </div>

                    <div class='glass-card'>
                        <h3>Secure Authentication</h3>
                        
                        " . (!empty($statusMessage) ? "
                        <div class='alert alert-{$messageType}'>
                            <div>{$statusMessage}</div>
                        </div>
                        " : "") . "

                        <form action='/erpSystem/login' method='POST'>
                            " . CSRFHelper::csrfField() . "
                            
                            <div class='form-group'>
                                <label for='username'>Username or Email</label>
                                <input type='text' id='username' name='username' autocomplete='username' required placeholder='Enter account username'>
                            </div>

                            <div class='form-group'>
                                <label for='password'>Password</label>
                                <input type='password' id='password' name='password' autocomplete='current-password' required placeholder='Enter security password'>
                            </div>

                            <button type='submit' class='btn-submit'>Verify Credentials</button>
                        </form>

                        <div class='footer-links'>
                            Having trouble logging in? <a href='#'>Contact administrator</a>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ");
    }

    /**
     * Process authentication dynamic credentials (OWASP compliance).
     */
    public function login(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();

        // 1. Verify CSRF Token (Crucial for OWASP compliance)
        $csrfTokenSubmitted = $request->get('csrf_token');
        if (!CSRFHelper::validate($csrfTokenSubmitted)) {
            $this->handleFailedAttempt(null, 'csrf', $request, $response);
            return;
        }

        $usernameInput = trim($request->get('username', ''));
        $passwordInput = $request->get('password', '');

        if (empty($usernameInput) || empty($passwordInput)) {
            $this->handleFailedAttempt(null, 'invalid', $request, $response);
            return;
        }

        try {
            $db = Database::getInstance();

            // 2. Fetch User Profile
            $user = $db->fetch("
                SELECT u.*, r.name as role_name 
                FROM users u
                JOIN roles r ON u.role_id = r.id
                WHERE u.username = :username OR u.email = :email
            ", [
                'username' => $usernameInput,
                'email'    => $usernameInput
            ]);

            if (!$user) {
                // Return generic error to prevent user enumeration attacks
                $this->handleFailedAttempt(null, 'invalid', $request, $response);
                return;
            }

            $userId = (int)$user['id'];

            // 3. Check Account Lockout
            if ($user['locked_until'] !== null) {
                $lockTime = strtotime($user['locked_until']);
                if (time() < $lockTime) {
                    $this->handleFailedAttempt($userId, 'locked', $request, $response);
                    return;
                } else {
                    // Lock has expired, reset lock parameters
                    $db->query("UPDATE users SET locked_until = NULL, login_attempts = 0 WHERE id = :id", ['id' => $userId]);
                }
            }

            // 4. Validate Account Status
            if ($user['status'] !== 'active') {
                $this->handleFailedAttempt($userId, $user['status'] === 'suspended' ? 'suspended' : 'invalid', $request, $response);
                return;
            }

            // 5. Verify Password
            if (password_verify($passwordInput, $user['password_hash'])) {
                // Success! Reset login attempts
                $db->query("UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = :id", ['id' => $userId]);

                // Establish safe session identifiers
                SessionHelper::set('user_id', $userId);
                SessionHelper::set('username', $user['username']);
                SessionHelper::set('email', $user['email']);
                SessionHelper::set('role_id', (int)$user['role_id']);
                SessionHelper::set('role_name', $user['role_name']);

                // Record dynamic audit trail/activity logs
                $db->query("
                    INSERT INTO activity_logs (user_id, action, ip_address, user_agent, entity_name, entity_id) 
                    VALUES (:user_id, :action, :ip_address, :user_agent, :entity_name, :entity_id)
                ", [
                    'user_id'     => $userId,
                    'action'      => 'User Login (Success)',
                    'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                    'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                    'entity_name' => 'users',
                    'entity_id'   => $userId
                ]);

                if ($request->expectsJson()) {
                    $response->json(['status' => 'success', 'redirect' => '/erpSystem/dashboard']);
                } else {
                    $response->redirect('/erpSystem/dashboard');
                }
            } else {
                // Password incorrect!
                $attempts = (int)$user['login_attempts'] + 1;
                
                if ($attempts >= 5) {
                    // Lock user account for 15 minutes
                    $lockedUntil = date('Y-m-d H:i:s', time() + 900);
                    $db->query("
                        UPDATE users 
                        SET login_attempts = :attempts, locked_until = :locked_until 
                        WHERE id = :id
                    ", [
                        'attempts'     => $attempts,
                        'locked_until' => $lockedUntil,
                        'id'           => $userId
                    ]);
                    
                    // Log the security lockout event
                    $db->query("
                        INSERT INTO activity_logs (user_id, action, ip_address, user_agent, entity_name, entity_id) 
                        VALUES (:user_id, :action, :ip_address, :user_agent, :entity_name, :entity_id)
                    ", [
                        'user_id'     => $userId,
                        'action'      => 'User Lockout Triggered',
                        'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                        'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                        'entity_name' => 'users',
                        'entity_id'   => $userId
                    ]);

                    $this->handleFailedAttempt($userId, 'locked', $request, $response);
                } else {
                    // Just increment attempts
                    $db->query("UPDATE users SET login_attempts = :attempts WHERE id = :id", [
                        'attempts' => $attempts,
                        'id'       => $userId
                    ]);

                    // Log failed password event
                    $db->query("
                        INSERT INTO activity_logs (user_id, action, ip_address, user_agent, entity_name, entity_id) 
                        VALUES (:user_id, :action, :ip_address, :user_agent, :entity_name, :entity_id)
                    ", [
                        'user_id'     => $userId,
                        'action'      => 'User Login (Failed Password)',
                        'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                        'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                        'entity_name' => 'users',
                        'entity_id'   => $userId
                    ]);

                    $this->handleFailedAttempt($userId, 'invalid', $request, $response);
                }
            }

        } catch (\Exception $e) {
            error_log("Authentication Controller System Error: " . $e->getMessage());
            if ($request->expectsJson()) {
                $response->json(['error' => 'A system server error occurred during authentication.'], 500);
            } else {
                $response->html("<h1>Server Error</h1><p>Authenication aborted due to system failure.</p>", 500);
            }
        }
    }

    /**
     * Helper to process failed attempts, supporting both JSON API and web routing redirects.
     */
    private function handleFailedAttempt(?int $userId, string $errorType, Request $request, Response $response): void {
        if ($request->expectsJson()) {
            $response->json([
                'status'  => 'failed',
                'error'   => $errorType,
                'message' => $this->getErrorMessage($errorType)
            ], 401);
        } else {
            $response->redirect("/erpSystem/login?error={$errorType}");
        }
    }

    /**
     * Get security alert messages.
     */
    private function getErrorMessage(string $type): string {
        switch ($type) {
            case 'csrf': return 'Security verification failed (CSRF token missing or mismatch).';
            case 'locked': return 'This account has been locked due to too many failed attempts. Try again in 15 minutes.';
            case 'suspended': return 'Your account is suspended. Please contact the system administrator.';
            case 'invalid': default: return 'Invalid username or password.';
        }
    }

    /**
     * Terminate the session securely, logging the exit event.
     */
    public function logout(Request $request, Response $response, array $params = []): void {
        SessionHelper::start();
        $userId = SessionHelper::get('user_id');

        if ($userId) {
            try {
                $db = Database::getInstance();
                // Record dynamic audit log
                $db->query("
                    INSERT INTO activity_logs (user_id, action, ip_address, user_agent, entity_name, entity_id) 
                    VALUES (:user_id, :action, :ip_address, :user_agent, :entity_name, :entity_id)
                ", [
                    'user_id'     => $userId,
                    'action'      => 'User Logout',
                    'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                    'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                    'entity_name' => 'users',
                    'entity_id'   => $userId
                ]);
            } catch (\Exception $e) {
                error_log("Logout Logging Error: " . $e->getMessage());
            }
        }

        SessionHelper::destroy();

        if ($request->expectsJson()) {
            $response->json(['status' => 'success', 'redirect' => '/erpSystem/login']);
        } else {
            $response->redirect('/erpSystem/login');
        }
    }
}
