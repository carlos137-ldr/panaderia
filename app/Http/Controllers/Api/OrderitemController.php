<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;
use App\Http\Resources\OrderItemsResource;
use App\Http\Requests\StoreOrderitemRequest;
use App\Http\Requests\UpdateOrderitemRequest;
use Symfony\Component\HttpFoundation\Response;

class OrderitemController extends Controller
{
    public function index(){
    $orderitem = Orderitem::with('products', 'orders')->get();
    return OrderItemsResource::collection($orderitem);
 }

 public function show(Orderitem $orderitem){
    $orderitem = $orderitem -> load('products', 'orders');
    if(!$orderitem){
        return response()->json(['message' => 'Articulo de la orden no encontrada'], 404);
    }
    return new OrderItemsResource($orderitem);
 }
 public function store(StoreorderitemRequest $request){
    $orderitem = orderitem::create ($request-> validated());
    //$orderitem -> orders() -> sync($request->intput('orders', [])); 
    $orderitem -> products() -> sync($request->intput('products', []));    
    $orderitem -> orders() -> sync($request->intput('orders', []));
    $orderitem -> save();
    return response()->json (new OrderItemsResource($orderitem), Response::HTTP_CREATED);
 }
    public function update(UpdateorderitemRequest $request, $id){
        // Actualizar una Articulo de la orden existente
        $orderitem = orderitem::find($id); // Buscar la Articulo de la orden por ID
        if(!$orderitem){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Articulo de la orden no encontrada'], 404);
        }
        $orderitem -> update ($request-> validated()); // Actualizar la Articulo de la orden con los datos validados
        //$orderitem -> orders() -> sync($request->intput('orders', []));// Sincronizar las órdenes relacionadas
            $orderitem -> products() -> sync($request->intput('products', []));    
            $orderitem -> orders() -> sync($request->intput('orders', []));
            $orderitem -> save(); // Guardar los cambios
        return response()->json (new OrderItemsResource($orderitem), Response::HTTP_ACCEPTED); // Devolver la Articulo de la orden actualizada
    }
    public function destroy($id){
        // Eliminar una Articulo de la orden existente
        $orderitem = orderitem::find($id); // Buscar la Articulo de la orden por ID
        if(!$orderitem){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Articulo de la orden no encontrada'], 404);
        }
        $orderitem -> delete(); // Eliminar la Articulo de la orden
        return response()->json (null, Response::HTTP_NO_CONTENT); // Devolver una respuesta sin contenido
    }
}
