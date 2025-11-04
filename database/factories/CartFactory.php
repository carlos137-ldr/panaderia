<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class CartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::all()->random()->id,
        ];
    }
}

