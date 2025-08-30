<?php

class DBClass extends PDO {
    public function __construct($db_type, $db_host, $db_user = null, $db_pass = null, $db_name = null) {
        $dsn = "";
        
        // Use a switch statement to construct the DSN based on the database type.
        switch ($db_type) {
            case 'pgsql':
                // DSN for PostgreSQL
                $dsn = "pgsql:host={$db_host};dbname={$db_name}";
                break;
            case 'mysql':
            default:
                // DSN for MariaDB/MySQL
                $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
                break;
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        
        try {
            parent::__construct($dsn, $db_user, $db_pass, $options);
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Executes a prepared statement and returns the results.
     *
     * @param string $sql SQL query with positional placeholders (e.g., SELECT * FROM users WHERE Id=?).
     * @param array $params An array of parameters to be bound.
     * @return array|null An array of all fetched rows, or null if no results were produced.
     */
    public function safeQuery(string $sql, array $params = []): ?array {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        
        if ($stmt->columnCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_BOTH);
        }
        
        return null;
    }

    /**
     * Executes a prepared statement and returns a single row.
     *
     * @param string $sql SQL query.
     * @param array $params An array of parameters.
     * @return array The first row as an array, or an empty array if no results.
     */
    public function row(string $sql, array $params = []): array {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_BOTH) ?? [];
    }

    /**
     * Executes a prepared statement and returns a single value from the first row.
     *
     * @param string $sql SQL query.
     * @param array $params An array of parameters.
     * @return mixed The first value of the first row, or null if no results.
     */
    public function single(string $sql, array $params = []) {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn(0);
    }
}