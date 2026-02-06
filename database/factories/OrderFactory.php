<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'processing', 'completed', 'cancelled']);
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(fake()->unique()->bothify('??####')),
            'total_amount' => fake()->randomFloat(2, 20, 200),
            'status' => $status,
            'delivery_address' => fake()->address(),
            'contact_phone' => fake()->phoneNumber(),
            'payment_status' => $status === 'cancelled' ? 'failed' : fake()->randomElement(['pending', 'paid']),
            'payment_method' => fake()->randomElement(['credit_card', 'debit_card', 'paypal']),
            'estimated_delivery_time' => fake()->dateTimeBetween('now', '+2 hours'),
        ];
    }
}
