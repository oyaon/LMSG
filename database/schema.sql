-- LMS Database Schema
-- Drop existing tables if they exist
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS borrow_history;
DROP TABLE IF EXISTS all_books;
DROP TABLE IF EXISTS authors;
DROP TABLE IF EXISTS users;

-- Create users table with improved security
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    user_name VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Will store hashed passwords
    user_type TINYINT NOT NULL DEFAULT 1, -- 0: Admin, 1: Regular User
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create authors table
CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    biography TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create books table with improved structure
CREATE TABLE all_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    author_id INT,
    author VARCHAR(100) NOT NULL, -- Keeping for backward compatibility
    category VARCHAR(50) NOT NULL,
    description TEXT,
    quantity INT NOT NULL DEFAULT 0,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    pdf VARCHAR(255),
    cover_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE SET NULL
);

-- Create borrow history table
CREATE TABLE borrow_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100) NOT NULL,
    book_id INT NOT NULL,
    issue_date DATE,
    fine DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    status ENUM('Requested', 'Issued', 'Returned', 'Declined') NOT NULL DEFAULT 'Requested',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    return_date DATE,
    FOREIGN KEY (book_id) REFERENCES all_books(id) ON DELETE CASCADE
);

-- Create cart table
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100) NOT NULL,
    book_id INT NOT NULL,
    date DATE NOT NULL,
    status TINYINT NOT NULL DEFAULT 0, -- 0: In cart, 1: Purchased
    FOREIGN KEY (book_id) REFERENCES all_books(id) ON DELETE CASCADE
);

-- Create payments table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100) NOT NULL,
    book_ids VARCHAR(255) NOT NULL, -- Comma-separated list of book IDs
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    transaction_id VARCHAR(100),
    payment_status ENUM('Pending', 'Completed', 'Failed') NOT NULL DEFAULT 'Completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add indexes for performance
CREATE INDEX idx_book_name ON all_books(name);
CREATE INDEX idx_book_author ON all_books(author);
CREATE INDEX idx_book_category ON all_books(category);
CREATE INDEX idx_borrow_status ON borrow_history(status);
CREATE INDEX idx_cart_user ON cart(user_email);
CREATE INDEX idx_payment_user ON payments(user_email);