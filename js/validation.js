/**
 * Library Management System - Form Validation
 * 
 * This script handles client-side form validation for the LMS application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all forms that need validation
    const forms = document.querySelectorAll('.needs-validation');
    
    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
        
        // Add custom validation for specific fields
        setupCustomValidation(form);
    });
    
    // Password strength meter
    const passwordInputs = document.querySelectorAll('input[type="password"][data-password-strength]');
    passwordInputs.forEach(input => {
        const strengthMeter = document.createElement('div');
        strengthMeter.className = 'password-strength-meter mt-2';
        strengthMeter.innerHTML = `
            <div class="progress" style="height: 5px;">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="password-strength-text text-muted mt-1">Password strength: Too weak</small>
        `;
        
        input.parentNode.insertBefore(strengthMeter, input.nextSibling);
        
        input.addEventListener('input', () => {
            updatePasswordStrength(input);
        });
    });
});

/**
 * Setup custom validation for specific form fields
 * 
 * @param {HTMLFormElement} form - The form element
 */
function setupCustomValidation(form) {
    // Email validation
    const emailInputs = form.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('input', () => {
            validateEmail(input);
        });
    });
    
    // Password validation
    const passwordInputs = form.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', () => {
            validatePassword(input);
        });
    });
    
    // Password confirmation validation
    const passwordConfirmInputs = form.querySelectorAll('input[data-match-password]');
    passwordConfirmInputs.forEach(input => {
        input.addEventListener('input', () => {
            validatePasswordMatch(input);
        });
    });
    
    // Username validation
    const usernameInputs = form.querySelectorAll('input[data-validate-username]');
    usernameInputs.forEach(input => {
        input.addEventListener('input', () => {
            validateUsername(input);
        });
    });
}

/**
 * Validate email format
 * 
 * @param {HTMLInputElement} input - The email input element
 * @returns {boolean} - Whether the email is valid
 */
function validateEmail(input) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const isValid = emailRegex.test(input.value);
    
    if (isValid) {
        input.setCustomValidity('');
    } else {
        input.setCustomValidity('Please enter a valid email address');
    }
    
    return isValid;
}

/**
 * Validate password strength
 * 
 * @param {HTMLInputElement} input - The password input element
 * @returns {boolean} - Whether the password is valid
 */
function validatePassword(input) {
    const minLength = input.getAttribute('minlength') || 8;
    const value = input.value;
    
    // Check if password meets minimum length
    if (value.length < minLength) {
        input.setCustomValidity(`Password must be at least ${minLength} characters long`);
        return false;
    }
    
    // Additional validation can be added here
    // For example, requiring uppercase, lowercase, numbers, special characters
    
    input.setCustomValidity('');
    return true;
}

/**
 * Validate password confirmation matches password
 * 
 * @param {HTMLInputElement} input - The password confirmation input element
 * @returns {boolean} - Whether the passwords match
 */
function validatePasswordMatch(input) {
    const passwordId = input.getAttribute('data-match-password');
    const passwordInput = document.getElementById(passwordId);
    
    if (!passwordInput) {
        console.error(`Password input with ID "${passwordId}" not found`);
        return false;
    }
    
    const isValid = input.value === passwordInput.value;
    
    if (isValid) {
        input.setCustomValidity('');
    } else {
        input.setCustomValidity('Passwords do not match');
    }
    
    return isValid;
}

/**
 * Validate username format
 * 
 * @param {HTMLInputElement} input - The username input element
 * @returns {boolean} - Whether the username is valid
 */
function validateUsername(input) {
    const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
    const isValid = usernameRegex.test(input.value);
    
    if (isValid) {
        input.setCustomValidity('');
    } else {
        input.setCustomValidity('Username must be 3-20 characters and can only contain letters, numbers, and underscores');
    }
    
    return isValid;
}

/**
 * Update password strength meter
 * 
 * @param {HTMLInputElement} input - The password input element
 */
