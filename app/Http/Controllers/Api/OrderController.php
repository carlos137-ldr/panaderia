<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Branch;
use App\Models\User;
use App\Http\Resources\OrdersResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
   public function index(){
    $order = Order::with('branch', 'users')->get();
    return OrdersResource::collection($order);
 }

 public function show(Order $order){
    $order = $order -> load('branch', 'users');
    if(!$order){
        return response()->json(['message' => 'orden no encontrada'], 404);
    }
    return new OrdersResource($order);
 }
 public function store(StoreorderRequest $request){
    $order = order::create ($request-> validated());
    //$order -> orders() -> sync($request->intput('orders', [])); 
    $order -> branch() -> associate(Branch::find($request->input('branch_id'))); // Asociar la orden al pedido
    $order -> user() -> associate(User::find($request->input('user_id'))); // Asociar el usuario al pedido
    $order -> save();
    return response()->json (new OrdersResource($order), Response::HTTP_CREATED);
 }
    public function update(UpdateorderRequest $request, $id){
        // Actualizar una orden existente
        $order = order::find($id); // Buscar la orden por ID
        if(!$order){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'orden no encontrada'], 404);
        }
        $order -> update ($request-> validated()); // Actualizar la orden con los datos validados
        //$order -> orders() -> sync($request->intput('orders', []));// Sincronizar las órdenes relacionadas
        $order -> branch() -> associate(Branch::find($request->input('branch_id'))); // Asociar la orden al pedido
        $order -> user() -> associate(User::find($request->input('user_id'))); // Asociar el usuario al pedido
        $order -> save();
        return response()->json (new OrdersResource($order), Response::HTTP_ACCEPTED); // Devolver la orden actualizada
    }
    public function destroy($id){
        // Eliminar una orden existente
        $order = order::find($id); // Buscar la orden por ID
        if(!$order){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'orden no encontrada'], 404);
        }
        $order -> delete(); // Eliminar la orden
        return response()->json (null, Response::HTTP_NO_CONTENT); // Devolver una respuesta sin contenido
    }
}
