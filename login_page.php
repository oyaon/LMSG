<?php
// Include necessary files
require_once "includes/init_simple.php";
include "header.php";
// navbar is already included in header.php, no need to include it again

// Check if there's a flash message
$flashMessage = '';
$flashType = '';
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    $flashType = $_SESSION['flash_type'] ?? 'info';
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}

// Check if there's a redirect URL
$redirectUrl = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'index.php';
?>

<div class="container my-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="auth-card card shadow">
                <div class="card-header bg-white">
                    <h3>Login to Your Account</h3>
                </div>
                <div class="card-body">
                    <?php if ($flashMessage): ?>
                        <?php echo renderAlert($flashMessage, $flashType); ?>
                    <?php endif; ?>
                    
                    <form action="login_submit.php" method="POST" class="needs-validation" novalidate>
                        <!-- Hidden redirect field -->
                        <input type="hidden" name="redirect" value="<?php echo $redirectUrl; ?>"

// Check if there's a flash message
$flashMessage = '';
$flashType = '';
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    $flashType = $_SESSION['flash_type'] ?? 'info';
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}

// Check if there's a redirect URL
$redirectUrl = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'index.php';
?>

<div class="container my-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="auth-card card shadow">
                <div class="card-header bg-white">
                    <h3>Login to Your Account</h3>
                </div>
                <div class="card-body">
                    <?php if ($flashMessage): ?>
                        <?php echo renderAlert($flashMessage, $flashType); ?>
                    <?php endif; ?>
                    
                    <form action="login_submit.php" method="POST" class="needs-validation" novalidate>
                        <!-- Hidden redirect field -->
                        <input type="hidden" name="redirect" value="<?php echo $redirectUrl; ?>">
                        
                        <!-- CSRF Token -->
                        <?php if (function_exists('Helper::csrfTokenField')): ?>
                            <?php echo Helper::csrfTokenField('login_form'); ?>
                        <?php endif; ?>
                        
                        <!-- Email Field -->
                        <?php echo renderFormInput(
                            'email',
                            'email',
                            'Email Address',
                            '',
                            true,
                            'Enter your email address',
                            '',
                            [
                                'autocomplete' => 'email',
                                'data-validate-email' => 'true'
                            ]
                        ); ?>
                        
                        <!-- Password Field -->
                        <?php echo renderFormInput(
                            'password',
                            'password',
                            'Password',
                            '',
                            true,
                            'Enter your password',
                            '',
                            [
                                'autocomplete' => 'current-password',
                                'minlength' => '8'
                            ]
                        ); ?>
                        
                        <!-- Remember Me Checkbox -->
                        <?php echo renderFormCheckbox(
                            'remember_me',
                            'Remember me',
                            false,
                            false,
                            'Stay logged in on this device'
                        ); ?>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p>Don't have an account? <a href="registration_page.php" class="fw-bold">Sign up</a></p>
                        <p><a href="forgot-password.php">Forgot your password?</a></p>
                    </div>
                </div>
            </div>
            
            <!-- Social Login Options -->
            <div class="card mt-3 shadow-sm">
                <div class="card-body">
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

<?php include "footer.php"; ?>