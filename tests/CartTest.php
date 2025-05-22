<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/Cart.php';

class CartTest extends TestCase
{
    private $cart;

    protected function setUp(): void
    {
        $this->cart = new Cart();
    }

    public function testCartInitialState()
    {
        $this->assertEmpty($this->cart->getItems(), "Cart should be empty initially");
    }

    public function testAddInvalidItem()
    {
        $result = $this->cart->addItem(null);
        $this->assertFalse($result, "Adding invalid item should return false");
    }
}