function updatePasswordStrength(input) {
    const value = input.value;
    const meter = input.nextSibling;
    const progressBar = meter.querySelector('.progress-bar');
    const strengthText = meter.querySelector('.password-strength-text');
    
    // Calculate password strength
    let strength = 0;
    let feedback = 'Too weak';
    
    if (value.length >= 8) {
        strength += 25;
    }
    
    if (value.match(/[A-Z]/)) {
        strength += 25;
    }
    
    if (value.match(/[0-9]/)) {
        strength += 25;
    }
    
    if (value.match(/[^A-Za-z0-9]/)) {
        strength += 25;
    }
    
    // Update progress bar
    progressBar.style.width = `${strength}%`;
    progressBar.setAttribute('aria-valuenow', strength);
    
    // Update color based on strength
    if (strength < 25) {
        progressBar.className = 'progress-bar bg-danger';
        feedback = 'Too weak';
    } else if (strength < 50) {
        progressBar.className = 'progress-bar bg-warning';
        feedback = 'Weak';
    } else if (strength < 75) {
        progressBar.className = 'progress-bar bg-info';
        feedback = 'Medium';
    } else {
        progressBar.className = 'progress-bar bg-success';
        feedback = 'Strong';
    }
    
    // Update text
    strengthText.textContent = `Password strength: ${feedback}`;
}

/**
 * Debounce function to limit how often a function can be called
 * 
 * @param {Function} func - The function to debounce
 * @param {number} wait - The debounce wait time in milliseconds
 * @returns {Function} - The debounced function
 */
function debounce(func, wait) {
    let timeout;
    
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Async validation with server
 * 
 * @param {string} url - The URL to send the validation request to
 * @param {Object} data - The data to send with the request
 * @returns {Promise} - A promise that resolves with the validation result
 */
async function validateWithServer(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Validation error:', error);
        return { valid: false, message: 'An error occurred during validation' };
    }
}/**
 * Library Management System - Form Validation
 * 
 * This script handles client-side form validation for the LMS application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all forms that need validation
    const forms = document.querySelectorAll('.needs-validation');
    
    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
        
        // Add custom validation for specific fields
        setupCustomValidation(form);
    });
    
    // Password strength meter
    const passwordInputs = document.querySelectorAll('input[type="password"][data-password-strength]');
    passwordInputs.forEach(input => {
        const strengthMeter = document.createElement('div');
        strengthMeter.className = 'password-strength-meter mt-2';
        strengthMeter.innerHTML = `
            <div class="progress" style="height: 5px;">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="password-strength-text text-muted mt-1">Password strength: Too weak</small>
        `;
        
        input.parentNode.insertBefore(strengthMeter, input.nextSibling);
        
        input.addEventListener('input', () => {
            updatePasswordStrength(input);
        });
    });
});

/**
 * Setup custom validation for specific form fields
 * 
 * @param {HTMLFormElement} form - The form element
 */
function setupCustomValidation(form) {
    // Email validation
    const emailInputs = form.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('input', () => {
            validateEmail(input);
        });
    });
    
    // Password validation
    const passwordInputs = form.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', () => {
            validatePassword(input);
        });
    });
    
    // Password confirmation validation
    const passwordConfirmInputs = form.querySelectorAll('input[data-match-password]');
    passwordConfirmInputs.forEach(input => {
        input.addEventListener('input', () => {
            validatePasswordMatch(input);
        });
    });
    
    // Username validation
    const usernameInputs = form.querySelectorAll('input[data-validate-username]');
    usernameInputs.forEach(input => {
        input.addEventListener('input', () => {
            validateUsername(input);
        });
    });
}

/**
 * Validate email format
 * 
 * @param {HTMLInputElement} input - The email input element
 * @returns {boolean} - Whether the email is valid
 */
