<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use App\Models\Order;
use Laravel\Sanctum\Sanctum;
use Carbon\Carbon;
use Database\Seeders\RolSeeder;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_index_orders()
    {
        $this->seed(RolSeeder::class);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        User::factory(5)->create(); 
        Branch::factory(3)->create();
        Order::factory(3)->create();

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_store_order()
    {
        $this->seed(RolSeeder::class);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $user = User::factory()->create();
        $branch = Branch::factory()->create();

        $data = [
            'user_id' => $user->id,
            'branch_id' => $branch->id,
            'fecha_pedido' => Carbon::now()->toDateTimeString(),
            'fecha_recogida' => Carbon::now()->addDay()->toDateTimeString(),
            'estado' => 'pendiente',
            'total' => 150.00
        ];

        $response = $this->postJson('/api/orders', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 150.00
        ]);
    }

    public function test_update_order()
    {
        $this->seed(RolSeeder::class);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $user = User::factory()->create();
        $branch = Branch::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'branch_id' => $branch->id]);

        $data = [
            'estado' => 'entregado',
            'total' => 200.00
        ];

        $response = $this->putJson("/api/orders/{$order->id}", $data);

        $response->assertStatus(202);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'estado' => 'entregado',
            'total' => 200.00
        ]);
    }

    public function test_destroy_order()
    {
        $this->seed(RolSeeder::class);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $user = User::factory()->create();
        $branch = Branch::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'branch_id' => $branch->id]);

        $response = $this->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}