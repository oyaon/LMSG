<?php
/**
 * Database Connection Class
 * 
 * Provides a singleton database connection with prepared statements
 */
class Database {
    private static $instance = null;
    private $conn;
    
    /**
     * Constructor - connects to the database
     */
    private function __construct() {
        require_once __DIR__ . '/../config/config.php';
        
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }
        
        // Set charset to UTF-8
        $this->conn->set_charset("utf8mb4");
    }
    
    /**
     * Get database instance (Singleton pattern)
     * 
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get the database connection
     * 
     * @return mysqli
     */
    public function getConnection() {
        return $this->conn;
    }
    
    /**
     * Execute a query with prepared statement
     * 
     * @param string $sql SQL query with placeholders
     * @param string $types Parameter types (i: integer, d: double, s: string, b: blob)
     * @param array $params Parameters to bind
     * @return mysqli_stmt|false
     */
    public function query($sql, $types = null, $params = []) {
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            error_log("SQL Error: " . $this->conn->error);
            error_log("Query: " . $sql);
            return false;
        }

        if ($types !== null && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }
    
    /**
     * Fetch a single row
     * 
     * @param string $sql SQL query with placeholders
     * @param string $types Parameter types
     * @param array $params Parameters to bind
     * @return array|null
     */
    public function fetchOne($sql, $types = null, $params = []) {
        $stmt = $this->query($sql, $types, $params);
        
        if ($stmt === false) {
            return null;
        }
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row;
    }
    
    /**
     * Fetch multiple rows
     * 
     * @param string $sql SQL query with placeholders
     * @param string $types Parameter types
     * @param array $params Parameters to bind
     * @return array
     */
    public function fetchAll($sql, $types = null, $params = []) {
        $stmt = $this->query($sql, $types, $params);
        
        if ($stmt === false) {
            return [];
        }
        
        $result = $stmt->get_result();
        $rows = [];
        
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        $stmt->close();
        return $rows;
    }
    
    /**
     * Insert data and return the last insert ID
     * 
     * @param string $sql SQL query with placeholders
     * @param string $types Parameter types
     * @param array $params Parameters to bind
     * @return int|false
     */
    public function insert($sql, $types, $params) {
        $stmt = $this->query($sql, $types, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        $id = $this->conn->insert_id;
        $stmt->close();
        
        return $id;
    }
    
    /**
     * Update data and return affected rows
     * 
     * @param string $sql SQL query with placeholders
     * @param string $types Parameter types
     * @param array $params Parameters to bind
     * @return int|false
     */
    public function update($sql, $types, $params) {
        $stmt = $this->query($sql, $types, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        return $affectedRows;
    }
    
    /**
     * Delete data and return affected rows
     * 
     * @param string $sql SQL query with placeholders
     * @param string $types Parameter types
     * @param array $params Parameters to bind
     * @return int|false
     */
    public function delete($sql, $types, $params) {
        return $this->update($sql, $types, $params);
    }
    
    /**
     * Begin a transaction
     */
    public function beginTransaction() {
        $this->conn->begin_transaction();
    }
    
    /**
     * Commit a transaction
     */
    public function commit() {
        $this->conn->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public function rollback() {
        $this->conn->rollback();
    }
    
    /**
     * Close the database connection
     */
    public function close() {
        $this->conn->close();
    }
}