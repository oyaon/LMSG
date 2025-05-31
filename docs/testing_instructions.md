# Testing Instructions and Test Cases for LMS Application

## 1. Registration Flow

### Test Cases:
- Submit registration form with all valid inputs.
- Submit registration form with missing required fields.
- Submit registration form with mismatched passwords.
- Submit registration form with an already registered email.
- Submit registration form with invalid CSRF token.
- Verify password is stored hashed in the database.
- Verify successful registration logs appropriate info.
- Verify error messages are shown for invalid inputs.

### Security Checks:
- CSRF token validation on registration form submission.
- Password hashing using password_hash.
- Email uniqueness enforced.
- No SQL injection vulnerabilities (prepared statements used).

---

## 2. Login Flow

### Test Cases:
- Submit login form with valid credentials.
- Submit login form with invalid credentials.
- Submit login form with missing email or password.
- Submit login form with invalid CSRF token.
- Attempt multiple failed logins to trigger rate limiting.
- Verify session ID is regenerated on successful login.
- Verify user is redirected based on user type (admin or regular).
- Verify error messages for invalid login or rate limiting.

### Security Checks:
- CSRF token validation on login form submission.
- Rate limiting on failed login attempts.
- Session fixation prevention by regenerating session ID.
- Password verification using password_verify.
- No SQL injection vulnerabilities (prepared statements used).

---

## 3. Session Management and Logout

### Test Cases:
- Verify session is started on login.
- Verify session variables (user_id, email, user_type) are set on login.
- Verify session is destroyed on logout.
- Verify user is redirected to login page after logout.
- Verify flash message is shown on successful logout.

### Security Checks:
- Session destruction on logout.
- No session data leakage after logout.

---

## 4. Book Management (Add Book)

### Test Cases:
- Submit add book form with all valid inputs including PDF upload.
- Submit add book form with missing required fields.
- Submit add book form with invalid file type upload.
- Submit add book form without file upload.
- Verify book is added to database with correct data.
- Verify success and error flash messages.
- Verify file is uploaded to correct directory with sanitized filename.

### Security Checks:
- Input sanitization on all form fields.
- File upload validation for allowed extensions and MIME types.
- Check for CSRF protection (currently missing, recommend adding).
- No SQL injection vulnerabilities in BookOperations class.

---

## 5. Book Browsing and Searching

### Test Cases:
- Search books by name and category.
- Filter books by category.
- Navigate through pagination.
- Clear filters and verify full book list is shown.
- Verify correct books are displayed based on filters.

### Security Checks:
- No state-changing actions, so CSRF not applicable.
- Input sanitization on search and filter inputs.

---

## 6. Admin Operations and User Management

### Test Cases:
- Admin registration with valid inputs.
- Admin registration with missing fields or mismatched passwords.
- Admin registration with already registered email.
- Search users by email or name.
- Mark entry fee as paid for a user.
- View user book list.
- Verify user list displays correct data.

### Security Checks:
- **Important:** Admin registration and user management use raw SQL queries without prepared statements (risk of SQL injection).
- Passwords in admin registration are stored in plaintext (security risk).
- No CSRF protection on admin forms and actions.
- Recommend refactoring admin registration and user management to use prepared statements, password hashing, and CSRF tokens.

---

## 7. General Security Aspects

- Verify all forms that change state have CSRF tokens.
- Verify session fixation prevention on login.
- Verify prepared statements are used for all database queries.
- Verify password hashing and verification.
- Verify rate limiting on login attempts.
- Verify secure file upload handling.

---

# Summary

This testing plan covers functional and security testing of all critical areas in the LMS application. It highlights current security strengths and weaknesses, especially in admin operations.

Please follow these test cases and report any issues or security concerns found.
