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
            'user_id' => User::inRandomOrder()->first()?->id,
            'branch_id' => Branch::inRandomOrder()->first()?->id,
            'fecha_pedido' => now(),
            'fecha_recogida' => now()->addDays($this->faker->numberBetween(1, 3)),
            'estado' => $this->faker->randomElement(['pendiente', 'preparando', 'listo']),
            'total' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
