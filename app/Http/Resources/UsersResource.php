<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrdersResource;
use App\Http\Resources\RolesResource;
use App\Http\Resources\CartsResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo' => 'Usuario',
            'atributos' => [ 
                'nombre' => $this->nombre,
                'email' => $this->email,
                'password' => $this->password,
                'rol_id' => $this->rol_id,
            ],
            'relaciones' => [ 
                'orders' => OrdersResource::collection($this->whenLoaded('orders')),
                'roles' => new RolesResource($this->whenLoaded('roles')),
                'carts' => new CartsResource($this->whenLoaded('carts')),
            ],
    ];
    }
}