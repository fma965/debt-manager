<?php

class DB
{
    private $pdo;

    public function __construct()
    {
        // Include the configuration file
        require_once __DIR__ . '/../../config.php';

        $dsn = '';
        $user = '';
        $pass = '';

        try {
            // Configure the connection based on the selected driver
            if (DB_DRIVER === 'mysql') {
                $dsn = 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB . ';charset=utf8mb4';
                $user = MYSQL_USER;
                $pass = MYSQL_PASS;
            } elseif (DB_DRIVER === 'pgsql') {
                $dsn = 'pgsql:host=' . PGSQL_HOST . ';dbname=' . PGSQL_DB;
                $user = PGSQL_USER;
                $pass = PGSQL_PASS;
            } else {
                throw new Exception("Unsupported database driver selected.");
            }

            // PDO connection options
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
            ];

            // Create the PDO instance
            $this->pdo = new PDO($dsn, $user, $pass, $options);

        } catch (PDOException $e) {
            // You should handle this more gracefully in a real application
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Executes a prepared statement and returns the result.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return PDOStatement The executed PDOStatement object.
     */
    public function safeQuery(string $sql, array $params = [])
    {
        // Prepare the statement
        $stmt = $this->pdo->prepare($sql);
        // Execute with parameters
        $stmt->execute($params);
        // Return the statement object to be iterated over (e.g., with foreach)
        return $stmt;
    }

    /**
     * Helper to get the ID of the last inserted row.
     *
     * @return string|false
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}

// Example instantiation
// $db = new DB();
