<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        // Generalmente cualquiera puede ver la lista de productos, 
        // pero si quieres restringirlo al permiso:
        return $user->hasPermissionTo('Ver producto');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('Ver producto');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Crear producto');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('Editar producto');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('Eliminar producto');
    }
}