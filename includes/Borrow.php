<?php
/**
 * Borrow Class
 * 
 * Handles book borrowing operations
 */
class Borrow {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $this->db = Database::getInstance();
    }
    
    /**
     * Request to borrow a book
     * 
     * @param string $userEmail User email
     * @param int $bookId Book ID
     * @return bool|string Success status or error message
     */
    public function requestBorrow($userEmail, $bookId) {
        // Check if user already has this book
        $existingBorrow = $this->db->fetchOne(
            "SELECT * FROM borrow_history 
             WHERE book_id = ? AND user_email = ? AND status != 'Returned'",
            "is",
            [$bookId, $userEmail]
        );
        
        if ($existingBorrow) {
            return "You have already requested or borrowed this book.";
        }
        
        // Check if user has reached borrow limit (3 books)
        $activeLoans = $this->db->fetchAll(
            "SELECT * FROM borrow_history 
             WHERE user_email = ? AND status != 'Returned'",
            "s",
            [$userEmail]
        );
        
        if (count($activeLoans) >= 3) {
            return "You cannot borrow more than 3 books at a time.";
        }
        
        // Check book availability
        $book = $this->db->fetchOne(
            "SELECT * FROM all_books WHERE id = ?",
            "i",
            [$bookId]
        );
        
        if (!$book || $book['quantity'] <= 0) {
            return "This book is not available for borrowing.";
        }
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Insert borrow request
            $result = $this->db->insert(
                "INSERT INTO borrow_history (user_email, book_id, status) 
                 VALUES (?, ?, 'Requested')",
                "si",
                [$userEmail, $bookId]
            );
            
            if (!$result) {
                $this->db->rollback();
                return "Failed to create borrow request.";
            }
            
            // Update book quantity
            $newQuantity = $book['quantity'] - 1;
            $updateResult = $this->db->update(
                "UPDATE all_books SET quantity = ? WHERE id = ?",
                "ii",
                [$newQuantity, $bookId]
            );
            
            if (!$updateResult) {
                $this->db->rollback();
                return "Failed to update book quantity.";
            }
            
            // Commit transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return "An error occurred: " . $e->getMessage();
        }
    }
    
    /**
     * Issue a book (admin function)
     * 
     * @param int $borrowId Borrow history ID
     * @return bool Success status
     */
    public function issueBook($borrowId) {
        $today = date('Y-m-d');
        
        return $this->db->update(
            "UPDATE borrow_history SET status = 'Issued', issue_date = ? WHERE id = ?",
            "si",
            [$today, $borrowId]
        );
    }
    
    /**
     * Return a book
     * 
     * @param int $borrowId Borrow history ID
     * @param int $bookId Book ID
     * @param float $fine Fine amount
     * @return bool Success status
     */
    public function returnBook($borrowId, $bookId, $fine = 0) {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Update borrow history
            $updateResult = $this->db->update(
                "UPDATE borrow_history SET status = 'Returned', fine = ?, return_date = CURRENT_DATE() WHERE id = ?",
                "di",
                [$fine, $borrowId]
            );
            
            if (!$updateResult) {
                $this->db->rollback();
                return false;
            }
            
            // Update book quantity
            $updateBookResult = $this->db->update(
                "UPDATE all_books SET quantity = quantity + 1 WHERE id = ?",
                "i",
                [$bookId]
            );
            
            if (!$updateBookResult) {
                $this->db->rollback();
                return false;
            }
            
            // Commit transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Decline a borrow request
     * 
     * @param int $borrowId Borrow history ID
     * @param int $bookId Book ID
     * @return bool Success status
     */
    public function declineBorrowRequest($borrowId, $bookId) {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Update borrow history
            $updateResult = $this->db->update(
                "UPDATE borrow_history SET status = 'Declined' WHERE id = ?",
                "i",
                [$borrowId]
            );
            
            if (!$updateResult) {
                $this->db->rollback();
                return false;
            }
            
            // Update book quantity
            $updateBookResult = $this->db->update(
                "UPDATE all_books SET quantity = quantity + 1 WHERE id = ?",
                "i",
                [$bookId]
            );
            
            if (!$updateBookResult) {
                $this->db->rollback();
                return false;
            }
            
            // Commit transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Get user's borrow history
     * 
     * @param string $userEmail User email
     * @return array
     */
    public function getUserBorrowHistory($userEmail) {
        return $this->db->fetchAll(
            "SELECT bh.*, b.name as book_name, b.author 
             FROM borrow_history bh 
             JOIN all_books b ON bh.book_id = b.id 
             WHERE bh.user_email = ? 
             ORDER BY bh.id DESC",
            "s",
            [$userEmail]
        );
    }
    
    /**
     * Get all borrow requests (admin function)
     * 
     * @param string $status Optional status filter
     * @return array
     */
    public function getAllBorrowRequests($status = null) {
        if ($status) {
            return $this->db->fetchAll(
                "SELECT bh.*, b.name as book_name, b.author, u.first_name, u.last_name 
                 FROM borrow_history bh 
                 JOIN all_books b ON bh.book_id = b.id 
                 JOIN users u ON bh.user_email = u.email 
                 WHERE bh.status = ? 
                 ORDER BY bh.id DESC",
                "s",
                [$status]
            );
        }
        
        return $this->db->fetchAll(
            "SELECT bh.*, b.name as book_name, b.author, u.first_name, u.last_name 
             FROM borrow_history bh 
             JOIN all_books b ON bh.book_id = b.id 
             JOIN users u ON bh.user_email = u.email 
             ORDER BY bh.id DESC"
        );
    }
    
    /**
     * Get borrow history for a specific book
     * 
     * @param int $bookId Book ID
     * @return array
     */
    public function getBookBorrowHistory($bookId) {
        return $this->db->fetchAll(
            "SELECT bh.*, u.first_name, u.last_name 
             FROM borrow_history bh 
             JOIN users u ON bh.user_email = u.email 
             WHERE bh.book_id = ? 
             ORDER BY bh.id DESC",
            "i",
            [$bookId]
        );
    }
    
    /**
     * Calculate fine for overdue books
     * 
     * @param string $issueDate Issue date
     * @param int $gracePeriod Grace period in days
     * @param float $finePerDay Fine amount per day
     * @return float Fine amount
     */
    public function calculateFine($issueDate, $gracePeriod = 14, $finePerDay = 5.0) {
        if (!$issueDate) {
            return 0;
        }
        
        $issueDateTime = new DateTime($issueDate);
        $today = new DateTime();
        $daysElapsed = $today->diff($issueDateTime)->days;
        
        if ($daysElapsed <= $gracePeriod) {
            return 0;
        }
        
        $overdueDays = $daysElapsed - $gracePeriod;
        return $overdueDays * $finePerDay;
    }
    
    /**
     * Check if there are active borrow records for a user
     *
     * @param string $userEmail User email
     * @return bool True if active, false otherwise
     */
    public function isActive($userEmail = null) {
        if ($userEmail === null) {
            return false; // Default to inactive if no email is provided
        }

        $activeLoans = $this->db->fetchAll(
            "SELECT * FROM borrow_history WHERE user_email = ? AND status != 'Returned'",
            "s",
            [$userEmail]
        );
        return count($activeLoans) > 0;
    }

    /**
     * Load borrow details by ID
     *
     * @param int $borrowId Borrow ID
     * @return array|false Borrow details or false if not found
     */
    public function loadBorrowById($borrowId) {
        $borrow = $this->db->fetchOne(
            "SELECT * FROM borrow_history WHERE id = ?",
            "i",
            [$borrowId]
        );
        return $borrow ?: false; // Explicitly return false if no record is found
    }
}