<?php
// =========================================================================
// CUSTOM FRAMEWORK CORE: DATABASE CLASS (PDO SINGLETON WRAPPER)
// =========================================================================

namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Database {
    private static ?Database $instance = null;
    private ?PDO $pdo = null;

    /**
     * Private constructor to enforce Singleton design pattern.
     */
    private function __construct() {
        $configPath = dirname(__DIR__, 2) . '/config/database.php';
        if (!file_exists($configPath)) {
            throw new Exception("Database configuration file not found at: {$configPath}");
        }

        $config = require $configPath;

        $dsn = sprintf(
            "mysql:host=%s;dbname=%s;charset=%s",
            $config['host'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            $this->logError("Database Connection Failed: " . $e->getMessage());
            throw new Exception("Database connection error. Please contact the administrator.");
        }
    }

    /**
     * Get the single class instance.
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Expose the raw PDO connection if advanced options are required.
     */
    public function getConnection(): PDO {
        return $this->pdo;
    }

    /**
     * Execute a SQL query with prepared parameters.
     * Use this for SELECT, INSERT, UPDATE, DELETE queries.
     * Protects natively against SQL Injection (SQLi).
     */
    public function query(string $sql, array $params = []): \PDOStatement {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError("Query Execution Error: " . $e->getMessage() . " | SQL: " . $sql);
            throw new Exception("Database query execution error. Query has been aborted.");
        }
    }

    /**
     * Fetch all records matching the query.
     */
    public function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Fetch a single record matching the query.
     */
    public function fetch(string $sql, array $params = []): ?array {
        $result = $this->query($sql, $params)->fetch();
        return $result ? $result : null;
    }

    /**
     * Get the ID of the last inserted row.
     */
    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begin a database transaction (highly recommended for multiple inserts/updates).
     */
    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit the active transaction.
     */
    public function commit(): bool {
        return $this->pdo->commit();
    }

    /**
     * Rollback the active transaction.
     */
    public function rollBack(): bool {
        return $this->pdo->rollBack();
    }

    /**
     * Log database errors into the application log directory.
     */
    private function logError(string $message): void {
        $logDir = dirname(__DIR__, 2) . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/database_errors.log';
        $timestamp = date('[Y-m-d H:i:s]');
        error_log("{$timestamp} {$message}\n", 3, $logFile);
    }
}
