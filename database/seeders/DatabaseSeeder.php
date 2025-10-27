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
        
        Roles::factory()->createMany([
            ['nombre' => 'admin'],
            ['nombre' => 'cliente'],
            ['nombre' => 'empleado'],
        ]);
        User::factory()->create([
            'nombre' => 'Juan Carlos Garfias Vilchis',
            'email' => 'juan.garfias1234567890@gmail.com',
            'password' => bcrypt('admin12345'),
            'rol_id' => 1, // Asignar rol de Administrador
        ]);
        User::factory(15)->create();
        Branch::factory(3)->create();
        Product::factory(50)->create();
        Cart::factory(10)->create()->each(function ($cart) {
            // Para cada carrito, crear entre 1 y 5 items de carrito
            $itemsCount = rand(1, 5);
            for ($i = 0; $i < $itemsCount; $i++) {
                CartItem::factory()->create([
                    'cart_id' => $cart->id,
                ]);
            }
        });
        CartItem::factory(50)->create();
        Order::factory(20)->create();
        OrderItem::factory(50)->create();


    }
}