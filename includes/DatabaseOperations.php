<?php
/**
 * Database Operations Class
 * 
 * Provides reusable functions for common database operations
 */
class DatabaseOperations {
    private $db;
    
    /**
     * Constructor - initializes the database connection
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all records from a table
     * 
     * @param string $table Table name
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @param int $limit Maximum number of records to return (0 for all)
     * @param int $offset Offset for pagination
     * @return array List of records
     */
    public function getAll($table, $orderBy = 'id', $order = 'ASC', $limit = 0, $offset = 0) {
        try {
            $sql = "SELECT * FROM `{$table}` ORDER BY `{$orderBy}` {$order}";
            
            if ($limit > 0) {
                $sql .= " LIMIT ?, ?";
                return $this->db->fetchAll($sql, 'ii', [$offset, $limit]);
            } else {
                return $this->db->fetchAll($sql);
            }
        } catch (Exception $e) {
            Helper::logError("Error fetching records from {$table}: " . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
    
    /**
     * Get record by ID
     * 
     * @param string $table Table name
     * @param int $id Record ID
     * @return array|null Record data or null if not found
     */
    public function getById($table, $id) {
        try {
            $sql = "SELECT * FROM `{$table}` WHERE `id` = ?";
            return $this->db->fetchOne($sql, 'i', [$id]);
        } catch (Exception $e) {
            Helper::logError("Error fetching record from {$table}: " . $e->getMessage(), __FILE__, __LINE__);
            return null;
        }
    }
    
    /**
     * Get records by a specific field value
     * 
     * @param string $table Table name
     * @param string $field Field name
     * @param mixed $value Field value
     * @param string $type Parameter type (i: integer, d: double, s: string, b: blob)
     * @return array List of matching records
     */
    public function getByField($table, $field, $value, $type = 's') {
        try {
            $sql = "SELECT * FROM `{$table}` WHERE `{$field}` = ?";
            return $this->db->fetchAll($sql, $type, [$value]);
        } catch (Exception $e) {
            Helper::logError("Error fetching records from {$table} by {$field}: " . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
    
    /**
     * Insert a record
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value pairs
     * @return int|false New record ID or false on failure
     */
    public function insert($table, $data) {
        try {
            $columns = array_keys($data);
            $values = array_values($data);
            $types = $this->getTypes($values);
            
            $placeholders = implode(', ', array_fill(0, count($columns), '?'));
            $columnList = '`' . implode('`, `', $columns) . '`';
            
            $sql = "INSERT INTO `{$table}` ({$columnList}) VALUES ({$placeholders})";
            
            return $this->db->insert($sql, $types, $values);
        } catch (Exception $e) {
            Helper::logError("Error inserting into {$table}: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Update a record
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value pairs
     * @param int $id Record ID
     * @return int|false Number of affected rows or false on failure
     */
    public function update($table, $data, $id) {
        try {
            $columns = array_keys($data);
            $values = array_values($data);
            
            $setClause = implode(' = ?, ', $columns) . ' = ?';
            $values[] = $id; // Add ID to values array
            
            $types = $this->getTypes($values);
            
            $sql = "UPDATE `{$table}` SET {$setClause} WHERE `id` = ?";
            
            return $this->db->update($sql, $types, $values);
        } catch (Exception $e) {
            Helper::logError("Error updating {$table}: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Delete a record
     * 
     * @param string $table Table name
     * @param int $id Record ID
     * @return int|false Number of affected rows or false on failure
     */
    public function delete($table, $id) {
        try {
            $sql = "DELETE FROM `{$table}` WHERE `id` = ?";
            return $this->db->delete($sql, 'i', [$id]);
        } catch (Exception $e) {
            Helper::logError("Error deleting from {$table}: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Count records in a table
     * 
     * @param string $table Table name
     * @param string $whereClause Optional WHERE clause (without the 'WHERE' keyword)
     * @param string $types Parameter types for WHERE clause
     * @param array $params Parameters for WHERE clause
     * @return int Number of records
     */
    public function count($table, $whereClause = '', $types = '', $params = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM `{$table}`";
            
            if (!empty($whereClause)) {
                $sql .= " WHERE {$whereClause}";
            }
            
            $result = $this->db->fetchOne($sql, $types, $params);
            return $result ? (int)$result['count'] : 0;
        } catch (Exception $e) {
            Helper::logError("Error counting records in {$table}: " . $e->getMessage(), __FILE__, __LINE__);
            return 0;
        }
    }
    
    /**
     * Search records in a table
     * 
     * @param string $table Table name
     * @param array $fields Fields to search in
     * @param string $searchTerm Search term
     * @return array List of matching records
     */
    public function search($table, $fields, $searchTerm) {
        try {
            $searchTerm = "%{$searchTerm}%";
            $whereClause = [];
            $params = [];
            $types = '';
            
            foreach ($fields as $field) {
                $whereClause[] = "`{$field}` LIKE ?";
                $params[] = $searchTerm;
                $types .= 's';
            }
            
            $sql = "SELECT * FROM `{$table}` WHERE " . implode(' OR ', $whereClause);
            
            return $this->db->fetchAll($sql, $types, $params);
        } catch (Exception $e) {
            Helper::logError("Error searching in {$table}: " . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
    
    /**
     * Execute a custom query
     * 
     * @param string $sql SQL query
     * @param string $types Parameter types
     * @param array $params Parameters
     * @param bool $fetchAll Whether to fetch all results or just one
     * @return array|null Query results
     */
    public function executeQuery($sql, $types = '', $params = [], $fetchAll = true) {
        try {
            if ($fetchAll) {
                return $this->db->fetchAll($sql, $types, $params);
            } else {
                return $this->db->fetchOne($sql, $types, $params);
            }
        } catch (Exception $e) {
            Helper::logError("Error executing custom query: " . $e->getMessage(), __FILE__, __LINE__);
            return $fetchAll ? [] : null;
        }
    }
    
    /**
     * Get parameter types for binding
     * 
     * @param array $values Values to determine types for
     * @return string Parameter types string
     */
    private function getTypes($values) {
        $types = '';
        
        foreach ($values as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } elseif (is_string($value)) {
                $types .= 's';
            } else {
                $types .= 's'; // Default to string
            }
        }
        
        return $types;
    }
    
    /**
     * Begin a transaction
     * 
     * @return bool Success status
     */
    public function beginTransaction() {
        try {
            return $this->db->beginTransaction();
        } catch (Exception $e) {
            Helper::logError("Error beginning transaction: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Commit a transaction
     * 
     * @return bool Success status
     */
    public function commit() {
        try {
            return $this->db->commit();
        } catch (Exception $e) {
            Helper::logError("Error committing transaction: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Rollback a transaction
     * 
     * @return bool Success status
     */
    public function rollback() {
        try {
            return $this->db->rollback();
        } catch (Exception $e) {
            Helper::logError("Error rolling back transaction: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
}