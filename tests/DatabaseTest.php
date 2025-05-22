<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/Database.php';

class DatabaseTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $this->db = Database::getInstance();
    }

    public function testSingletonInstance()
    {
        $db2 = Database::getInstance();
        $this->assertSame($this->db, $db2, "Database::getInstance should return the same instance");
    }

    public function testConnection()
    {
        $conn = $this->db->getConnection();
        $this->assertInstanceOf(mysqli::class, $conn, "getConnection should return a mysqli instance");
        $this->assertNull($conn->connect_error, "Database connection should not have errors");
    }

    public function testQueryExecution()
    {
        $result = $this->db->query("SELECT 1");
        $this->assertNotFalse($result, "Query should execute successfully");
    }
}
