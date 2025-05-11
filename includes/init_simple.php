<?php
/**
 * Simple Initialization file
 * 
 * This file includes the necessary files for basic functionality
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once __DIR__ . '/../admin/db-connect.php';

// Include components
require_once __DIR__ . '/components.php';

// Simple BookOperations class for basic functionality
class SimpleBookOperations {
    private $conn;
    
    /**
     * Constructor - initializes the database connection
     */
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    /**
     * Get book by ID
     * 
     * @param int $id Book ID
     * @return array|null Book data or null if not found
     */
    public function getBookById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM all_books WHERE id = $id";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Get related books based on category and author
     * 
     * @param int $bookId Current book ID to exclude
     * @param string $category Book category
     * @param int $authorId Author ID
     * @param int $limit Maximum number of books to return
     * @return array List of related books
     */
    public function getRelatedBooks($bookId, $category, $authorId = 0, $limit = 4) {
        $bookId = (int)$bookId;
        $authorId = (int)$authorId;
        $limit = (int)$limit;
        $category = $this->conn->real_escape_string($category);
        
        $sql = "SELECT * FROM all_books 
                WHERE id != $bookId 
                AND (category = '$category' OR author_id = $authorId)
                AND quantity > 0
                ORDER BY RAND()
                LIMIT $limit";
        
        $result = $this->conn->query($sql);
        $books = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
        
        return $books;
    }
    
    /**
     * Get categories for filtering
     * 
     * @return array List of categories
     */
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM all_books ORDER BY category";
        $result = $this->conn->query($sql);
        $categories = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row['category'];
            }
        }
        
        return $categories;
    }
    
    /**
     * Search books with advanced filtering and sorting
     * 
     * @param string $query Search query
     * @param string $category Category filter
     * @param string|int $authorId Author ID or name filter
     * @param string $sortBy Field to sort by
     * @param string $sortOrder Sort order (asc or desc)
     * @param int $page Page number
     * @param int $perPage Items per page
     * @return array Array with 'books' and 'total' keys
     */
    public function searchBooks($query = '', $category = '', $authorId = '', $sortBy = 'name', $sortOrder = 'asc', $page = 1, $perPage = 12) {
        // Sanitize inputs
        $query = $this->conn->real_escape_string($query);
        $category = $this->conn->real_escape_string($category);
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'DESC' : 'ASC';
        $page = max(1, (int)$page);
        $perPage = max(1, (int)$perPage);
        $offset = ($page - 1) * $perPage;
        
        // Validate sort field
        $allowedSortFields = ['name', 'author', 'category', 'price', 'id'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'name';
        }
        
        // Build query
        $sql = "SELECT * FROM all_books WHERE 1=1";
        $countSql = "SELECT COUNT(*) as total FROM all_books WHERE 1=1";
        
        // Add search query
        if (!empty($query)) {
            $sql .= " AND (name LIKE '%$query%' OR description LIKE '%$query%' OR author LIKE '%$query%')";
            $countSql .= " AND (name LIKE '%$query%' OR description LIKE '%$query%' OR author LIKE '%$query%')";
        }
        
        // Add category filter
        if (!empty($category)) {
            $sql .= " AND category = '$category'";
            $countSql .= " AND category = '$category'";
        }
        
        // Add author filter
        if (!empty($authorId)) {
            if (is_numeric($authorId)) {
                $authorId = (int)$authorId;
                $sql .= " AND author_id = $authorId";
                $countSql .= " AND author_id = $authorId";
            } else {
                $authorId = $this->conn->real_escape_string($authorId);
                $sql .= " AND author LIKE '%$authorId%'";
                $countSql .= " AND author LIKE '%$authorId%'";
            }
        }
        
        // Add sorting
        $sql .= " ORDER BY $sortBy $sortOrder";
        
        // Add pagination
        $sql .= " LIMIT $offset, $perPage";
        
        // Execute count query
        $countResult = $this->conn->query($countSql);
        $total = 0;
        if ($countResult && $countResult->num_rows > 0) {
            $row = $countResult->fetch_assoc();
            $total = (int)$row['total'];
        }
        
        // Execute search query
        $result = $this->conn->query($sql);
        $books = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
        
        return [
            'books' => $books,
            'total' => $total
        ];
    }
}

// Simple Author class for basic functionality
class SimpleAuthor {
    private $conn;
    
    /**
     * Constructor - initializes the database connection
     */
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    /**
     * Get author by ID
     * 
     * @param int $id Author ID
     * @return array|null Author data or null if not found
     */
    public function getAuthorById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM authors WHERE id = $id";
        $result = $this->conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Get all authors
     * 
     * @return array List of authors
     */
    public function getAllAuthors() {
        $sql = "SELECT * FROM authors ORDER BY name";
        $result = $this->conn->query($sql);
        $authors = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $authors[] = $row;
            }
        }
        
        return $authors;
    }
}

// Initialize objects
$bookOps = new SimpleBookOperations();
$author = new SimpleAuthor();