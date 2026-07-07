<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku'        => fake()->unique()->regexify('[A-Z]{3}-[0-9]{6}'),
            'name'       => fake()->randomElement(['XS', 'S', 'M', 'L', 'XL'])
                . ' / ' . fake()->safeColorName(),
            'price'      => fake()->numberBetween(500, 50000),
            'stock'      => fake()->numberBetween(0, 200),
            'is_active'  => true,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn() => ['stock' => 0]);
    }
}
