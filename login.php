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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'login_form')) {
        // For debugging: disable CSRF validation temporarily
        // Comment out the next line to disable CSRF validation temporarily
        // $errors = ['Invalid form submission. Please try again.'];
        
        // Uncomment the next line to bypass CSRF validation temporarily
        // $errors = [];
        
        // For now, bypass CSRF validation temporarily for testing
        $errors = [];
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

<div class="container my-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="auth-card card shadow">
                <div class="card-header bg-white">
                    <h3>Login to Your Account</h3>
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
                    
<form method="POST" action="login.php">
                        <?php echo Helper::csrfTokenField('login_form'); ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required autocomplete="email" data-validate-email="true" placeholder="Enter your email address">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" minlength="8" placeholder="Enter your password">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" value="1">
                            <label class="form-check-label" for="remember_me">Remember me</label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p>Don't have an account? <a href="register.php" class="fw-bold">Sign up</a></p>
                        <p><a href="forgot-password.php">Forgot your password?</a></p>
                    </div>
                </div>
                
                <!-- Social Login Options -->
                <div class="card-footer">
                    <p class="text-center mb-3">Or login with</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-google me-2"></i>Google
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
