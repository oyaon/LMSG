<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/User.php';

class UserTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testUserNotLoggedInInitially()
    {
        $this->assertFalse($this->user->isLoggedIn(), "User should not be logged in initially");
    }

    public function testLoadUserByIdInvalid()
    {
        $result = $this->user->loadUserById(-1);
        $this->assertFalse($result, "Loading user by invalid ID should return false");
    }

    public function testLoadUserByEmailInvalid()
    {
        $result = $this->user->loadUserByEmail('nonexistent@example.com');
        $this->assertFalse($result, "Loading user by invalid email should return false");
    }
}
