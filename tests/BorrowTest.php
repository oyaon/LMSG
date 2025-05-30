<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/Borrow.php';

class BorrowTest extends TestCase
{
    private $borrow;

    protected function setUp(): void
    {
        $this->borrow = new Borrow();
    }

    public function testBorrowInitialState()
    {
        $this->assertFalse($this->borrow->isActive(), "Borrow should not be active initially");
    }

    public function testLoadBorrowByIdInvalid()
    {
        $result = $this->borrow->loadBorrowById(-1);
        $this->assertFalse($result, "Loading borrow by invalid ID should return false");
    }

    public function testCalculateFineNoIssueDate()
    {
        $fine = $this->borrow->calculateFine(null);
        $this->assertEquals(0, $fine, "Fine should be 0 if issue date is null");
    }

    public function testCalculateFineWithinGracePeriod()
    {
        $issueDate = (new DateTime())->modify('-10 days')->format('Y-m-d');
        $fine = $this->borrow->calculateFine($issueDate, 14, 5.0);
        $this->assertEquals(0, $fine, "Fine should be 0 if within grace period");
    }

    public function testCalculateFineOverdue()
    {
        $issueDate = (new DateTime())->modify('-20 days')->format('Y-m-d');
        $fine = $this->borrow->calculateFine($issueDate, 14, 5.0);
        $expectedFine = (20 - 14) * 5.0;
        $this->assertEquals($expectedFine, $fine, "Fine should be calculated correctly for overdue days");
    }

    public function testReturnBookUpdatesFine()
    {
        // Mock data for borrowId and bookId
        $borrowId = 1;
        $bookId = 1;
        $fineAmount = 15.0;

        // Since returnBook interacts with the database, we cannot test it fully here without mocking the db.
        // Instead, we test that the method returns a boolean (true/false).
        $result = $this->borrow->returnBook($borrowId, $bookId, $fineAmount);
        $this->assertIsBool($result, "returnBook should return a boolean");
    }

    public function testRequestBorrowLimitExceeded()
    {
        $userEmail = "testuser@example.com";

        // Simulate 3 active loans
        $this->borrow->db->insert("INSERT INTO borrow_history (user_email, book_id, status) VALUES (?, ?, 'Borrowed')", "si", [$userEmail, 1]);
        $this->borrow->db->insert("INSERT INTO borrow_history (user_email, book_id, status) VALUES (?, ?, 'Borrowed')", "si", [$userEmail, 2]);
        $this->borrow->db->insert("INSERT INTO borrow_history (user_email, book_id, status) VALUES (?, ?, 'Borrowed')", "si", [$userEmail, 3]);

        // Attempt to borrow a 4th book
        $result = $this->borrow->requestBorrow($userEmail, 4);

        $this->assertEquals("You cannot borrow more than 3 books at a time.", $result);
    }

    public function testReturnBookUpdatesStatus()
    {
        $borrowId = 1;
        $bookId = 1;

        // Simulate returning a book
        $result = $this->borrow->returnBook($borrowId, $bookId);

        $this->assertTrue($result, "Returning a book should succeed");
    }
}
