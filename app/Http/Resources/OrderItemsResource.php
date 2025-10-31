<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'cantidad' => $this->cantidad,
            'precio_unitario' => $this->precio_unitario,
            // Carga el producto relacionado, usando su propio Resource
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}