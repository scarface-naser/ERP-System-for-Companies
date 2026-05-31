<?php
// =========================================================================
// APPLICATION UTILITIES: INPUT/OUTPUT SANITIZATION HELPER
// =========================================================================

namespace App\Helpers;

class Sanitizer {
    /**
     * Escape HTML output dynamically. Native XSS defense.
     */
    public static function escape(string $value): string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize integer variables.
     */
    public static function cleanInt($value): int {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize float/decimal variables.
     */
    public static function cleanFloat($value): float {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Escape strings for CSV headers/contents to avoid injection attacks in Excel.
     */
    public static function escapeCsv(string $value): string {
        $value = trim($value);
        // Formula injection protection (=, +, -, @)
        if (in_array(substr($value, 0, 1), ['=', '+', '-', '@'])) {
            return "'" . $value;
        }
        return $value;
    }
}
