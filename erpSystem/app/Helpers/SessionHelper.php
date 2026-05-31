<?php
// =========================================================================
// APPLICATION UTILITIES: SECURE SESSION OPERATIONS WRAPPER
// =========================================================================

namespace App\Helpers;

class SessionHelper {
    /**
     * Start secure session. Checks headers and applies strict OWASP attributes.
     */
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            $configPath = dirname(__DIR__, 2) . '/config/app.php';
            $config = file_exists($configPath) ? (require $configPath)['session'] : [];

            // Configure secure session cookie parameters
            session_name($config['name'] ?? 'ERP_SESSION_ID');
            session_set_cookie_params([
                'lifetime' => $config['timeout'] ?? 900,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $config['secure'] ?? false,
                'httponly' => $config['http_only'] ?? true,
                'samesite' => $config['samesite'] ?? 'Strict'
            ]);

            session_start();

            // Refresh session key dynamically to prevent session fixation attacks
            if (!isset($_SESSION['CREATED'])) {
                $_SESSION['CREATED'] = time();
            } elseif (time() - $_SESSION['CREATED'] > 300) { // Rotate key every 5 mins
                session_regenerate_id(true);
                $_SESSION['CREATED'] = time();
            }

            // Check session timeout inactivity
            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > ($config['timeout'] ?? 900))) {
                self::destroy();
                // Send JSON session timeout response or dynamic redirect
                if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/api')) {
                    http_response_code(401);
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['error' => 'Session expired due to inactivity', 'expired' => true]);
                    exit;
                }
                header("Location: /erpSystem/login?timeout=1");
                exit;
            }
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }

    /**
     * Set variable in session.
     */
    public static function set(string $key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Get variable from session.
     */
    public static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if variable exists in session.
     */
    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Remove specific variable from session.
     */
    public static function remove(string $key): void {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy current session (for logout/timeout).
     */
    public static function destroy(): void {
        if (session_status() === PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
            self::start();
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            session_destroy();
        }
    }
}
