<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'sku' => 'SNK-' . strtoupper($this->faker->unique()->bothify('??-###')),
            'name' => ucwords($this->faker->words(3, true)),
            'price' => $this->faker->randomElement([12000, 15000, 18000, 21000, 24000, 28000, 35000]),
            'stock' => $this->faker->numberBetween(2, 50),
            'minimum_stock' => 5,
        ];
    }
}
