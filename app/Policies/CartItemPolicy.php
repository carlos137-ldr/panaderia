<?php

namespace App\Policies;

use App\Models\User;  // Importar el modelo User
use App\Models\CartItem;  // Importar el modelo CartItem

class CartItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // Determinar si el usuario puede actualizar la CartItem
    public function update(User $user, CartItem $cartitem)
    {
        return $user->id === $cartitem->cart_id;
    }

    // Determinar si el usuario puede eliminar la CartItem
    public function delete(User $user, CartItem $cartitem)
    {
        return $user->id === $cartitem->cart_id;
    }

}