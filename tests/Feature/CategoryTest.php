<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
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
    public function it_returns_all_categories_for_authenticated_user()
    {
        // Arrange
        Category::factory()->create(['name' => 'Zebra', 'user_id' => $this->user->id]);
        Category::factory()->create(['name' => 'Apple', 'user_id' => $this->user->id]);

        // Act
        $response = $this->getJson('/api/categories');

        // Assert
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         ['id', 'name']
                     ]
                 ]);

        $names = collect($response->json('data'))->pluck('name')->toArray();
        $this->assertEquals(['Apple', 'Zebra'], $names); // alphabetically ordered
    }
}
