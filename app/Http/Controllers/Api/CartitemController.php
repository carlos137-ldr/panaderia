<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;

class CartitemController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with(['cart', 'product'])->get(); // Obtener todos los items con sus relaciones
        return response()->json($cartItems);
    }
}
