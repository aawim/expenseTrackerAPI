<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Expense;
use App\Models\Category;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // System categories
        $systemCategories = [
            ['name' => 'Food', 'description' => 'Groceries and dining out', 'is_system_category' => true],
            ['name' => 'Transportation', 'description' => 'Public transport, fuel, etc.', 'is_system_category' => true],
            ['name' => 'Housing', 'description' => 'Rent, mortgage, utilities', 'is_system_category' => true],
            ['name' => 'Entertainment', 'description' => 'Movies, games, etc.', 'is_system_category' => true],
            ['name' => 'Healthcare', 'description' => 'Medical expenses', 'is_system_category' => true],
        ];

        foreach ($systemCategories as $category) {
            Category::create($category);
        }

        // System payment methods
        $systemPaymentMethods = [
            ['name' => 'Cash', 'is_system_method' => true],
            ['name' => 'Credit Card', 'is_system_method' => true],
            ['name' => 'Debit Card', 'is_system_method' => true],
            ['name' => 'Bank Transfer', 'is_system_method' => true],
            ['name' => 'Digital Wallet', 'is_system_method' => true],
        ];

        foreach ($systemPaymentMethods as $method) {
            PaymentMethod::create($method);
        }

        $user = User::where('email','admin@mail.com')->first();
        // Create some expenses for the test user
        Expense::factory()->count(5)->create([
            'user_id'           => $user->id,
            'category_id'       => Category::inRandomOrder()->first()->id,
            'payment_method_id' => PaymentMethod::inRandomOrder()->first()->id,
        ]);
    }
}
