<?php
/**
 * User Class
 * 
 * Handles user authentication and management
 */
class User {
    private $db;
    private $id;
    private $firstName;
    private $lastName;
    private $userName;
    private $email;
    private $userType;
    
    /**
     * Constructor
     */
    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $this->db = Database::getInstance();
        
        // Initialize user from session if available
        if (isset($_SESSION['user_id'])) {
            $this->loadUserById($_SESSION['user_id']);
        } elseif (isset($_COOKIE['remember_me_token'])) {
            $this->loginWithToken($_COOKIE['remember_me_token']);
        }
    }
    
    /**
     * Load user by ID
     * 
     * @param int $id User ID
     * @return bool Success status
     */
    public function loadUserById($id) {
        $user = $this->db->fetchOne(
            "SELECT * FROM users WHERE id = ?",
            "i",
            [$id]
        );
        
        if ($user) {
            $this->id = $user['id'];
            $this->firstName = $user['first_name'];
            $this->lastName = $user['last_name'];
            $this->userName = $user['user_name'];
            $this->email = $user['email'];
            $this->userType = $user['user_type'];
            return true;
        }
        
        return false;
    }
    
    /**
     * Load user by email
     * 
     * @param string $email User email
     * @return bool Success status
     */
    public function loadUserByEmail($email) {
        $user = $this->db->fetchOne(
            "SELECT * FROM users WHERE email = ?",
            "s",
            [$email]
        );
        
        if ($user) {
            $this->id = $user['id'];
            $this->firstName = $user['first_name'];
            $this->lastName = $user['last_name'];
            $this->userName = $user['user_name'];
            $this->email = $user['email'];
            $this->userType = $user['user_type'];
            return true;
        }
        
        return false;
    }
    
    /**
     * Check login attempts for rate limiting
     * 
     * @param string $email User email
     * @param int $maxAttempts Maximum number of attempts allowed
     * @param int $timeWindow Time window in seconds
     * @return bool|array False if rate limit not exceeded, otherwise array with wait time
     */
    private function checkLoginAttempts($email, $maxAttempts = 5, $timeWindow = 300) {
        // Initialize login attempts in session if not set
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = [];
        }
        
        // Clean up old attempts
        $now = time();
        foreach ($_SESSION['login_attempts'] as $attemptEmail => $attempts) {
            foreach ($attempts as $index => $timestamp) {
                if ($now - $timestamp > $timeWindow) {
                    unset($_SESSION['login_attempts'][$attemptEmail][$index]);
                }
            }
            
            // Remove empty arrays
            if (empty($_SESSION['login_attempts'][$attemptEmail])) {
                unset($_SESSION['login_attempts'][$attemptEmail]);
            }
        }
        
        // Check if email has attempts
        if (!isset($_SESSION['login_attempts'][$email])) {
            $_SESSION['login_attempts'][$email] = [];
        }
        
        // Check if rate limit exceeded
        if (count($_SESSION['login_attempts'][$email]) >= $maxAttempts) {
            // Get oldest attempt
            $oldestAttempt = min($_SESSION['login_attempts'][$email]);
            $waitTime = $timeWindow - ($now - $oldestAttempt);
            
            return [
                'exceeded' => true,
                'waitTime' => $waitTime
            ];
        }
        
        return false;
    }
    
    /**
     * Record login attempt
     * 
     * @param string $email User email
     */
    private function recordLoginAttempt($email) {
        if (!isset($_SESSION['login_attempts'][$email])) {
            $_SESSION['login_attempts'][$email] = [];
        }
        
        $_SESSION['login_attempts'][$email][] = time();
    }
    
    /**
     * Clear login attempts for an email
     * 
     * @param string $email User email
     */
    private function clearLoginAttempts($email) {
        if (isset($_SESSION['login_attempts'][$email])) {
            unset($_SESSION['login_attempts'][$email]);
        }
    }
    
    /**
     * Authenticate user
     * 
     * @param string $email User email
     * @param string $password User password
     * @return bool|array Authentication success or rate limit info
     */
    public function authenticate($email, $password) {
        // Check for rate limiting
        $rateLimitCheck = $this->checkLoginAttempts($email);
        if ($rateLimitCheck !== false) {
            return $rateLimitCheck;
        }
        
        $user = $this->db->fetchOne(
            "SELECT * FROM users WHERE email = ?",
            "s",
            [$email]
        );
        
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_regenerate_id(true);
            }

            // Set user properties
            $this->id = $user['id'];
            $this->firstName = $user['first_name'];
            $this->lastName = $user['last_name'];
            $this->userName = $user['user_name'];
            $this->email = $user['email'];
            $this->userType = $user['user_type'];
            
            // Set session
            $_SESSION['user_id'] = $this->id;
            $_SESSION['email'] = $this->email;
            $_SESSION['user_type'] = $this->userType;
            
            // Clear login attempts on successful login
            $this->clearLoginAttempts($email);
            
            // Log successful login
            Helper::logSecurity("User {$this->id} ({$this->email}) logged in successfully");
            
            return true;
        }
        
        // Record failed login attempt
        $this->recordLoginAttempt($email);
        
        // Log failed login attempt
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        Helper::logSecurity("Failed login attempt for email: {$email} from IP: {$ipAddress}");
        
        return false;
    }
    
    /**
     * Register a new user
     * 
     * @param array $userData User data
     * @return int|false User ID or false on failure
     */
    public function register($userData) {
        // Check if email already exists
        $existingUser = $this->db->fetchOne(
            "SELECT id FROM users WHERE email = ?",
            "s",
            [$userData['email']]
        );
        
        if ($existingUser) {
            Helper::logWarning("Registration attempt with existing email: {$userData['email']}");
            return false; // Email already exists
        }
        
        // Hash password
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Insert user
        $userId = $this->db->insert(
            "INSERT INTO users (first_name, last_name, user_name, email, password, user_type) VALUES (?, ?, ?, ?, ?, ?)",
            "sssssi",
            [
                $userData['first_name'],
                $userData['last_name'],
                $userData['user_name'],
                $userData['email'],
                $hashedPassword,
                isset($userData['user_type']) ? $userData['user_type'] : 1 // Default to regular user
            ]
        );
        
        if ($userId) {
            // Log successful registration
            Helper::logInfo("New user registered: ID {$userId}, Email: {$userData['email']}, Name: {$userData['first_name']} {$userData['last_name']}");
        } else {
            // Log failed registration
            Helper::logError("Failed to register user: Email: {$userData['email']}, Name: {$userData['first_name']} {$userData['last_name']}");
        }
        
        return $userId;
    }
    
    /**
     * Update user profile
     * 
     * @param array $userData User data to update
     * @return bool Success status
     */
    public function updateProfile($userData) {
        if (!$this->id) {
            return false;
        }
        
        $result = $this->db->update(
            "UPDATE users SET first_name = ?, last_name = ?, user_name = ? WHERE id = ?",
            "sssi",
            [
                $userData['first_name'],
                $userData['last_name'],
                $userData['user_name'],
                $this->id
            ]
        );
        
        if ($result) {
            $this->firstName = $userData['first_name'];
            $this->lastName = $userData['last_name'];
            $this->userName = $userData['user_name'];
            return true;
        }
        
        return false;
    }
    
    /**
     * Change user password
     * 
     * @param string $currentPassword Current password
     * @param string $newPassword New password
     * @return bool Success status
     */
    public function changePassword($currentPassword, $newPassword) {
        if (!$this->id) {
            return false;
        }
        
        // Verify current password
        $user = $this->db->fetchOne(
            "SELECT password FROM users WHERE id = ?",
            "i",
            [$this->id]
        );
        
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return false;
        }
        
        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        return $this->db->update(
            "UPDATE users SET password = ? WHERE id = ?",
            "si",
            [$hashedPassword, $this->id]
        );
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Log logout event before unsetting user properties
        if ($this->isLoggedIn()) {
            Helper::logSecurity("User {$this->id} ({$this->email}) logged out");
        }
        
        // Unset all user properties
        $this->id = null;
        $this->firstName = null;
        $this->lastName = null;
        $this->userName = null;
        $this->email = null;
        $this->userType = null;
        
        // Unset session variables
        unset($_SESSION['user_id']);
        unset($_SESSION['email']);
        unset($_SESSION['user_type']);
        
        // Clear remember me token cookie and delete from DB
        if (isset($_COOKIE['remember_me_token'])) {
            $this->deleteRememberMeToken($_COOKIE['remember_me_token']);
            setcookie('remember_me_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
        }
        
        // Destroy session
        session_destroy();

        // Clear session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    public function isLoggedIn() {
        return isset($this->id);
    }
    
    /**
     * Check if user is admin
     * 
     * @return bool
     */
    public function isAdmin() {
        return $this->isLoggedIn() && $this->userType == 0;
    }
    
    /**
     * Get all users (admin function)
     * 
     * @return array
     */
    public function getAllUsers() {
        if (!$this->isAdmin()) {
            return [];
        }
        
        return $this->db->fetchAll("SELECT id, first_name, last_name, user_name, email, user_type, created_at FROM users ORDER BY id");
    }
    
    /**
     * Get user ID
     * 
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get user email
     * 
     * @return string|null
     */
    public function getEmail() {
        return $this->email;
    }
    
    /**
     * Get user full name
     * 
     * @return string
     */
    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }
    
    /**
     * Get user first name
     * 
     * @return string|null
     */
    public function getFirstName() {
        return $this->firstName;
    }
    
    /**
     * Get user last name
     * 
     * @return string|null
     */
    public function getLastName() {
        return $this->lastName;
    }
    
    /**
     * Get username
     * 
     * @return string|null
     */
    public function getUserName() {
        return $this->userName;
    }
    
    /**
     * Get user type
     * 
     * @return int|null
     */
    public function getUserType() {
        return $this->userType;
    }

    /**
     * Create a remember me token for the user
     * 
     * @param int $userId User ID
     * @return string|false Token string or false on failure
     */
    public function createRememberMeToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30); // 30 days
        
        $inserted = $this->db->insert(
            "INSERT INTO user_tokens (user_id, token, expires_at) VALUES (?, ?, ?)",
            "iss",
            [$userId, $token, $expiresAt]
        );
        
        if ($inserted) {
            return $token;
        }
        
        return false;
    }
    
    /**
     * Validate remember me token and log in user
     * 
     * @param string $token Token string
     * @return bool Success status
     */
    public function loginWithToken($token) {
        $now = date('Y-m-d H:i:s');
        $row = $this->db->fetchOne(
            "SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > ?",
            "ss",
            [$token, $now]
        );
        
        if ($row) {
            $this->loadUserById($row['user_id']);
            
            // Set session variables
            $_SESSION['user_id'] = $this->id;
            $_SESSION['email'] = $this->email;
            $_SESSION['user_type'] = $this->userType;
            
            // Regenerate session ID
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_regenerate_id(true);
            }
            
            // Refresh token expiration
            $this->refreshRememberMeToken($token);
            
            // Log login via token
            Helper::logSecurity("User {$this->id} ({$this->email}) logged in via remember me token");
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Refresh remember me token expiration
     * 
     * @param string $token Token string
     * @return bool Success status
     */
    public function refreshRememberMeToken($token) {
        $newExpiresAt = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30); // 30 days
        
        return $this->db->update(
            "UPDATE user_tokens SET expires_at = ? WHERE token = ?",
            "ss",
            [$newExpiresAt, $token]
        );
    }
    
    /**
     * Delete remember me token
     * 
     * @param string $token Token string
     * @return bool Success status
     */
    public function deleteRememberMeToken($token) {
        return $this->db->delete(
            "DELETE FROM user_tokens WHERE token = ?",
            "s",
            [$token]
        );
    }
}
