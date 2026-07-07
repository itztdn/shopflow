<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['T-Shirts', 'Hoodies', 'Sneakers', 'Accessories'];

        foreach ($categories as $name) {
            $category = Category::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);

            Product::factory()
                ->count(5)
                ->for($category)
                ->has(ProductVariant::factory()->count(3), 'variants')
                ->create();
        }
    }
}
