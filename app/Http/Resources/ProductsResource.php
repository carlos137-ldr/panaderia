<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CartItemsResource;
use App\Http\Resources\CartsResource;
use App\Http\Resources\OrderItemsResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Define cómo se debe ver un Producto en el JSON
        return [
            'id' => $this->id,
            'tipo' => 'Producto',
            'atributos' => [ 
                'nombre del producto' => $this->nombre,
                'descripcion del producto' => $this->descripcion,
                'precio del producto' => $this->precio,
                'stock' => $this->stock,
            ],
            'relaciones' => [ 
                //'cartItems' => CartItemsResource::collection($this->whenLoaded('cartItems')),
                //'carts' => CartsResource::collection($this->whenLoaded('carts')),
                'orderItems' => OrderItemsResource::collection($this->whenLoaded('orderItems')),
            ],

        ];
    }
}