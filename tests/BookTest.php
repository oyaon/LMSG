<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/Book.php';

class BookTest extends TestCase
{
    private $book;

    protected function setUp(): void
    {
        $this->book = new Book();
    }

    public function testBookInitialState()
    {
        $this->assertFalse($this->book->isAvailable(), "Book should not be available initially");
    }

    public function testLoadBookByIdInvalid()
    {
        $result = $this->book->loadBookById(-1);
        $this->assertFalse($result, "Loading book by invalid ID should return false");
    }
}
