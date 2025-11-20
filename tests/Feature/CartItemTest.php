<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_cart_items()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        CartItem::factory(3)->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);

        $response = $this->getJson('/api/cartitems');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonCount(3, 'data');
    }

    public function test_store_cart_item()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $data = [
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
            'cantidad'   => 5,
        ];

        $response = $this->postJson('/api/cartitems', $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('cart_items', [
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
            'cantidad'   => 5,
        ]);
    }

    public function test_update_cart_item()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);

        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $cartItem = CartItem::factory()->create([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
        ]);

        $data = [
            'cantidad' => 10,
        ];

        $response = $this->putJson("/api/cartitems/{$cartItem->id}", $data);

        $response->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertDatabaseHas('cart_items', [
            'id'       => $cartItem->id,
            'cantidad' => 10,
        ]);
    }

    public function test_destroy_cart_item()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $cartItem = CartItem::factory()->create([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
        ]);

        $response = $this->deleteJson("/api/cartitems/{$cartItem->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }
}
