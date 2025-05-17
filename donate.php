<?php
session_start();
require_once __DIR__ . '/includes/Database.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Fetch all books for book donation selection
$books = $db->fetchAll("SELECT id, name FROM all_books ORDER BY name ASC");

// Handle flash messages
require_once __DIR__ . '/includes/Helper.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Donate - Gobindaganj Public Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
</head>
<body>
<?php include("header.php"); ?>

<div class="container mt-5 pt-5">
    <h1 class="text-center mb-4">Support Gobindaganj Public Library</h1>
    <p class="text-center mb-4">Choose how you would like to contribute: Donate Money or Donate Books.</p>

    <?php Helper::displayFlashMessage(); ?>

    <ul class="nav nav-tabs justify-content-center" id="donationTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="money-tab" data-bs-toggle="tab" data-bs-target="#money" type="button" role="tab" aria-controls="money" aria-selected="true">Donate Money</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="books-tab" data-bs-toggle="tab" data-bs-target="#books" type="button" role="tab" aria-controls="books" aria-selected="false">Donate Books</button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="donationTabContent">
        <!-- Donate Money Tab -->
        <div class="tab-pane fade show active" id="money" role="tabpanel" aria-labelledby="money-tab">
            <form action="donate_money.php" method="POST" class="mx-auto" style="max-width: 400px;">
                <div class="mb-3">
                    <label for="donor_name_money" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="donor_name_money" name="donor_name" required>
                </div>
                <div class="mb-3">
                    <label for="donor_email_money" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="donor_email_money" name="donor_email" required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Donation Amount (Tk)</label>
                    <input type="number" class="form-control" id="amount" name="amount" min="1" required>
                </div>
                <p>Please send your donation amount via Bkash, Nagad, or Rocket to 01830567270 and enter the transaction ID below.</p>
                <div class="mb-3">
                    <label for="trans_id" class="form-label">Transaction ID</label>
                    <input type="text" class="form-control" id="trans_id" name="trans_id" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Submit Donation</button>
            </form>
        </div>

        <!-- Donate Books Tab -->
        <div class="tab-pane fade" id="books" role="tabpanel" aria-labelledby="books-tab">
            <form action="donate_books.php" method="POST" class="mx-auto" style="max-width: 600px;">
                <div class="mb-3">
                    <label for="donor_name_books" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="donor_name_books" name="donor_name" required>
                </div>
                <div class="mb-3">
                    <label for="donor_email_books" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="donor_email_books" name="donor_email" required>
                </div>
                <div id="book-donation-list">
                    <div class="row mb-3 align-items-center">
                        <div class="col-8">
                            <label for="book_id_1" class="form-label">Select Book</label>
                            <select class="form-select" id="book_id_1" name="book_ids[]" required>
                                <option value="" disabled selected>Select a book</option>
                                <?php foreach ($books as $book): ?>
                                    <option value="<?php echo htmlspecialchars($book['id']); ?>"><?php echo htmlspecialchars($book['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="quantity_1" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity_1" name="quantities[]" min="1" value="1" required>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mb-3" id="add-book-btn">Add Another Book</button>
                <button type="submit" class="btn btn-primary w-100">Submit Book Donation</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add dynamic book donation fields
    let bookCount = 1;
    document.getElementById('add-book-btn').addEventListener('click', function() {
        bookCount++;
        const bookDonationList = document.getElementById('book-donation-list');
        const newRow = document.createElement('div');
        newRow.className = 'row mb-3 align-items-center';
        newRow.innerHTML = `
            <div class="col-8">
                <select class="form-select" name="book_ids[]" required>
                    <option value="" disabled selected>Select a book</option>
                    <?php foreach ($books as $book): ?>
                        <option value="<?php echo htmlspecialchars($book['id']); ?>"><?php echo htmlspecialchars($book['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4">
                <input type="number" class="form-control" name="quantities[]" min="1" value="1" required>
            </div>
        `;
        bookDonationList.appendChild(newRow);
    });
</script>

<?php include("footer.php"); ?>
</body>
</html>
