<?php

class DBClass extends mysqli {
    public function __construct($host, $user = null, $pass = null, $db = null, $port = null, $socket = null) {
        // enable error reporting
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        // instantiate mysqli
        parent::__construct($host, $user, $pass, $db, $port, $socket);
        // set the correct charset
        $this->set_charset('utf8mb4');
    }

    /**
     * Executes prepared statement
     *
     * @param string $sql SQL query with placeholders e.g. SELECT * FROM users WHERE Id=?
     * @param array $params An array of parameters to be bound
     * @return array|null
     */
    public function safeQuery(string $sql, array $params = []): ?array {
        // prepare/bind/execute
        $stmt = $this->prepare($sql);
        if ($params) {
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }
        $stmt->execute();
        // If the query produces results then fetch them into multidimensional array
        if ($result = $stmt->get_result()) {
            return $result->fetch_all(MYSQLI_BOTH);
        }
        // return nothing if the query was successfully executed and it didn't produce results
        return null;
    }

    public function row(string $sql, array $params = []): ?array {
        return $this->safeQuery($sql, $params)[0] ?? [];
    }

    public function single(string $sql, array $params = []) {
        $data = $this->row($sql, $params);
        return array_shift($data);
    }
}

/*

Examples - https://stackoverflow.com/questions/64868834/is-there-a-shorthand-for-prepared-statements

$db = new DBClass('localhost', 'user', 'pass', 'dbname');

// select multiple rows
$id = 2;
foreach ($db->safeQuery('SELECT title FROM movie WHERE directorId=?', [$id]) as $row) {
    echo $row['title']."\n";
}

// select a single row
$movie = $db->row('SELECT id FROM movie WHERE title=?', ['Titanic']);
if ($movie) {
    echo $movie['id']."\n";
}

// select a single column from a single row
echo $db->single('SELECT title FROM movie WHERE id=1');

*/
?>