<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;

class OrderItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Define cómo se ve un item del pedido
        return [
            'id' => $this->id,
            'tipo' => 'Información del Pedido',
            'atributos' => [ 
                'Id del pedido' => $this->order_id,
                'Id del producto' => $this->product_id,
                'cantidad' => $this->cantidad,
                'precio unitario' => $this->precio_unitario,
            ],
            'relaciones' => [ 
                //'order' => new OrdersResource($this->whenLoaded('order')),
                'product' => new ProductsResource($this->whenLoaded('product')),
            ],
        ];
    }
}