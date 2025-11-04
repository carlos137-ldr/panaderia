<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $producto = Product::inRandomOrder()->first();
        return [
            'order_id' => \App\Models\Order::all()->random()->id,
            'product_id' => $producto?->id,
            'cantidad' => $this->faker->numberBetween(1, 4),
            'precio_unitario' => $producto?->precio ?? 20.00,
        ];
    }
}
