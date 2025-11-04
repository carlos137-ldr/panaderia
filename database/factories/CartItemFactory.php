<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cart;
use App\Models\Product;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cart_id' => \App\Models\Cart::all()->random()->id,
            'product_id' =>  \App\Models\Product::all()->random()->id,
            'cantidad' => $this->faker->numberBetween(1, 5),
        ];
    }
}