function validateEmail(input) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const isValid = emailRegex.test(input.value);
    
    if (isValid) {
        input.setCustomValidity('');
    } else {
        input.setCustomValidity('Please enter a valid email address');
    }
    
    return isValid;
}

/**
 * Validate password strength
 * 
 * @param {HTMLInputElement} input - The password input element
 * @returns {boolean} - Whether the password is valid
 */
function validatePassword(input) {
    const minLength = input.getAttribute('minlength') || 8;
    const value = input.value;
    
    // Check if password meets minimum length
    if (value.length < minLength) {
        input.setCustomValidity(`Password must be at least ${minLength} characters long`);
        return false;
    }
    
    // Additional validation can be added here
    // For example, requiring uppercase, lowercase, numbers, special characters
    
    input.setCustomValidity('');
    return true;
}

/**
 * Validate password confirmation matches password
 * 
 * @param {HTMLInputElement} input - The password confirmation input element
 * @returns {boolean} - Whether the passwords match
 */
function validatePasswordMatch(input) {
    const passwordId = input.getAttribute('data-match-password');
    const passwordInput = document.getElementById(passwordId);
    
    if (!passwordInput) {
        console.error(`Password input with ID "${passwordId}" not found`);
        return false;
    }
    
    const isValid = input.value === passwordInput.value;
    
    if (isValid) {
        input.setCustomValidity('');
    } else {
        input.setCustomValidity('Passwords do not match');
    }
    
    return isValid;
}

/**
 * Validate username format
 * 
 * @param {HTMLInputElement} input - The username input element
 * @returns {boolean} - Whether the username is valid
 */
function validateUsername(input) {
    const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
    const isValid = usernameRegex.test(input.value);
    
    if (isValid) {
        input.setCustomValidity('');
    } else {
        input.setCustomValidity('Username must be 3-20 characters and can only contain letters, numbers, and underscores');
    }
    
    return isValid;
}

/**
 * Update password strength meter
 * 
 * @param {HTMLInputElement} input - The password input element
 */
function updatePasswordStrength(input) {
    const value = input.value;
    const meter = input.nextSibling;
    const progressBar = meter.querySelector('.progress-bar');
    const strengthText = meter.querySelector('.password-strength-text');
    
    // Calculate password strength
    let strength = 0;
    let feedback = 'Too weak';
    
    if (value.length >= 8) {
        strength += 25;
    }
    
    if (value.match(/[A-Z]/)) {
        strength += 25;
    }
    
    if (value.match(/[0-9]/)) {
        strength += 25;
    }
    
    if (value.match(/[^A-Za-z0-9]/)) {
        strength += 25;
    }
    
    // Update progress bar
    progressBar.style.width = `${strength}%`;
    progressBar.setAttribute('aria-valuenow', strength);
    
    // Update color based on strength
    if (strength < 25) {
        progressBar.className = 'progress-bar bg-danger';
        feedback = 'Too weak';
    } else if (strength < 50) {
        progressBar.className = 'progress-bar bg-warning';
        feedback = 'Weak';
    } else if (strength < 75) {
        progressBar.className = 'progress-bar bg-info';
        feedback = 'Medium';
    } else {
        progressBar.className = 'progress-bar bg-success';
        feedback = 'Strong';
    }
    
    // Update text
    strengthText.textContent = `Password strength: ${feedback}`;
}

/**
 * Debounce function to limit how often a function can be called
 * 
 * @param {Function} func - The function to debounce
 * @param {number} wait - The debounce wait time in milliseconds
 * @returns {Function} - The debounced function
 */
function debounce(func, wait) {
    let timeout;
    
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Async validation with server
 * 
 * @param {string} url - The URL to send the validation request to
 * @param {Object} data - The data to send with the request
 * @returns {Promise} - A promise that resolves with the validation result
 */
async function validateWithServer(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Validation error:', error);
        return { valid: false, message: 'An error occurred during validation' };
    }
}