<?php
/**
 * Helper Class
 * 
 * Contains utility functions for sanitizing input, validating email, redirecting,
 * and error handling.
 */ 
class Helper {
    /**
     * Sanitize input data
     * 
     * @param string $data Input data
     * @return string Sanitized data
     */
    public static function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    /**
     * Validate email format
     * 
     * @param string $email Email address
     * @return bool True if valid, false otherwise
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Redirect to a specified URL
     * 
     * @param string $url URL to redirect to
     */
    public static function redirect($url) {
        header("Location: $url");
        exit();
    }
    
    /**
     * Set flash message for session
     * 
     * @param string $type Message type (success, error, etc.)
     * @param string $message Message content
     */
    public static function setFlashMessage($type, $message) {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Display flash message and clear it from session
     */
    public static function displayFlashMessage() {
        if (isset($_SESSION['flash_message'])) {
            $type = $_SESSION['flash_message']['type'];
            $message = $_SESSION['flash_message']['message'];
            
            echo '<div class="alert alert-' . $type . '">' . $message . '</div>';
            
            unset($_SESSION['flash_message']);
        }
    }
    
    /**
     * Generate CSRF token and store in session
     * 
     * @param string $formName Name of the form (to support multiple forms)
     * @return string CSRF token
     */
    public static function generateCsrfToken($formName = 'default') {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
        
        // Store in session with form name as key
        $_SESSION['csrf_tokens'][$formName] = [
            'token' => $token,
            'time' => time()
        ];
        
        return $token;
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token Token to validate
     * @param string $formName Name of the form
     * @param int $expireTime Token expiration time in seconds (default: 3600 = 1 hour)
     * @return bool True if valid, false otherwise
     */
    public static function validateCsrfToken($token, $formName = 'default', $expireTime = 3600) {
        // Check if token exists in session
        if (!isset($_SESSION['csrf_tokens'][$formName])) {
            return false;
        }
        
        $storedToken = $_SESSION['csrf_tokens'][$formName]['token'];
        $tokenTime = $_SESSION['csrf_tokens'][$formName]['time'];
        
        // Check if token has expired
        if (time() - $tokenTime > $expireTime) {
            // Remove expired token
            unset($_SESSION['csrf_tokens'][$formName]);
            return false;
        }
        
        // Validate token
        if (hash_equals($storedToken, $token)) {
            // Remove used token (one-time use) only if formName is not 'login_form'
            if ($formName !== 'login_form') {
                unset($_SESSION['csrf_tokens'][$formName]);
            }
            return true;
        }
        
        return false;
    }
    
    /**
     * Generate CSRF token field for forms
     * 
     * @param string $formName Name of the form
     * @return string HTML input field with CSRF token
     */
    public static function csrfTokenField($formName = 'default') {
        $token = self::generateCsrfToken($formName);
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
    
    /**
     * Log message to file
     * 
     * @param string $message Log message
     * @param string $level Log level (error, warning, info, debug)
     * @param string $file File where event occurred
     * @param int $line Line number where event occurred
     */
    public static function log($message, $level = 'info', $file = '', $line = 0) {
        $logDir = __DIR__ . '/../logs';
        
        // Create logs directory if it doesn't exist
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Determine log file based on level
        $logFile = $logDir . '/' . $level . '.log';
        
        // Get user info if available
        $userInfo = '';
        if (isset($_SESSION['user_id'])) {
            $userInfo = " [User: {$_SESSION['user_id']}]";
        }
        
        // Get IP address
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        // Format timestamp
        $timestamp = date('Y-m-d H:i:s');
        
        // Format log message
        $logMessage = "[$timestamp][$level][$ipAddress]$userInfo $message";
        
        if ($file && $line) {
            $logMessage .= " in $file on line $line";
        }
        
        $logMessage .= PHP_EOL;
        
        // Append to log file
        file_put_contents($logFile, $logMessage, FILE_APPEND);
        
        // Also log to PHP error log if it's an error or warning
        if ($level === 'error' || $level === 'warning') {
            error_log($message);
        }
    }
    
    /**
     * Log error message
     * 
     * @param string $message Error message
     * @param string $file File where error occurred
     * @param int $line Line number where error occurred
     */
    public static function logError($message, $file = '', $line = 0) {
        self::log($message, 'error', $file, $line);
    }
    
    /**
     * Log warning message
     * 
     * @param string $message Warning message
     * @param string $file File where warning occurred
     * @param int $line Line number where warning occurred
     */
    public static function logWarning($message, $file = '', $line = 0) {
        self::log($message, 'warning', $file, $line);
    }
    
    /**
     * Log info message
     * 
     * @param string $message Info message
     */
    public static function logInfo($message) {
        self::log($message, 'info');
    }
    
    /**
     * Log debug message
     * 
     * @param string $message Debug message
     */
    public static function logDebug($message) {
        // Only log debug messages in development environment
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            self::log($message, 'debug');
        }
    }
    
    /**
     * Log security event
     * 
     * @param string $message Security event message
     */
    public static function logSecurity($message) {
        self::log($message, 'security');
    }
    
    /**
     * Handle exception - log it and optionally set flash message
     * 
     * @param Exception $e Exception to handle
     * @param bool $setFlash Whether to set flash message
     * @param string $userMessage User-friendly message (optional)
     */
    public static function handleException($e, $setFlash = true, $userMessage = '') {
        // Log the exception
        self::logError($e->getMessage(), $e->getFile(), $e->getLine());
        
        // Set flash message if requested
        if ($setFlash) {
            $message = $userMessage ?: 'An error occurred. Please try again later.';
            self::setFlashMessage('danger', $message);
        }
    }
    
    /**
     * Get user-friendly database error message
     * 
     * @param string $operation Operation type (create, update, delete, fetch, search)
     * @param string $entity Entity type (book, author, user, etc.)
     * @return string User-friendly error message
     */
    public static function getDatabaseErrorMessage($operation, $entity) {
        $messages = [
            'create' => "Failed to create new $entity.",
            'update' => "Failed to update $entity.",
            'delete' => "Failed to delete $entity.",
            'fetch' => "Failed to retrieve $entity data.",
            'search' => "Failed to search for $entity."
        ];
        
        return $messages[$operation] ?? "An error occurred while processing your request.";
    }
    
    /**
     * Validate password strength
     * 
     * @param string $password Password to validate
     * @param int $minLength Minimum password length (default: 8)
     * @param bool $requireMixedCase Require mixed case (default: true)
     * @param bool $requireNumbers Require numbers (default: true)
     * @param bool $requireSymbols Require symbols (default: false)
     * @return array Array with 'valid' (bool) and 'message' (string) keys
     */
    public static function validatePassword($password, $minLength = 8, $requireMixedCase = true, $requireNumbers = true, $requireSymbols = false) {
        $result = [
            'valid' => true,
            'message' => ''
        ];
        
        // Check length
        if (strlen($password) < $minLength) {
            $result['valid'] = false;
            $result['message'] = "Password must be at least $minLength characters long.";
            return $result;
        }
        
        // Check for mixed case
        if ($requireMixedCase && !preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password)) {
            $result['valid'] = false;
            $result['message'] = 'Password must include both uppercase and lowercase letters.';
            return $result;
        }
        
        // Check for numbers
        if ($requireNumbers && !preg_match('/[0-9]/', $password)) {
            $result['valid'] = false;
            $result['message'] = 'Password must include at least one number.';
            return $result;
        }
        
        // Check for symbols
        if ($requireSymbols && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $result['valid'] = false;
            $result['message'] = 'Password must include at least one special character.';
            return $result;
        }
        
        return $result;
    }
}
