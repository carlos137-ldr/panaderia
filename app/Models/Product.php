<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_items');
    }
}
