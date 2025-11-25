<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BranchesResource;
use App\Http\Resources\UsersResource;

class OrdersResource extends JsonResource
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
            'tipo' => 'Orden',
            'atributos' => [ 
                'Id del usuario' => $this->user_id,
                'Id de la sucursal' => $this->branch_id,
                'Fecha del pedido' => $this->fecha_pedido,
                'Fecha para recoger el pedido' => $this->fecha_recogida,
                'estado' => $this->estado,
                'total' => $this->total,
                
            ],
            'relaciones' => [ 
                //'branch' => new BranchesResource($this->whenLoaded('branch')),
                //'user' => new UsersResource($this->whenLoaded('user')),
            ],
        ];
    }
}
