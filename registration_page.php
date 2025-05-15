<?php
// Include necessary files
require_once "includes/init.php";
include "header.php";
include "navbar.php";

// Check if there's a flash message
$flashMessage = '';
$flashType = '';
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    $flashType = $_SESSION['flash_type'] ?? 'info';
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}
?>

<div class="container my-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="auth-card card shadow">
                <div class="card-header bg-white">
                    <h3>Create an Account</h3>
                </div>
                <div class="card-body">
                    <?php if ($flashMessage): ?>
                        <?php echo renderAlert($flashMessage, $flashType); ?>
                    <?php endif; ?>
                    
                    <form action="admin/registration_submit.php" method="POST" class="needs-validation" novalidate>
                        <!-- CSRF Token -->
                        <?php if (function_exists('Helper::csrfTokenField')): ?>
                            <?php echo Helper::csrfTokenField('registration_form'); ?>
                        <?php endif; ?>
                        
                        <div class="row">
                            <!-- First Name Field -->
                            <div class="col-md-6">
                                <?php echo renderFormInput(
                                    'text',
                                    'firstname',
                                    'First Name',
                                    '',
                                    true,
                                    'Enter your first name',
                                    '',
                                    [
                                        'autocomplete' => 'given-name',
                                        'minlength' => '2',
                                        'maxlength' => '50'
                                    ]
                                ); ?>
                            </div>
                            
                            <!-- Last Name Field -->
                            <div class="col-md-6">
                                <?php echo renderFormInput(
                                    'text',
                                    'lastname',
                                    'Last Name',
                                    '',
                                    true,
                                    'Enter your last name',
                                    '',
                                    [
                                        'autocomplete' => 'family-name',
                                        'minlength' => '2',
                                        'maxlength' => '50'
                                    ]
                                ); ?>
                            </div>
                        </div>
                        
                        <!-- Username Field -->
                        <?php echo renderFormInput(
                            'text',
                            'username',
                            'Username',
                            '',
                            true,
                            'Choose a username',
                            'Username must be 3-20 characters and can only contain letters, numbers, and underscores',
                            [
                                'autocomplete' => 'username',
                                'minlength' => '3',
                                'maxlength' => '20',
                                'pattern' => '^[a-zA-Z0-9_]{3,20}$',
                                'data-validate-username' => 'true'
                            ]
                        ); ?>
                        
                        <!-- Email Field -->
                        <?php echo renderFormInput(
                            'email',
                            'email',
                            'Email Address',
                            '',
                            true,
                            'Enter your email address',
                            'We\'ll never share your email with anyone else',
                            [
                                'autocomplete' => 'email',
                                'data-validate-email' => 'true'
                            ]
                        ); ?>
                        
                        <div class="row">
                            <!-- Password Field -->
                            <div class="col-md-6">
                                <?php echo renderFormInput(
                                    'password',
                                    'password',
                                    'Password',
                                    '',
                                    true,
                                    'Create a password',
                                    'Password must be at least 8 characters long',
                                    [
                                        'id' => 'password',
                                        'autocomplete' => 'new-password',
                                        'minlength' => '8',
                                        'data-password-strength' => 'true'
                                    ]
                                ); ?>
                            </div>
                            
                            <!-- Confirm Password Field -->
                            <div class="col-md-6">
                                <?php echo renderFormInput(
                                    'password',
                                    'confirm_password',
                                    'Confirm Password',
                                    '',
                                    true,
                                    'Confirm your password',
                                    '',
                                    [
                                        'autocomplete' => 'new-password',
                                        'data-match-password' => 'password'
                                    ]
                                ); ?>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <?php echo renderFormCheckbox(
                            'agree_terms',
                            'I agree to the Terms of Service and Privacy Policy',
                            false,
                            true,
                            'By creating an account, you agree to our <a href="terms-of-service.php" target="_blank">Terms of Service</a> and <a href="privacy-policy.php" target="_blank">Privacy Policy</a>'
                        ); ?>
                        
                        <!-- Entry Fee Notice -->
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Make sure to pay 100Tk as an entry fee while borrowing your first book.
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p>Already have an account? <a href="login.php" class="fw-bold">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>