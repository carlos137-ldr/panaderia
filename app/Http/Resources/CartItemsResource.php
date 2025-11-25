<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CartsResource;
use App\Http\Resources\ProductsResource;

class CartItemsResource extends JsonResource
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
            'tipo' => 'Item del Carrito',
            'atributos' => [ 
                'Id del carrito' => $this->cart_id,
                'Id del producto' => $this->product_id,
                'cantidad' => $this->cantidad,
            ],
            'relaciones' => [ 
                //'cart' => new CartsResource($this->whenLoaded('cart')),
                'product' => new ProductsResource($this->whenLoaded('product')),
            ],
        ];
        
    }
}
