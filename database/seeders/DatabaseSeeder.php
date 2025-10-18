<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
 
use App\Models\Roles;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Branch;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles
        
        Roles::factory()->create([
            'nombre' => 'admin',
           
        ]);
        User::factory()->create([
            'nombre' => 'Juan Carlos Garfias Vilchis',
            'email' => 'juan.garfias1234567890@gmail.com',
            'password' => bcrypt('admin12345'),
            'rol_id' => 1, // Asignar rol de Administrador
        ]);
    }
}