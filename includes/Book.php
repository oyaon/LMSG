<?php
/**
 * Book Class
 * 
 * Handles book-related operations
 */
class Book {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all books
     * 
     * @param int $limit Optional limit
     * @param int $offset Optional offset
     * @return array
     */
    public function getAllBooks($limit = null, $offset = 0) {
        $sql = "SELECT b.*, a.name as author_name FROM all_books b 
                LEFT JOIN authors a ON b.author_id = a.id 
                ORDER BY b.id DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ?, ?";
            return $this->db->fetchAll($sql, "ii", [$offset, $limit]);
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get book by ID
     * 
     * @param int $id Book ID
     * @return array|null
     */
    public function getBookById($id) {
        return $this->db->fetchOne(
            "SELECT b.*, a.name as author_name, a.biography as author_biography 
             FROM all_books b 
             LEFT JOIN authors a ON b.author_id = a.id 
             WHERE b.id = ?",
            "i",
            [$id]
        );
    }
    
    /**
     * Search books
     * 
     * @param string $query Search query
     * @param string $category Optional category filter
     * @return array
     */
    public function searchBooks($query, $category = null) {
        $searchTerm = "%$query%";
        
        if ($category) {
            return $this->db->fetchAll(
                "SELECT b.*, a.name as author_name FROM all_books b 
                 LEFT JOIN authors a ON b.author_id = a.id 
                 WHERE (b.name LIKE ? OR b.author LIKE ? OR a.name LIKE ?) 
                 AND b.category = ? 
                 ORDER BY b.id DESC",
                "ssss",
                [$searchTerm, $searchTerm, $searchTerm, $category]
            );
        }
        
        return $this->db->fetchAll(
            "SELECT b.*, a.name as author_name FROM all_books b 
             LEFT JOIN authors a ON b.author_id = a.id 
             WHERE b.name LIKE ? OR b.author LIKE ? OR a.name LIKE ? 
             ORDER BY b.id DESC",
            "sss",
            [$searchTerm, $searchTerm, $searchTerm]
        );
    }
    
    /**
     * Get books by category
     * 
     * @param string $category Category name
     * @param int $limit Optional limit
     * @return array
     */
    public function getBooksByCategory($category, $limit = null) {
        $sql = "SELECT b.*, a.name as author_name FROM all_books b 
                LEFT JOIN authors a ON b.author_id = a.id 
                WHERE b.category = ? 
                ORDER BY b.id DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ?";
            return $this->db->fetchAll($sql, "si", [$category, $limit]);
        }
        
        return $this->db->fetchAll($sql, "s", [$category]);
    }
    
    /**
     * Get books by author
     * 
     * @param int $authorId Author ID
     * @return array
     */
    public function getBooksByAuthor($authorId) {
        return $this->db->fetchAll(
            "SELECT * FROM all_books WHERE author_id = ? ORDER BY id DESC",
            "i",
            [$authorId]
        );
    }
    
    /**
     * Get latest books
     * 
     * @param int $limit Number of books to return
     * @return array
     */
    public function getLatestBooks($limit = 10) {
        return $this->db->fetchAll(
            "SELECT b.*, a.name as author_name FROM all_books b 
             LEFT JOIN authors a ON b.author_id = a.id 
             ORDER BY b.id DESC LIMIT ?",
            "i",
            [$limit]
        );
    }
    
    /**
     * Add a new book
     * 
     * @param array $bookData Book data
     * @return int|false Book ID or false on failure
     */
    public function addBook($bookData) {
        // Get author ID or create new author
        $authorId = null;
        if (!empty($bookData['author_id'])) {
            $authorId = $bookData['author_id'];
        } else if (!empty($bookData['author'])) {
            // Check if author exists
            $author = $this->db->fetchOne(
                "SELECT id FROM authors WHERE name = ?",
                "s",
                [$bookData['author']]
            );
            
            if ($author) {
                $authorId = $author['id'];
            } else {
                // Create new author
                $authorId = $this->db->insert(
                    "INSERT INTO authors (name) VALUES (?)",
                    "s",
                    [$bookData['author']]
                );
            }
        }
        
        // Handle file upload for PDF
        $pdfFileName = '';
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
            $pdfFileName = $this->uploadFile($_FILES['pdf'], PDF_FILES_DIR);
        }
        
