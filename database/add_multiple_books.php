<?php
/**
 * Script to programmatically add multiple books to the database
 */

require_once(__DIR__ . '/../admin/db-connect.php');

$books = [
    [
        'name' => 'The Great Gatsby',
        'author' => 'F. Scott Fitzgerald',
        'category' => 'Classic Literature',
        'description' => 'A novel set in the Roaring Twenties.',
        'quantity' => 10,
        'price' => 15.99,
        'pdf' => '',
        'image' => 'uploads/images/gatsby.jpg'
    ],
    [
        'name' => 'To Kill a Mockingbird',
        'author' => 'Harper Lee',
        'category' => 'Classic Literature',
        'description' => 'A novel about racial injustice in the Deep South.',
        'quantity' => 8,
        'price' => 12.99,
        'pdf' => '',
        'image' => 'uploads/images/mockingbird.jpg'
    ],
    [
        'name' => '1984',
        'author' => 'George Orwell',
        'category' => 'Dystopian',
        'description' => 'A novel about a totalitarian regime.',
        'quantity' => 15,
        'price' => 14.99,
        'pdf' => '',
        'image' => 'uploads/images/1984.jpg'
    ],
    [
        'name' => 'Clean Code',
        'author' => 'Robert C. Martin',
        'category' => 'Programming',
        'description' => 'A handbook of agile software craftsmanship.',
        'quantity' => 5,
        'price' => 39.99,
        'pdf' => '',
        'image' => 'uploads/images/clean_code.jpg'
    ],
    [
        'name' => 'The Pragmatic Programmer',
        'author' => 'Andrew Hunt and David Thomas',
        'category' => 'Programming',
        'description' => 'Your journey to mastery.',
        'quantity' => 7,
        'price' => 42.50,
        'pdf' => '',
        'image' => 'uploads/images/pragmatic_programmer.jpg'
    ]
];

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("INSERT INTO all_books (name, author, category, description, quantity, price, pdf, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($books as $book) {
        $stmt->bind_param(
            "ssssidss",
            $book['name'],
            $book['author'],
            $book['category'],
            $book['description'],
            $book['quantity'],
            $book['price'],
            $book['pdf'],
            $book['image']
        );
        $stmt->execute();
    }

    $conn->commit();
    echo "Successfully added " . count($books) . " books to the database.\n";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error adding books: " . $e->getMessage() . "\n";
}
?>
