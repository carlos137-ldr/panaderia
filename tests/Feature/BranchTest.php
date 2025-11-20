<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Branch;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BranchTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_branches()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);

        Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));

        Branch::factory(3)->create();

        $response = $this->getJson('/api/branches');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'tipo',
                        'atributos' => [
                            'nombre',
                            'direccion',
                            'telefono',
                        ]
                    ]
                ]
            ]);
    }

    public function test_show_branch()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);
        Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));

        $branch = Branch::factory()->create();

        $response = $this->getJson("/api/branches/{$branch->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'tipo',
                    'atributos' => [
                        'nombre',
                        'direccion',
                        'telefono',
                    ],
                    'relaciones' => [
                        'orders'
                    ]
                ]
            ]);
    }

    public function test_store_branch()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);

        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $data = [
            'nombre' => $this->faker->company,
            'direccion' => $this->faker->address,
            'telefono' => substr($this->faker->phoneNumber, 0, 20),
        ];

        $response = $this->postJson('/api/branches', $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('branches', [
            'nombre' => $data['nombre']
        ]);
    }

    public function test_update_branch()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);

        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $branch = Branch::factory()->create();

        $data = [
            'nombre' => 'Sucursal Actualizada',
            'direccion' => 'Nueva direccion 123',
        ];

        $response = $this->putJson("/api/branches/{$branch->id}", $data);

        $response->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'nombre' => 'Sucursal Actualizada',
        ]);
    }

    public function test_destroy_branch()
    {
        $this->artisan('db:seed', ['--class' => 'RolSeeder']);

        Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

        $branch = Branch::factory()->create();

        $response = $this->deleteJson("/api/branches/{$branch->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
    }
}