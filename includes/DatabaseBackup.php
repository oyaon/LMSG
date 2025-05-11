<?php
/**
 * Database Backup Class
 * 
 * Handles database backup and restore operations
 */
class DatabaseBackup {
    private $db;
    private $backupDir;
    
    /**
     * Constructor
     */
    public function __construct() {
        require_once __DIR__ . '/Database.php';
        require_once __DIR__ . '/Helper.php';
        
        $this->db = Database::getInstance();
        $this->backupDir = __DIR__ . '/../database/backups';
        
        // Create backup directory if it doesn't exist
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }
    }
    
    /**
     * Create a database backup
     * 
     * @param string $filename Optional filename (default: auto-generated)
     * @return string|false Backup filename or false on failure
     */
    public function createBackup($filename = null) {
        try {
            // Generate filename if not provided
            if ($filename === null) {
                $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            }
            
            $backupPath = $this->backupDir . '/' . $filename;
            
            // Get database credentials from config
            require_once __DIR__ . '/../config/config.php';
            
            // Build mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg(DB_USER),
                escapeshellarg(DB_PASS),
                escapeshellarg(DB_HOST),
                escapeshellarg(DB_NAME),
                escapeshellarg($backupPath)
            );
            
            // Execute command
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                Helper::logError("Database backup failed: " . implode("\n", $output));
                return false;
            }
            
            Helper::logInfo("Database backup created: $filename");
            return $filename;
        } catch (Exception $e) {
            Helper::handleException($e, false, "Database backup failed");
            return false;
        }
    }
    
    /**
     * Restore database from backup
     * 
     * @param string $filename Backup filename
     * @return bool Success status
     */
    public function restoreBackup($filename) {
        try {
            $backupPath = $this->backupDir . '/' . $filename;
            
            // Check if backup file exists
            if (!file_exists($backupPath)) {
                Helper::logError("Backup file not found: $filename");
                return false;
            }
            
            // Get database credentials from config
            require_once __DIR__ . '/../config/config.php';
            
            // Build mysql command
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s %s < %s',
                escapeshellarg(DB_USER),
                escapeshellarg(DB_PASS),
                escapeshellarg(DB_HOST),
                escapeshellarg(DB_NAME),
                escapeshellarg($backupPath)
            );
            
            // Execute command
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                Helper::logError("Database restore failed: " . implode("\n", $output));
                return false;
            }
            
            Helper::logInfo("Database restored from backup: $filename");
            return true;
        } catch (Exception $e) {
            Helper::handleException($e, false, "Database restore failed");
            return false;
        }
    }
    
    /**
     * Get list of available backups
     * 
     * @return array List of backup files with metadata
     */
    public function getBackups() {
        $backups = [];
        
        // Get all .sql files in backup directory
        $files = glob($this->backupDir . '/*.sql');
        
        foreach ($files as $file) {
            $filename = basename($file);
            $backups[] = [
                'filename' => $filename,
                'size' => filesize($file),
                'created' => filemtime($file),
                'path' => $file
            ];
        }
        
        // Sort by creation time (newest first)
        usort($backups, function($a, $b) {
            return $b['created'] - $a['created'];
        });
        
        return $backups;
    }
    
    /**
     * Delete a backup file
     * 
     * @param string $filename Backup filename
     * @return bool Success status
     */
    public function deleteBackup($filename) {
        $backupPath = $this->backupDir . '/' . $filename;
        
        // Check if backup file exists
        if (!file_exists($backupPath)) {
            Helper::logError("Backup file not found: $filename");
            return false;
        }
        
        // Delete file
        if (unlink($backupPath)) {
            Helper::logInfo("Backup deleted: $filename");
            return true;
        }
        
        Helper::logError("Failed to delete backup: $filename");
        return false;
    }
    
    /**
     * Schedule automatic backups
     * 
     * @param string $frequency Backup frequency (daily, weekly, monthly)
     * @param int $keepCount Number of backups to keep
     * @return bool Success status
     */
    public function scheduleBackups($frequency = 'daily', $keepCount = 7) {
        // This method would typically set up a cron job or scheduled task
        // For simplicity, we'll just create a backup and clean up old ones
        
        // Create new backup
        $result = $this->createBackup();
        
        if ($result === false) {
            return false;
        }
        
        // Clean up old backups
        $this->cleanupBackups($keepCount);
        
        return true;
    }
    
    /**
     * Clean up old backups, keeping only the specified number
     * 
     * @param int $keepCount Number of backups to keep
     * @return int Number of backups deleted
     */
    public function cleanupBackups($keepCount = 7) {
        $backups = $this->getBackups();
        $deleteCount = 0;
        
        // If we have more backups than we want to keep
        if (count($backups) > $keepCount) {
            // Delete oldest backups
            for ($i = $keepCount; $i < count($backups); $i++) {
                if ($this->deleteBackup($backups[$i]['filename'])) {
                    $deleteCount++;
                }
            }
        }
        
        return $deleteCount;
    }
}