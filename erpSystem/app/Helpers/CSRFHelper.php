<?php
// =========================================================================
// APPLICATION SECURITY: CSRF PROTECTION ENGINE
// =========================================================================

namespace App\Helpers;

class CSRFHelper {
    /**
     * Generate a cryptographically secure CSRF token, store it in the session, and return it.
     */
    public static function generate(): string {
        SessionHelper::start();
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    /**
     * Retrieve the current CSRF token from the session, or generate one if missing.
     */
    public static function getToken(): string {
        SessionHelper::start();
        if (!isset($_SESSION['csrf_token'])) {
            return self::generate();
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate if the submitted token matches the session token.
     * Uses timing-attack resistant comparison (hash_equals).
     */
    public static function validate(?string $token): bool {
        SessionHelper::start();
        if (empty($token) || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Generate a hidden HTML input field containing the current CSRF token.
     * Ideal to drop directly into forms: <?= CSRFHelper::csrfField() ?>
     */
    public static function csrfField(): string {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
