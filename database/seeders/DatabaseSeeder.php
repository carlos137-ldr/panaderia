<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nombre' => 'Juan Carlos Garfias Vilchis',
            'email' => 'juan.garfias1234567890@gmail.com',
            'password' => bcrypt('admin12345'),
            'rol_id' => 1, // Asignar rol de Administrador
        ]);
    }
}
