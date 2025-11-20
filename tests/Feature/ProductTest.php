<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_products()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));

        Product::factory(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonCount(3, 'data');
    }

    public function test_show_product()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));

        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'tipo',
                         'atributos' => [
                             'nombre del producto',
                             'precio del producto',
                             'stock'
                         ]
                     ]
                 ]);
    }

    public function test_store_product()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $data = [
            'nombre' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
            'precio' => 50.00,
            'stock' => 100,
            'imagen' => UploadedFile::fake()->image('producto.jpg'),
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('products', [
            'nombre' => $data['nombre'],
            'precio' => 50.00
        ]);
    }

    public function test_update_product()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $product = Product::factory()->create();

        $data = [
            'nombre' => 'Pan Integral Editado',
            'precio' => 25.50
        ];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'nombre' => 'Pan Integral Editado',
            'precio' => 25.50
        ]);
    }

    public function test_destroy_product()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
