<?php

namespace App\Policies;

use App\Models\OrderItem;
use App\Models\User;

class OrderItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Ver item_orden');
    }

    public function view(User $user, OrderItem $orderItem): bool
    {
        // Acceso si tiene permiso o si es dueño de la orden a la que pertenece el item
        return $user->hasPermissionTo('Ver item_orden') || $user->id === $orderItem->order->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Crear item_orden');
    }

    public function update(User $user, OrderItem $orderItem): bool
    {
        return $user->hasPermissionTo('Editar item_orden');
    }

    public function delete(User $user, OrderItem $orderItem): bool
    {
        return $user->hasPermissionTo('Eliminar item_orden');
    }
}