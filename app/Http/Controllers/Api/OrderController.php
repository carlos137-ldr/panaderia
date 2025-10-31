<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Product;
class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['products'])->get(); // Obtener todos los pedidos con sus productos relacionados
        return response()->json($orders);
    }
}