        // Handle file upload for cover image
        $coverImage = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $coverImage = $this->uploadFile($_FILES['cover_image'], BOOK_COVERS_DIR);
        }
        
        // Insert book
        return $this->db->insert(
            "INSERT INTO all_books (name, author_id, author, category, description, quantity, price, pdf, cover_image) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            "iissidss",
            [
                $bookData['name'],
                $authorId,
                $bookData['author'],
                $bookData['category'],
                $bookData['description'],
                $bookData['quantity'],
                $bookData['price'],
                $pdfFileName,
                $coverImage
            ]
        );
    }
    
    /**
     * Update a book
     * 
     * @param int $id Book ID
     * @param array $bookData Book data
     * @return bool Success status
     */
    public function updateBook($id, $bookData) {
        // Get author ID or create new author
        $authorId = null;
        if (!empty($bookData['author_id'])) {
            $authorId = $bookData['author_id'];
        } else if (!empty($bookData['author'])) {
            // Check if author exists
            $author = $this->db->fetchOne(
                "SELECT id FROM authors WHERE name = ?",
                "s",
                [$bookData['author']]
            );
            
            if ($author) {
                $authorId = $author['id'];
            } else {
                // Create new author
                $authorId = $this->db->insert(
                    "INSERT INTO authors (name) VALUES (?)",
                    "s",
                    [$bookData['author']]
                );
            }
        }
        
        // Get current book data
        $currentBook = $this->getBookById($id);
        
        // Handle file upload for PDF
        $pdfFileName = $currentBook['pdf'];
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
            // Delete old file if exists
            if (!empty($currentBook['pdf'])) {
                $oldFilePath = PDF_FILES_DIR . '/' . $currentBook['pdf'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            $pdfFileName = $this->uploadFile($_FILES['pdf'], PDF_FILES_DIR);
        }
        
        // Handle file upload for cover image
        $coverImage = $currentBook['cover_image'];
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            // Delete old file if exists
            if (!empty($currentBook['cover_image'])) {
                $oldFilePath = BOOK_COVERS_DIR . '/' . $currentBook['cover_image'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            $coverImage = $this->uploadFile($_FILES['cover_image'], BOOK_COVERS_DIR);
        }
        
        // Update book
        return $this->db->update(
            "UPDATE all_books SET 
             name = ?, 
             author_id = ?, 
             author = ?, 
             category = ?, 
             description = ?, 
             quantity = ?, 
             price = ?, 
             pdf = ?, 
             cover_image = ? 
             WHERE id = ?",
            "sissidiisi",
            [
                $bookData['name'],
                $authorId,
                $bookData['author'],
                $bookData['category'],
                $bookData['description'],
                $bookData['quantity'],
                $bookData['price'],
                $pdfFileName,
                $coverImage,
                $id
            ]
        );
    }
    
    /**
     * Delete a book
     * 
     * @param int $id Book ID
     * @return bool Success status
     */
    public function deleteBook($id) {
        // Get book data to delete files
        $book = $this->getBookById($id);
        
        if ($book) {
            // Delete PDF file if exists
            if (!empty($book['pdf'])) {
                $pdfPath = PDF_FILES_DIR . '/' . $book['pdf'];
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }
            
            // Delete cover image if exists
            if (!empty($book['cover_image'])) {
                $coverPath = BOOK_COVERS_DIR . '/' . $book['cover_image'];
                if (file_exists($coverPath)) {
                    unlink($coverPath);
                }
            }
            
            // Delete book from database
            return $this->db->delete(
                "DELETE FROM all_books WHERE id = ?",
                "i",
                [$id]
            );
        }
        
        return false;
    }
    
    /**
     * Get all categories
     * 
     * @return array
     */
    public function getAllCategories() {
        $categories = $this->db->fetchAll("SELECT DISTINCT category FROM all_books ORDER BY category");
        return array_column($categories, 'category');
    }
    
    /**
     * Upload a file
     * 
     * @param array $file File data from $_FILES
     * @param string $targetDir Target directory
     * @return string|false Filename or false on failure
     */
    private function uploadFile($file, $targetDir) {
        // Generate unique filename
        $filename = uniqid() . '_' . basename($file['name']);
        $targetPath = $targetDir . '/' . $filename;
        
        // Check file size
        if ($file['size'] > MAX_FILE_SIZE) {
            return false;
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }
        
        return false;
    }
}