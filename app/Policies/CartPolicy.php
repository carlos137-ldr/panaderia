<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;

class CartPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Ver carrito');
    }

    public function view(User $user, Cart $cart): bool
    {
        // Puede ver si tiene permiso global O si es el dueño del carrito
        return $user->hasPermissionTo('Ver carrito') || $user->id === $cart->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Crear carrito');
    }

    public function update(User $user, Cart $cart): bool
    {
        // Puede editar si tiene permiso global (ej. Admin) O si es su propio carrito
        // Nota: A veces los admins no deben editar carritos ajenos, ajusta según necesites.
        // Aquí asumo que el permiso 'Editar carrito' es administrativo.
        return $user->hasPermissionTo('Editar carrito') || $user->id === $cart->user_id;
    }

    public function delete(User $user, Cart $cart): bool
    {
        return $user->hasPermissionTo('Eliminar carrito') || $user->id === $cart->user_id;
    }
}