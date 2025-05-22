<?php
/**
 * Notification Class
 *
 * Handles user notifications (e.g., overdue books, admin messages)
 */
class Notification {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Get notifications for a user
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUserNotifications($userId, $limit = 5) {
        return $this->db->fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
            "ii",
            [$userId, $limit]
        );
    }

    /**
     * Mark a notification as read
     *
     * @param int $notificationId
     * @return bool
     */
    public function markAsRead($notificationId) {
        return $this->db->update(
            "UPDATE notifications SET is_read = 1 WHERE id = ?",
            "i",
            [$notificationId]
        );
    }

    /**
     * Get unread notification count for a user
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount($userId) {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) as unread FROM notifications WHERE user_id = ? AND is_read = 0",
            "i",
            [$userId]
        );
        return $row ? (int)$row['unread'] : 0;
    }
}
