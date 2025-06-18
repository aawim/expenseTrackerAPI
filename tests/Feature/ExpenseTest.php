<?php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $paymentMethod;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user          = User::factory()->create();
        $this->category      = Category::factory()->create(['user_id' => $this->user->id]);
        $this->paymentMethod = PaymentMethod::factory()->create(['user_id' => $this->user->id]);

        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_returns_a_paginated_list_of_expenses()
    {
        Expense::factory()->count(15)->create([
            'user_id'           => $this->user->id,
            'category_id'       => $this->category->id,
            'payment_method_id' => $this->paymentMethod->id,
        ]);

        $response = $this->getJson('/api/expenses');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    #[Test]
    public function it_can_store_an_expense()
    {
        $payload = [
            'amount'            => 49.99,
            'description'       => 'Test expense',
            'date'              => now()->toDateString(),
            'category_id'       => $this->category->id,
            'payment_method_id' => $this->paymentMethod->id,
        ];

        $response = $this->postJson('/api/expenses', $payload);

        $response->assertCreated()
            ->assertJsonStructure(['data' =>
                ['id', 'amount', 'description', 'date', 'category', 'payment_method']]);

        $this->assertDatabaseHas('expenses', [
            'amount'      => 49.99,
            'description' => 'Test expense',
            'user_id'     => $this->user->id,
        ]);
    }

    #[Test]
    public function it_can_show_a_single_expense()
    {
        $expense = Expense::factory()->create([
            'user_id'           => $this->user->id,
            'category_id'       => $this->category->id,
            'payment_method_id' => $this->paymentMethod->id,
        ]);

        $response = $this->getJson("/api/expenses/{$expense->id}");

        $response->assertOk()
            ->assertJsonStructure(['data' =>
                ['id', 'amount', 'description', 'date', 'category', 'payment_method']]);
    }

    #[Test]
    public function it_can_update_an_expense(): void
    {
        $expense = Expense::factory()->create([
            'user_id'           => $this->user->id,
            'category_id'       => $this->category->id,
            'payment_method_id' => $this->paymentMethod->id,
        ]);

        $payload = [
            'amount'            => 199.99,
            'description'       => 'Updated expense description',
            'date'              => now()->toDateString(),
            'category_id'       => $this->category->id,
            'payment_method_id' => $this->paymentMethod->id,
            'location'          => 'Remote',
            'is_recurring'      => false,
        ];

        $response = $this->putJson("/api/expenses/{$expense->id}", $payload);
        $response->assertOk();

        $this->assertDatabaseHas('expenses', [
            'id'          => $expense->id,
            'amount'      => 199.99,
            'description' => 'Updated expense description',
        ]);
    }

    #[Test]
    public function it_can_delete_an_expense()
    {
        $expense = Expense::factory()->create([
            'user_id'           => $this->user->id,
            'category_id'       => $this->category->id,
            'payment_method_id' => $this->paymentMethod->id,
        ]);

        $response = $this->deleteJson("/api/expenses/{$expense->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

}
