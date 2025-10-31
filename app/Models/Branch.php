<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [ // Atributos asignables masivamente
        'nombre',
        'direccion',
        'telefono',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class); // Relación uno a muchos con Order
    }
    
}
