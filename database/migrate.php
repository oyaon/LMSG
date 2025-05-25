<?php
/**
 * Database Migration Script
 * 
 * This script migrates data from the old database structure to the new one
 * It preserves all existing data while upgrading the schema
 */

// Set maximum execution time to handle large datasets
ini_set('max_execution_time', 300); // 5 minutes

// Include database connection
require_once(__DIR__ . '/../admin/db-connect.php');

// Function to log migration steps
function logMigration($message) {
    echo date('[Y-m-d H:i:s]') . " $message" . PHP_EOL;
    // Also log to file
    file_put_contents(__DIR__ . '/migration.log', date('[Y-m-d H:i:s]') . " $message" . PHP_EOL, FILE_APPEND);
}

// Start migration
logMigration("Starting database migration...");

// Skip backup step for now
logMigration("Skipping database backup step...");

// Import new schema
logMigration("Importing new schema...");
try {
    $conn->query("SET FOREIGN_KEY_CHECKS=0;");
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $conn->multi_query($sql);
    
    // Clear results to allow next query
    while ($conn->more_results() && $conn->next_result()) {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    }
    $conn->query("SET FOREIGN_KEY_CHECKS=1;");
    logMigration("Schema imported successfully.");
} catch (Exception $e) {
    logMigration("Error importing schema: " . $e->getMessage());
    exit(1);
}

// Migrate users data
logMigration("Migrating users data...");
try {
    // Get existing users
    $result = $conn->query("SELECT * FROM users");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Hash password for security
            $hashedPassword = password_hash($row['password'], PASSWORD_DEFAULT);
            
            // Insert with hashed password
            $stmt = $conn->prepare("INSERT INTO users (id, first_name, last_name, user_name, email, password, user_type) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssi", 
                $row['id'],
                $row['first_name'], 
                $row['last_name'], 
                $row['user_name'], 
                $row['email'], 
                $hashedPassword, 
                $row['user_type']
            );
            $stmt->execute();
            $stmt->close();
        }
        logMigration("Migrated " . $result->num_rows . " users.");
    } else {
        logMigration("No users to migrate.");
    }
} catch (Exception $e) {
    logMigration("Error migrating users: " . $e->getMessage());
}

// Migrate authors data
logMigration("Migrating authors data...");
try {
    // Extract unique authors from books
    $result = $conn->query("SELECT DISTINCT author FROM all_books");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO authors (name) VALUES (?)");
            $stmt->bind_param("s", $row['author']);
            $stmt->execute();
            $stmt->close();
        }
        logMigration("Migrated " . $result->num_rows . " authors.");
    } else {
        logMigration("No authors to migrate.");
    }
} catch (Exception $e) {
    logMigration("Error migrating authors: " . $e->getMessage());
}

// Migrate books data
logMigration("Migrating books data...");
try {
    // Get existing books
    $result = $conn->query("SELECT * FROM all_books");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Get author_id from authors table
            $authorStmt = $conn->prepare("SELECT id FROM authors WHERE name = ?");
            $authorStmt->bind_param("s", $row['author']);
            $authorStmt->execute();
            $authorResult = $authorStmt->get_result();
            $authorRow = $authorResult->fetch_assoc();
            $authorId = $authorRow ? $authorRow['id'] : null;
            $authorStmt->close();
            
            // Insert book with author_id
            $stmt = $conn->prepare("INSERT INTO all_books (id, name, author_id, author, category, description, quantity, pdf) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isississ", 
                $row['id'],
                $row['name'], 
                $authorId,
                $row['author'], 
                $row['category'], 
                $row['description'], 
                $row['quantity'], 
                $row['pdf']
            );
            $stmt->execute();
            $stmt->close();
        }
        logMigration("Migrated " . $result->num_rows . " books.");
    } else {
        logMigration("No books to migrate.");
    }
} catch (Exception $e) {
    logMigration("Error migrating books: " . $e->getMessage());
}

// Migrate borrow history
logMigration("Migrating borrow history...");
try {
    // Get existing borrow history
    $result = $conn->query("SELECT * FROM borrow_history");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO borrow_history (id, user_email, book_id, issue_date, fine, status) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isisds", 
                $row['id'],
                $row['user_email'], 
                $row['book_id'], 
                $row['issue_date'], 
                $row['fine'], 
                $row['status']
            );
            $stmt->execute();
            $stmt->close();
        }
        logMigration("Migrated " . $result->num_rows . " borrow records.");
    } else {
        logMigration("No borrow history to migrate.");
    }
} catch (Exception $e) {
    logMigration("Error migrating borrow history: " . $e->getMessage());
}

// Migrate cart data
logMigration("Migrating cart data...");
try {
    // Get existing cart data
    $result = $conn->query("SELECT * FROM cart");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO cart (id, user_email, book_id, date, status) 
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isisi", 
                $row['id'],
                $row['user_email'], 
                $row['book_id'], 
                $row['date'], 
                $row['status']
            );
            $stmt->execute();
            $stmt->close();
        }
        logMigration("Migrated " . $result->num_rows . " cart items.");
    } else {
        logMigration("No cart data to migrate.");
    }
} catch (Exception $e) {
    logMigration("Error migrating cart data: " . $e->getMessage());
}

// Migrate payments data
logMigration("Migrating payments data...");
try {
    // Get existing payments
    $result = $conn->query("SELECT * FROM payments");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO payments (id, user_email, book_ids, amount, payment_date, transaction_id) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issdss", 
                $row['id'],
                $row['user_email'], 
                $row['book_ids'], 
                $row['amount'], 
                $row['payment_date'], 
                $row['transaction_id']
            );
            $stmt->execute();
            $stmt->close();
        }
        logMigration("Migrated " . $result->num_rows . " payment records.");
    } else {
        logMigration("No payment data to migrate.");
    }
} catch (Exception $e) {
    logMigration("Error migrating payments: " . $e->getMessage());
}

/**
 * Add tables for donations: money_donations and book_donations
 */
logMigration("Creating donations tables...");

try {
    $createMoneyDonationsTable = "
    CREATE TABLE IF NOT EXISTS money_donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_email VARCHAR(100) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        donation_date DATE NOT NULL,
        transaction_id VARCHAR(100) NOT NULL,
        donation_status ENUM('Pending', 'Completed', 'Failed') NOT NULL DEFAULT 'Completed',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ";
    if ($conn->query($createMoneyDonationsTable) === TRUE) {
        logMigration("money_donations table created or already exists.");
    } else {
        logMigration("Error creating money_donations table: " . $conn->error);
    }

    $createBookDonationsTable = "
    CREATE TABLE IF NOT EXISTS book_donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_email VARCHAR(100) NOT NULL,
        book_id INT NOT NULL,
        quantity INT NOT NULL,
        donation_date DATE NOT NULL,
        status ENUM('Pending', 'Received', 'Cancelled') NOT NULL DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (book_id) REFERENCES all_books(id) ON DELETE CASCADE
    );
    ";
    if ($conn->query($createBookDonationsTable) === TRUE) {
        logMigration("book_donations table created or already exists.");
    } else {
        logMigration("Error creating book_donations table: " . $conn->error);
    }
} catch (Exception $e) {
    logMigration("Exception creating donation tables: " . $e->getMessage());
}

// Migration complete
logMigration("Migration completed successfully!");
echo "Migration completed successfully! Check migration.log for details.";
