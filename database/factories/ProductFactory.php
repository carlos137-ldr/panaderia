<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->randomElement(['Pan blanco', 'Concha', 'Cuernito', 'Empanada', 'Baguette']),
            'descripcion' => $this->faker->sentence(),
            'precio' => $this->faker->randomFloat(2, 5, 50),
            'stock' => $this->faker->numberBetween(10, 200),
            'imagen' => 'default.jpg',
        ];
    }
}
