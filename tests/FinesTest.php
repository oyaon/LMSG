<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/EntryAndFines.php';

class FinesTest extends TestCase
{
    private $fines;

    protected function setUp(): void
    {
        $this->fines = new EntryAndFines();
    }

    public function testFinesInitialState()
    {
        $this->assertFalse($this->fines->hasOutstandingFines(), "User should not have outstanding fines initially");
    }

    public function testCalculateFineInvalidUser()
    {
        $result = $this->fines->calculateFine(-1);
        $this->assertFalse($result, "Calculating fine for invalid user should return false");
    }
}
