<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyRefactorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'firstname' => 'Test',
            'lastname' => 'User',
            'username' => 'testuser' . time(),
            'email' => 'testuser' . time() . '@example.com',
            'password' => 'TestPass123',
            'password_confirmation' => 'TestPass123',
        ]);

        $response->assertStatus(302); // Redirect after registration
        $this->assertDatabaseHas('users', [
            'username' => 'testuser' . time(),
        ]);
    }

    /**
     * Test adding item to cart.
     *
     * @return void
     */
    public function test_user_can_add_to_cart()
    {
        // Simulate a logged-in user
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/add_cart?t=1&q=2');

        $response->assertStatus(200);
        // Additional assertions can be added based on cart implementation
    }

    /**
     * Test payment processing.
     *
     * @return void
     */
    public function test_user_can_process_payment()
    {
        // Simulate a logged-in user
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->post('/payment', [
            'submit' => '1',
            'ids' => '1,2,',
            'amount' => 100,
            'trans_id' => 'TX123456',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Payment Successful');
    }
}
