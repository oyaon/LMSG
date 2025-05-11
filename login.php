<?php
/**
 * Login Page
 * 
 * Handles user authentication
 */

// Include initialization file
require_once 'includes/init.php';

// Check if user is already logged in
if ($user->isLoggedIn()) {
    // Redirect based on user type
    if ($user->isAdmin()) {
        Helper::redirect('admin/index.php');
    } else {
        Helper::redirect('index.php');
    }
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'login_form')) {
        $errors = ['Invalid form submission. Please try again.'];
    } else {
        // Sanitize input
        $email = Helper::sanitize($_POST['email']);
        $password = $_POST['password']; // Don't sanitize password
        
        // Validate input
        $errors = [];
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!Helper::validateEmail($email)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($password)) {
            $errors[] = 'Password is required';
        }
        
        // Attempt login if no errors
        if (empty($errors)) {
            $authResult = $user->authenticate($email, $password);
            
            if ($authResult === true) {
                // Successful login
                // Redirect based on user type
                if ($user->isAdmin()) {
                    Helper::redirect('admin/index.php');
                } else {
                    Helper::redirect('index.php');
                }
            } elseif (is_array($authResult) && isset($authResult['exceeded'])) {
                // Rate limit exceeded
                $waitMinutes = ceil($authResult['waitTime'] / 60);
                $errors[] = "Too many failed login attempts. Please try again after {$waitMinutes} minute(s).";
            } else {
                // Failed login
                $errors[] = 'Invalid email or password';
            }
        }
    }
}

// Page title
$pageTitle = 'Login';

// Include header
include 'header.php';
// Removed include 'top-navbar.php' to avoid duplicate navbar since header.php already includes navbar
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Login</h3>
                </div>
                <div class="card-body">
                    <?php Helper::displayFlashMessage(); ?>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <?php echo Helper::csrfTokenField('login_form'); ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>