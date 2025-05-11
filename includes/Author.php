<?php
/**
 * Author Class
 * 
 * Handles all author-related database operations
 */
class Author {
    private $db;
    
    /**
     * Constructor - initializes the database connection
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all authors
     * 
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @return array List of authors
     */
    public function getAllAuthors($orderBy = 'id', $order = 'ASC') {
        try {
            $sql = "SELECT * FROM `authors` ORDER BY `{$orderBy}` {$order}";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            Helper::logError('Error fetching authors: ' . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
    
    /**
     * Get author by ID
     * 
     * @param int $id Author ID
     * @return array|null Author data or null if not found
     */
    public function getAuthorById($id) {
        try {
            $sql = "SELECT * FROM `authors` WHERE `id` = ?";
            return $this->db->fetchOne($sql, 'i', [$id]);
        } catch (Exception $e) {
            Helper::logError('Error fetching author: ' . $e->getMessage(), __FILE__, __LINE__);
            return null;
        }
    }
    
    /**
     * Add a new author
     * 
     * @param string $name Author name
     * @param string $biography Author biography
     * @param string $bookType Book type (optional)
     * @param string $imagePath Path to author image (optional)
     * @return int|false New author ID or false on failure
     */
    public function addAuthor($name, $biography, $bookType = '', $imagePath = '') {
        try {
            $sql = "INSERT INTO `authors` (`name`, `biography`, `book_type`, `image_path`) 
                    VALUES (?, ?, ?, ?)";
            return $this->db->insert($sql, 'ssss', [$name, $biography, $bookType, $imagePath]);
        } catch (Exception $e) {
            Helper::logError('Error adding author: ' . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Update an existing author
     * 
     * @param int $id Author ID
     * @param string $name Author name
     * @param string $biography Author biography
     * @param string $bookType Book type (optional)
     * @param string $imagePath Path to author image (optional)
     * @return int|false Number of affected rows or false on failure
     */
    public function updateAuthor($id, $name, $biography, $bookType = '', $imagePath = '') {
        try {
            // If image path is empty, don't update it
            if (empty($imagePath)) {
                $sql = "UPDATE `authors` SET `name` = ?, `biography` = ?, `book_type` = ? 
                        WHERE `id` = ?";
                return $this->db->update($sql, 'sssi', [$name, $biography, $bookType, $id]);
            } else {
                $sql = "UPDATE `authors` SET `name` = ?, `biography` = ?, `book_type` = ?, 
                        `image_path` = ? WHERE `id` = ?";
                return $this->db->update($sql, 'ssssi', [$name, $biography, $bookType, $imagePath, $id]);
            }
        } catch (Exception $e) {
            Helper::logError('Error updating author: ' . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Delete an author
     * 
     * @param int $id Author ID
     * @return int|false Number of affected rows or false on failure
     */
    public function deleteAuthor($id) {
        try {
            $sql = "DELETE FROM `authors` WHERE `id` = ?";
            return $this->db->delete($sql, 'i', [$id]);
        } catch (Exception $e) {
            Helper::logError('Error deleting author: ' . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Search authors by name or biography
     * 
     * @param string $searchTerm Search term
     * @return array List of matching authors
     */
    public function searchAuthors($searchTerm) {
        try {
            $searchTerm = "%{$searchTerm}%";
            $sql = "SELECT * FROM `authors` WHERE `name` LIKE ? OR `biography` LIKE ?";
            return $this->db->fetchAll($sql, 'ss', [$searchTerm, $searchTerm]);
        } catch (Exception $e) {
            Helper::logError('Error searching authors: ' . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
}