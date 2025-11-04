<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [ // Atributos asignables masivamente
        'user_id',
    ];

    public function users()
    {
        return $this->hasOne(User::class ,'id','user_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_items');
    }
}
