<?php
/**
 * Database Backup Management
 * 
 * Allows administrators to create, restore, and manage database backups
 */

// Include initialization file
require_once '../includes/init.php';
require_once '../includes/DatabaseBackup.php';

// Check if user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    Helper::redirect('../login.php');
}

// Initialize database backup
$dbBackup = new DatabaseBackup();

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'backup_form')) {
        Helper::setFlashMessage('danger', 'Invalid form submission. Please try again.');
    } else {
        // Create backup
        if (isset($_POST['create_backup'])) {
            $result = $dbBackup->createBackup();
            
            if ($result) {
                Helper::setFlashMessage('success', 'Database backup created successfully.');
            } else {
                Helper::setFlashMessage('danger', 'Failed to create database backup.');
            }
        }
        
        // Restore backup
        if (isset($_POST['restore_backup']) && isset($_POST['backup_file'])) {
            $filename = Helper::sanitize($_POST['backup_file']);
            $result = $dbBackup->restoreBackup($filename);
            
            if ($result) {
                Helper::setFlashMessage('success', 'Database restored successfully from backup.');
            } else {
                Helper::setFlashMessage('danger', 'Failed to restore database from backup.');
            }
        }
        
        // Delete backup
        if (isset($_POST['delete_backup']) && isset($_POST['backup_file'])) {
            $filename = Helper::sanitize($_POST['backup_file']);
            $result = $dbBackup->deleteBackup($filename);
            
            if ($result) {
                Helper::setFlashMessage('success', 'Backup deleted successfully.');
            } else {
                Helper::setFlashMessage('danger', 'Failed to delete backup.');
            }
        }
        
        // Schedule backups
        if (isset($_POST['schedule_backups'])) {
            $frequency = Helper::sanitize($_POST['frequency']);
            $keepCount = (int) $_POST['keep_count'];
            
            $result = $dbBackup->scheduleBackups($frequency, $keepCount);
            
            if ($result) {
                Helper::setFlashMessage('success', 'Automatic backups scheduled successfully.');
            } else {
                Helper::setFlashMessage('danger', 'Failed to schedule automatic backups.');
            }
        }
    }
    
    // Redirect to avoid form resubmission
    Helper::redirect('database-backup.php');
}

// Get list of backups
$backups = $dbBackup->getBackups();

// Page title
$pageTitle = 'Database Backup Management';

// Include header
include 'header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Database Backup Management</h1>
            </div>
            
            <?php Helper::displayFlashMessage(); ?>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Create Backup</h5>
                        </div>
                        <div class="card-body">
                            <p>Create a new backup of the current database state.</p>
                            <form method="POST" action="">
                                <?php echo Helper::csrfTokenField('backup_form'); ?>
                                <button type="submit" name="create_backup" class="btn btn-primary">Create Backup</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Schedule Automatic Backups</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <?php echo Helper::csrfTokenField('backup_form'); ?>
                                <div class="mb-3">
                                    <label for="frequency" class="form-label">Backup Frequency</label>
                                    <select class="form-select" id="frequency" name="frequency">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="keep_count" class="form-label">Number of Backups to Keep</label>
                                    <input type="number" class="form-control" id="keep_count" name="keep_count" value="7" min="1" max="30">
                                </div>
                                <button type="submit" name="schedule_backups" class="btn btn-success">Schedule Backups</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Backups</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($backups)): ?>
                        <p class="text-muted">No backups available.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Size</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($backups as $backup): ?>
                                        <tr>
                                            <td><?php echo $backup['filename']; ?></td>
                                            <td><?php echo round($backup['size'] / 1024, 2); ?> KB</td>
                                            <td><?php echo date('Y-m-d H:i:s', $backup['created']); ?></td>
                                            <td>
                                                <form method="POST" action="" class="d-inline">
                                                    <?php echo Helper::csrfTokenField('backup_form'); ?>
                                                    <input type="hidden" name="backup_file" value="<?php echo $backup['filename']; ?>">
                                                    <button type="submit" name="restore_backup" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to restore this backup? Current data will be overwritten.')">Restore</button>
                                                </form>
                                                <form method="POST" action="" class="d-inline">
                                                    <?php echo Helper::csrfTokenField('backup_form'); ?>
                                                    <input type="hidden" name="backup_file" value="<?php echo $backup['filename']; ?>">
                                                    <button type="submit" name="delete_backup" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this backup?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'footer.php'; ?>