<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [ // Atributos asignables masivamente
        'order_id',
        'product_id',
        'cantidad',
        'precio_unitario',
    ];
    public function products()
    {
        return $this->belongsTo(Product::class); // Relación inversa con Product
    }
    public function orders()
    {
        return $this->belongsTo(Order::class); // Relación inversa con Order
    }
}
