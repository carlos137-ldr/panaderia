<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderResource;

class BranchesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Define cómo se debe ver una Sucursal en el JSON
        return [
            'id' => $this->id,
            'tipo' => 'Sucursal',
            'atributos' => [ 
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            ],
            'relaciones' => [ 
                'orders' => OrdersResource::collection($this->whenLoaded('orders')),
                
            ],
    ];
    }
}

