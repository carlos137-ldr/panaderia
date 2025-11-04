<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CartsItemResource;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\UsersResource;

class CartsResource extends JsonResource
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
            'tipo' => 'Carrito',
            'atributos' => [ 
                'Id del usuario' => $this->user_id,
                'estado' => $this->estado,
                'total' => $this->total,
                
            ],
            'relaciones' => [ 
                //'user' => new UsersResource($this->whenLoaded('user')),
                'usuario' => UsersResource::collection($this->whenLoaded('user')),
                'cartItems' => CartItemsResource::collection($this->whenLoaded('cartItems')),
                'products' => ProductsResource::collection($this->whenLoaded('products')),
            ],
        ];
    }
}
