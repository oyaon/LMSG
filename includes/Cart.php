<?php
/**
 * Cart Class
 * 
 * Handles shopping cart and payment operations
 */
class Cart {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $this->db = Database::getInstance();
    }
    
    /**
     * Add a book to cart
     * 
     * @param string $userEmail User email
     * @param int $bookId Book ID
     * @return bool|string Success status or error message
     */
    public function addToCart($userEmail, $bookId) {
        // Check if book is already in cart
        $existingItem = $this->db->fetchOne(
            "SELECT * FROM cart WHERE user_email = ? AND book_id = ? AND status = 0",
            "si",
            [$userEmail, $bookId]
        );
        
        if ($existingItem) {
            return "This book is already in your cart.";
        }
        
        // Check book availability
        $book = $this->db->fetchOne(
            "SELECT * FROM all_books WHERE id = ?",
            "i",
            [$bookId]
        );
        
        if (!$book || $book['quantity'] <= 0) {
            return "This book is not available for purchase.";
        }
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Add to cart
            $today = date('Y-m-d');
            $result = $this->db->insert(
                "INSERT INTO cart (user_email, book_id, date, status) VALUES (?, ?, ?, 0)",
                "sis",
                [$userEmail, $bookId, $today]
            );
            
            if (!$result) {
                $this->db->rollback();
                return "Failed to add book to cart.";
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
     * Remove a book from cart
     * 
     * @param int $cartId Cart item ID
     * @param int $bookId Book ID
     * @return bool Success status
     */
    public function removeFromCart($cartId, $bookId) {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Remove from cart
            $deleteResult = $this->db->delete(
                "DELETE FROM cart WHERE id = ?",
                "i",
                [$cartId]
            );
            
            if (!$deleteResult) {
                $this->db->rollback();
                return false;
            }
            
            // Update book quantity
            $updateResult = $this->db->update(
                "UPDATE all_books SET quantity = quantity + 1 WHERE id = ?",
                "i",
                [$bookId]
            );
            
            if (!$updateResult) {
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
     * Get user's cart items
     * 
     * @param string $userEmail User email
     * @return array
     */
    public function getCartItems($userEmail) {
        return $this->db->fetchAll(
            "SELECT c.*, b.name, b.price, b.quantity as book_quantity 
             FROM cart c 
             JOIN all_books b ON c.book_id = b.id 
             WHERE c.user_email = ? AND c.status = 0 
             ORDER BY c.id DESC",
            "s",
            [$userEmail]
        );
    }
    
    /**
     * Get cart total price
     * 
     * @param string $userEmail User email
     * @return float
     */
    public function getCartTotal($userEmail) {
        $result = $this->db->fetchOne(
            "SELECT SUM(b.price) as total 
             FROM cart c 
             JOIN all_books b ON c.book_id = b.id 
             WHERE c.user_email = ? AND c.status = 0",
            "s",
            [$userEmail]
        );
        
        return $result ? $result['total'] : 0;
    }
    
    /**
     * Process payment
     * 
     * @param string $userEmail User email
     * @param string $bookIds Comma-separated book IDs
     * @param float $amount Total amount
     * @param string $transactionId Transaction ID
     * @return bool Success status
     */
    public function processPayment($userEmail, $bookIds, $amount, $transactionId = '') {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Insert payment record
            $today = date('Y-m-d');
            $paymentResult = $this->db->insert(
                "INSERT INTO payments (user_email, book_ids, amount, payment_date, transaction_id) 
                 VALUES (?, ?, ?, ?, ?)",
                "ssdss",
                [$userEmail, $bookIds, $amount, $today, $transactionId]
            );
            
            if (!$paymentResult) {
                $this->db->rollback();
                return false;
            }
            
            // Update cart items status
            $bookIdsArray = explode(',', $bookIds);
            $placeholders = implode(',', array_fill(0, count($bookIdsArray), '?'));
            
            // Prepare types and params
            $types = str_repeat('i', count($bookIdsArray)) . 's';
            $params = $bookIdsArray;
            $params[] = $userEmail;
            
            $updateResult = $this->db->update(
                "UPDATE cart SET status = 1 
                 WHERE book_id IN ($placeholders) AND user_email = ?",
                $types,
                $params
            );
            
            if (!$updateResult) {
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
     * Get user's order history
     * 
     * @param string $userEmail User email
     * @return array
     */
    public function getOrderHistory($userEmail) {
        return $this->db->fetchAll(
            "SELECT * FROM payments WHERE user_email = ? ORDER BY id DESC",
            "s",
            [$userEmail]
        );
    }
    
    /**
     * Get order details
     * 
     * @param int $paymentId Payment ID
     * @return array
     */
    public function getOrderDetails($paymentId) {
        // Get payment record
        $payment = $this->db->fetchOne(
            "SELECT * FROM payments WHERE id = ?",
            "i",
            [$paymentId]
        );
        
        if (!$payment) {
            return [];
        }
        
        // Get book details
        $bookIds = explode(',', $payment['book_ids']);
        $placeholders = implode(',', array_fill(0, count($bookIds), '?'));
        
        // Prepare types and params
        $types = str_repeat('i', count($bookIds));
        
        return [
            'payment' => $payment,
            'books' => $this->db->fetchAll(
                "SELECT * FROM all_books WHERE id IN ($placeholders)",
                $types,
                $bookIds
            )
        ];
    }
    
    /**
     * Get all orders (admin function)
     * 
     * @return array
     */
    public function getAllOrders() {
        return $this->db->fetchAll(
            "SELECT p.*, u.first_name, u.last_name 
             FROM payments p 
             JOIN users u ON p.user_email = u.email 
             ORDER BY p.id DESC"
        );
    }
}