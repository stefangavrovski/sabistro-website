<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraphs(2, true),
            'price' => fake()->randomFloat(2, 5, 50),
            'category_id' => Category::factory(),
            'image_path' => 'products/' . fake()->uuid() . '.jpg',
            'is_available' => fake()->boolean(90),
            'preparation_time' => fake()->numberBetween(10, 60),
            'is_featured' => fake()->boolean(20),
        ];
    }
}
