<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
   

class Order extends Model
{
    use HasFactory;
    protected $fillable = [ // Atributos asignables masivamente
        'user_id',
        'branch_id',
        'fecha_pedido',
        'fecha_recogida',
        'estado',
        'total',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }   
}
