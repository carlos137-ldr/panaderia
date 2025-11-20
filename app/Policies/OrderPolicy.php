<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Ver orden');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('Ver orden') || $user->id === $order->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Crear orden');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('Editar orden');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('Eliminar orden');
    }
}