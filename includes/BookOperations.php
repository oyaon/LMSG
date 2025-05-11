<?php
/**
 * Book Operations Class
 * 
 * Provides reusable functions for book-related database operations
 */
class BookOperations {
    private $dbOps;
    
    /**
     * Constructor - initializes the database operations
     */
    public function __construct() {
        global $dbOps;
        $this->dbOps = $dbOps;
    }
    
    /**
     * Get all books with optional filtering and pagination
     * 
     * @param array $filters Associative array of filters (e.g., ['category' => 'Fiction'])
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @param int $limit Maximum number of records to return (0 for all)
     * @param int $offset Offset for pagination
     * @return array List of books
     */
    public function getAllBooks($filters = [], $orderBy = 'id', $order = 'DESC', $limit = 0, $offset = 0) {
        try {
            // Start building the query
            $sql = "SELECT b.*, a.name as author_name 
                    FROM all_books b 
                    LEFT JOIN authors a ON b.author_id = a.id";
            
            // Add filters if any
            if (!empty($filters)) {
                $whereClause = [];
                $params = [];
                $types = '';
                
                foreach ($filters as $field => $value) {
                    if ($field === 'search') {
                        // Special case for search
                        $whereClause[] = "(b.name LIKE ? OR b.author LIKE ? OR a.name LIKE ?)";
                        $searchTerm = "%{$value}%";
                        $params[] = $searchTerm;
                        $params[] = $searchTerm;
                        $params[] = $searchTerm;
                        $types .= 'sss';
                    } else {
                        $whereClause[] = "b.{$field} = ?";
                        $params[] = $value;
                        $types .= is_numeric($value) ? 'i' : 's';
                    }
                }
                
                if (!empty($whereClause)) {
                    $sql .= " WHERE " . implode(' AND ', $whereClause);
                }
                
                // Add order by
                $sql .= " ORDER BY b.{$orderBy} {$order}";
                
                // Add limit if specified
                if ($limit > 0) {
                    $sql .= " LIMIT ?, ?";
                    $params[] = $offset;
                    $params[] = $limit;
                    $types .= 'ii';
                }
                
                return $this->dbOps->executeQuery($sql, $types, $params);
            } else {
                // No filters, use simple query
                return $this->dbOps->executeQuery(
                    $sql . " ORDER BY b.{$orderBy} {$order}" . 
                    ($limit > 0 ? " LIMIT ?, ?" : ""),
                    ($limit > 0 ? 'ii' : ''),
                    ($limit > 0 ? [$offset, $limit] : [])
                );
            }
        } catch (Exception $e) {
            Helper::logError("Error fetching books: " . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
    
    /**
     * Get book by ID with author information
     * 
     * @param int $id Book ID
     * @return array|null Book data or null if not found
     */
    public function getBookById($id) {
        try {
            $sql = "SELECT b.*, a.name as author_name, a.biography as author_biography 
                    FROM all_books b 
                    LEFT JOIN authors a ON b.author_id = a.id 
                    WHERE b.id = ?";
            
            return $this->dbOps->executeQuery($sql, 'i', [$id], false);
        } catch (Exception $e) {
            Helper::logError("Error fetching book: " . $e->getMessage(), __FILE__, __LINE__);
            return null;
        }
    }
    
    /**
     * Get books by category
     * 
     * @param string $category Category name
     * @param int $limit Maximum number of books to return (0 for all)
     * @param int $offset Offset for pagination
     * @return array List of books
     */
    public function getBooksByCategory($category, $limit = 0, $offset = 0) {
        return $this->getAllBooks(['category' => $category], 'id', 'DESC', $limit, $offset);
    }
    
    /**
     * Get books by author
     * 
     * @param int $authorId Author ID
     * @param int $limit Maximum number of books to return (0 for all)
     * @param int $offset Offset for pagination
     * @return array List of books
     */
    public function getBooksByAuthor($authorId, $limit = 0, $offset = 0) {
        return $this->getAllBooks(['author_id' => $authorId], 'id', 'DESC', $limit, $offset);
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
        try {
            // Validate parameters
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
            $sql = "SELECT b.*, a.name as author_name 
                    FROM all_books b 
                    LEFT JOIN authors a ON b.author_id = a.id";
            
            $countSql = "SELECT COUNT(*) as total FROM all_books b 
                         LEFT JOIN authors a ON b.author_id = a.id";
            
            $whereClause = [];
            $params = [];
            $types = '';
            
            // Add search query
            if (!empty($query)) {
                $whereClause[] = "(b.name LIKE ? OR b.description LIKE ? OR b.author LIKE ? OR a.name LIKE ?)";
                $searchTerm = "%{$query}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $types .= 'ssss';
            }
            
            // Add category filter
            if (!empty($category)) {
                $whereClause[] = "b.category = ?";
                $params[] = $category;
                $types .= 's';
            }
            
            // Add author filter
            if (!empty($authorId)) {
                if (is_numeric($authorId)) {
                    $whereClause[] = "b.author_id = ?";
                    $params[] = $authorId;
                    $types .= 'i';
                } else {
                    $whereClause[] = "(b.author LIKE ? OR a.name LIKE ?)";
                    $authorTerm = "%{$authorId}%";
                    $params[] = $authorTerm;
                    $params[] = $authorTerm;
                    $types .= 'ss';
                }
            }
            
            // Add where clause to queries
            if (!empty($whereClause)) {
                $whereString = " WHERE " . implode(' AND ', $whereClause);
                $sql .= $whereString;
                $countSql .= $whereString;
            }
            
            // Add sorting
            $sql .= " ORDER BY b.{$sortBy} {$sortOrder}";
            
            // Add pagination
            $sql .= " LIMIT ?, ?";
            
            // Prepare parameters and types for main query
            $mainParams = $params;
            $mainTypes = $types;
            $mainParams[] = $offset;
            $mainParams[] = $perPage;
            $mainTypes .= 'ii';
            
            // Execute count query without pagination parameters
            $countResult = $this->dbOps->executeQuery($countSql, $types, $params, false);
            $total = $countResult ? (int)$countResult['total'] : 0;
            
            // Execute search query with pagination parameters
            $books = $this->dbOps->executeQuery($sql, $mainTypes, $mainParams);
            
            return [
                'books' => $books,
                'total' => $total
            ];
        } catch (Exception $e) {
            Helper::logError("Error searching books: " . $e->getMessage(), __FILE__, __LINE__);
            return [
                'books' => [],
                'total' => 0
            ];
        }
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
        try {
            $sql = "SELECT b.*, a.name as author_name 
                    FROM all_books b 
                    LEFT JOIN authors a ON b.author_id = a.id
                    WHERE b.id != ? AND (b.category = ? OR b.author_id = ?)
                    ORDER BY RAND()
                    LIMIT ?";
            
            return $this->dbOps->executeQuery($sql, 'isii', [$bookId, $category, $authorId, $limit]);
        } catch (Exception $e) {
            Helper::logError("Error fetching related books: " . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
    
    /**
     * Get categories for filtering
     * 
     * @return array List of categories
     */
    public function getCategories() {
        try {
            $result = $this->dbOps->executeQuery("SELECT DISTINCT category FROM all_books ORDER BY category");
            return array_column($result, 'category');
        } catch (Exception $e) {
            Helper::logError("Error fetching categories: " . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
    
    /**
     * Get latest books
     * 
     * @param int $limit Number of books to return
     * @return array List of books
     */
    public function getLatestBooks($limit = 10) {
        return $this->getAllBooks([], 'id', 'DESC', $limit);
    }
    
    /**
     * Get all categories
     * 
     * @return array List of categories
     */
    public function getAllCategories() {
        try {
            $result = $this->dbOps->executeQuery("SELECT DISTINCT category FROM all_books ORDER BY category");
            return array_column($result, 'category');
        } catch (Exception $e) {
            Helper::logError("Error fetching categories: " . $e->getMessage(), __FILE__, __LINE__);
            return [];
        }
    }
    
    /**
     * Add a new book
     * 
     * @param array $bookData Book data
     * @return int|false New book ID or false on failure
     */
    public function addBook($bookData) {
        try {
            // Begin transaction
            $this->dbOps->beginTransaction();
            
            // Get or create author
            $authorId = $this->getOrCreateAuthor($bookData['author']);
            
            if (!$authorId) {
                throw new Exception("Failed to get or create author");
            }
            
            // Handle file uploads
            $pdfFileName = $this->handleFileUpload('pdf', PDF_FILES_DIR);
            $coverImage = $this->handleFileUpload('cover_image', BOOK_COVERS_DIR);
            
            // Prepare book data
            $insertData = [
                'name' => $bookData['name'],
                'author_id' => $authorId,
                'author' => $bookData['author'],
                'category' => $bookData['category'],
                'description' => $bookData['description'],
                'quantity' => $bookData['quantity'],
                'price' => $bookData['price'],
                'pdf' => $pdfFileName,
                'cover_image' => $coverImage
            ];
            
            // Insert book
            $bookId = $this->dbOps->insert('all_books', $insertData);
            
            if (!$bookId) {
                throw new Exception("Failed to insert book");
            }
            
            // Commit transaction
            $this->dbOps->commit();
            
            return $bookId;
        } catch (Exception $e) {
            // Rollback transaction
            $this->dbOps->rollback();
            
            Helper::logError("Error adding book: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Update a book
     * 
     * @param int $id Book ID
     * @param array $bookData Book data
     * @return bool Success status
     */
    public function updateBook($id, $bookData) {
        try {
            // Begin transaction
            $this->dbOps->beginTransaction();
            
            // Get current book data
            $currentBook = $this->getBookById($id);
            
            if (!$currentBook) {
                throw new Exception("Book not found with ID: $id");
            }
            
            // Get or create author
            $authorId = $this->getOrCreateAuthor($bookData['author']);
            
            if (!$authorId) {
                throw new Exception("Failed to get or create author");
            }
            
            // Handle file uploads
            $pdfFileName = $this->handleFileUpload('pdf', PDF_FILES_DIR, $currentBook['pdf']);
            $coverImage = $this->handleFileUpload('cover_image', BOOK_COVERS_DIR, $currentBook['cover_image']);
            
            // Prepare book data
            $updateData = [
                'name' => $bookData['name'],
                'author_id' => $authorId,
                'author' => $bookData['author'],
                'category' => $bookData['category'],
                'description' => $bookData['description'],
                'quantity' => $bookData['quantity'],
                'price' => $bookData['price'],
                'pdf' => $pdfFileName,
                'cover_image' => $coverImage
            ];
            
            // Update book
            $result = $this->dbOps->update('all_books', $updateData, $id);
            
            if ($result === false) {
                throw new Exception("Failed to update book");
            }
            
            // Commit transaction
            $this->dbOps->commit();
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $this->dbOps->rollback();
            
            Helper::logError("Error updating book: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Delete a book
     * 
     * @param int $id Book ID
     * @return bool Success status
     */
    public function deleteBook($id) {
        try {
            // Begin transaction
            $this->dbOps->beginTransaction();
            
            // Get book data
            $book = $this->getBookById($id);
            
            if (!$book) {
                throw new Exception("Book not found with ID: $id");
            }
            
            // Delete files
            $this->deleteFile(PDF_FILES_DIR, $book['pdf']);
            $this->deleteFile(BOOK_COVERS_DIR, $book['cover_image']);
            
            // Delete book
            $result = $this->dbOps->delete('all_books', $id);
            
            if ($result === false) {
                throw new Exception("Failed to delete book");
            }
            
            // Commit transaction
            $this->dbOps->commit();
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $this->dbOps->rollback();
            
            Helper::logError("Error deleting book: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Get or create an author
     * 
     * @param string $authorName Author name
     * @return int|false Author ID or false on failure
     */
    private function getOrCreateAuthor($authorName) {
        try {
            // Check if author exists
            $sql = "SELECT id FROM authors WHERE name = ?";
            $author = $this->dbOps->executeQuery($sql, 's', [$authorName], false);
            
            if ($author) {
                return $author['id'];
            }
            
            // Create new author
            $authorId = $this->dbOps->insert('authors', ['name' => $authorName]);
            
            return $authorId;
        } catch (Exception $e) {
            Helper::logError("Error getting or creating author: " . $e->getMessage(), __FILE__, __LINE__);
            return false;
        }
    }
    
    /**
     * Handle file upload
     * 
     * @param string $fileKey File key in $_FILES
     * @param string $targetDir Target directory
     * @param string $currentFileName Current file name (for updates)
     * @return string File name
     */
    private function handleFileUpload($fileKey, $targetDir, $currentFileName = '') {
        // If no new file uploaded, return current file name
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== 0) {
            return $currentFileName;
        }
        
        // Delete old file if exists
        $this->deleteFile($targetDir, $currentFileName);
        
        // Generate unique filename
        $filename = uniqid() . '_' . basename($_FILES[$fileKey]['name']);
        $targetPath = $targetDir . '/' . $filename;
        
        // Check file size
        if ($_FILES[$fileKey]['size'] > MAX_FILE_SIZE) {
            throw new Exception("File size exceeds maximum allowed size");
        }
        
        // Move uploaded file
        if (!move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetPath)) {
            throw new Exception("Failed to move uploaded file");
        }
        
        return $filename;
    }
    
    /**
     * Delete a file
     * 
     * @param string $dir Directory
     * @param string $fileName File name
     */
    private function deleteFile($dir, $fileName) {
        if (empty($fileName)) {
            return;
        }
        
        $filePath = $dir . '/' . $fileName;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}