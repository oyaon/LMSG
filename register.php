<?php
/**
 * Registration Page
 * 
 * Handles user registration
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

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'register_form')) {
        $errors = ['Invalid form submission. Please try again.'];
    } else {
        // Sanitize input
        $firstName = Helper::sanitize($_POST['first_name']);
        $lastName = Helper::sanitize($_POST['last_name']);
        $userName = Helper::sanitize($_POST['user_name']);
        $email = Helper::sanitize($_POST['email']);
        $password = $_POST['password']; // Don't sanitize password
        $confirmPassword = $_POST['confirm_password']; // Don't sanitize password
        
        // Validate input
        $errors = [];
        
        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }
        
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }
        
        if (empty($userName)) {
            $errors[] = 'Username is required';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!Helper::validateEmail($email)) {
            $errors[] = 'Invalid email format';
        }
        
        // Use the new password validation function
        if (empty($password)) {
            $errors[] = 'Password is required';
        } else {
            $passwordValidation = Helper::validatePassword($password, 6, false, false, false);
            if (!$passwordValidation['valid']) {
                $errors[] = $passwordValidation['message'];
            }
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }
        
        // Register user if no errors
        if (empty($errors)) {
            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'user_name' => $userName,
                'email' => $email,
                'password' => $password,
                'user_type' => 1 // Regular user
            ];
            
            $result = $user->register($userData);
            
            if ($result) {
                // Set success message
                Helper::setFlashMessage('success', 'Registration successful! You can now login.');
                Helper::redirect('login.php');
            } else {
                $errors[] = 'Email already exists or registration failed';
            }
        }
    }
}

// Page title
$pageTitle = 'Register';

// Include header
include ("header.php");
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Register</h3>
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
                        <?php echo Helper::csrfTokenField('register_form'); ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($firstName) ? $firstName : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($lastName) ? $lastName : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Username</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo isset($userName) ? $userName : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="text-muted">Password must be at least 6 characters</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ("footer.php"); ?>