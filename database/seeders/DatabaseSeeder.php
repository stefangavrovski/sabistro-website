<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $users = User::factory(10)->create();

        $categories = Category::factory(5)->create();

        $categories->each(function ($category) {
            Product::factory(rand(5, 10))->create([
                'category_id' => $category->id,
            ]);
        });

        $products = Product::all();

        $users->each(function ($user) use ($products) {
            Order::factory(rand(1, 3))->create([
                'user_id' => $user->id,
            ])->each(function ($order) use ($products) {
                $orderProducts = $products->random(rand(1, 5));
                
                $total_amount = 0;
                foreach ($orderProducts as $product) {
                    $quantity = rand(1, 3);
                    $unit_price = $product->price;
                    $subtotal = $quantity * $unit_price;
                    $total_amount += $subtotal;

                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unit_price,
                        'subtotal' => $subtotal,
                    ]);
                }

                $order->update(['total_amount' => $total_amount]);
            });
        });

        $users->each(function ($user) use ($products) {
            $productsToReview = $products->random(rand(0, 3));
            foreach ($productsToReview as $product) {
                Review::factory()->create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
            }
        });
    }
}
