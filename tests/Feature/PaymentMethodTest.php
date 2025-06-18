<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\PaymentMethod;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_returns_all_payment_methods_ordered_by_name()
    {
        // Arrange
        PaymentMethod::factory()->create(['name' => 'Cash', 'user_id' => $this->user->id]);
        PaymentMethod::factory()->create(['name' => 'Bank Transfer', 'user_id' => $this->user->id]);

        // Act
        $response = $this->getJson('/api/payment-methods');

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         ['id', 'name']
                     ]
                 ]);

        $names = collect($response->json('data'))->pluck('name')->toArray();
        $this->assertEquals(['Bank Transfer', 'Cash'], $names); // Ordered by name ASC
    }
}
