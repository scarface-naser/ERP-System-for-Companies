<?php
// =========================================================================
// APPLICATION CONFIGURATION
// =========================================================================

return [
    'name'        => 'Enterprise ERP Platform',
    'env'         => 'development', // 'development' or 'production'
    'url'         => 'http://localhost/erpSystem',
    'timezone'    => 'UTC',
    'app_key'     => 'base64:ERP_SECRET_KEY_PRODUCTION_GRADE_2026_OWASP_COMPLIANT',
    'session'     => [
        'timeout'  => 900, // 15 minutes of inactivity
        'name'     => 'ERP_SESSION_ID',
        'secure'   => false, // Set to true if running under HTTPS
        'http_only'=> true,
        'samesite' => 'Strict',
    ],
    'security'    => [
        'rate_limit_max'    => 60, // Maximum requests per minute
        'rate_limit_period' => 60, // Per 60 seconds
    ]
];
