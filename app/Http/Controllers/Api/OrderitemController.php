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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderitemController extends Controller
{
    use AuthorizesRequests;
    public function index(){
    $this->authorize('Ver item_carrito');
    $orderitem = Orderitem::with('products', 'orders')->get();
    return OrderItemsResource::collection($orderitem);
 }

 public function show(Orderitem $orderitem){
    $this->authorize('Ver item_carrito');
    $orderitem = $orderitem -> load('products', 'orders');
    if(!$orderitem){
        return response()->json(['message' => 'Articulo de la orden no encontrada'], 404);
    }
    return new OrderItemsResource($orderitem);
 }


 public function store(StoreorderitemRequest $request){
    $this->authorize('Crear item_carrito');
    $orderitem = orderitem::create ($request-> validated());
    //$orderitem -> orders() -> sync($request->intput('orders', [])); 
    $orderitem -> products() -> sync($request->intput('products', []));    
    $orderitem -> orders() -> sync($request->intput('orders', []));
    $orderitem -> save();
    return response()->json (new OrderItemsResource($orderitem), Response::HTTP_CREATED);
 }
    public function update(UpdateorderitemRequest $request, $id){
        $this->authorize('Editar item_carrito');
        $this->authorize('update', $orderitem);
        $orderitem->update($request->all());
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
        $this->authorize('Eliminar item_carrito');
        $this->authorize('delete', $orderitem);
        $orderitem->delete();
        // Eliminar una Articulo de la orden existente
        $orderitem = orderitem::find($id); // Buscar la Articulo de la orden por ID
        if(!$orderitem){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Articulo de la orden no encontrada'], 404);
        }
        $orderitem -> delete(); // Eliminar la Articulo de la orden
        return response()->json (null, Response::HTTP_NO_CONTENT); // Devolver una respuesta sin contenido
    }
}
