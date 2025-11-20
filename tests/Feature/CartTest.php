<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Cart;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_carts()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        Cart::factory(3)->create();

        $response = $this->getJson('/api/carts');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonCount(3, 'data');
    }

    public function test_store_cart()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $clientUser = User::factory()->create();

        $data = [
            'user_id' => $clientUser->id,
        ];

        $response = $this->postJson('/api/carts', $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('carts', [
            'user_id' => $clientUser->id
        ]);
    }

    public function test_update_cart()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $cart = Cart::factory()->create();
        $newUser = User::factory()->create();

        $data = [
            'user_id' => $newUser->id,
        ];

        $response = $this->putJson("/api/carts/{$cart->id}", $data);

        $response->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'user_id' => $newUser->id
        ]);
    }

    public function test_destroy_cart()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $cart = Cart::factory()->create();

        $response = $this->deleteJson("/api/carts/{$cart->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }
}