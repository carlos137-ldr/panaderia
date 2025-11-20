<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Branch;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    }

    /** @test */
    public function index_order_items()
    {
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));
        
        // Crear datos previos necesarios para que los factories no fallen
        User::factory()->create();
        Branch::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create();
        OrderItem::factory(3)->create([
            'order_id' => $order->id,
            'product_id' => $product->id
        ]);

        $response = $this->getJson('/api/orderitems');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function store_order_item()
    {
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));
        
        User::factory()->create();
        Branch::factory()->create();
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $data = [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'cantidad' => 2,
            'precio_unitario' => 20.50
        ];

        $response = $this->postJson('/api/orderitems', $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'cantidad' => 2,
            'precio_unitario' => 20.50
        ]);
    }

    /** @test */
    public function update_order_item()
    {
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));
        
        User::factory()->create();
        Branch::factory()->create();
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id
        ]);

        $data = [
            'cantidad' => 5,
            'precio_unitario' => 25.00
        ];

        $response = $this->putJson("/api/orderitems/{$orderItem->id}", $data);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseHas('order_items', [
            'id' => $orderItem->id,
            'cantidad' => 5,
            'precio_unitario' => 25.00
        ]);
    }

    /** @test */
    public function destroy_order_item()
    {
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));
        
        User::factory()->create();
        Branch::factory()->create();
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id
        ]);

        $response = $this->deleteJson("/api/orderitems/{$orderItem->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('order_items', ['id' => $orderItem->id]);
    }
}