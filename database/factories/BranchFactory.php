<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => 'Sucursal ' . $this->faker->city(),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
        ];
    }
}
