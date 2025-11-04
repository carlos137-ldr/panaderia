<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Branch;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' =>  \App\Models\User::all()->random()->id,
            'branch_id' =>  \App\Models\Branch::all()->random()->id,
            'fecha_pedido' => now(),
            'fecha_recogida' => now()->addDays($this->faker->numberBetween(1, 3)),
            'estado' => $this->faker->randomElement(['pendiente', 'preparando', 'listo']),
            'total' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
