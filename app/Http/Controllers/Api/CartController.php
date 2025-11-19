<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Resources\CartsResource;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
 public function index(){
     $this->authorize('Ver carrito'); 
   $cart = cart::with('users','cartitems', 'products')->get(); 
    return CartsResource::collection($cart);
 }

 public function show(cart $cart){
    $this->authorize('Ver carrito');
    $cart = $cart -> load('users','cartitems', 'products');
    if(!$cart){
        return response()->json(['message' => 'Carrito no encontrado'], 404);
    }
    return new CartsResource($cart);
 }
 public function store(StorecartRequest $request){
    $this->authorize('Crear carrito');
    $cart = cart::create ($request-> validated());
   // $cart -> orders() -> sync($request->intput('user','cartitems', 'products', [])); // Sincronizar las órdenes relacionadas
   $cart -> user() -> associate(User::find($request->input('user_id'))); // Asociar el usuario al carrito
   $cart -> save();
   $cart -> load('users','cartitems', 'products'); // Cargar las relaciones
    return response()->json (new CartsResource($cart), Response::HTTP_CREATED);
 }
    public function update(UpdatecartRequest $request, $id){
        $this->authorize('Editar carrito');
        $this->authorize('update', $cart);
        // Actualizar una sucursal existente
        $cart = cart::find($id); // Buscar la sucursal por ID
        if(!$cart){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Carrito no encontrado'], 404);
        }
        $cart -> update ($request-> validated()); // Actualizar la sucursal con los datos validados
        $cart -> user() -> associate(User::find($request->input('user_id'))); // Asociar el usuario al carrito
        $cart -> save(); // Guardar los cambios
        $cart -> load('user','cartitems', 'products'); // Cargar las relaciones
        return response()->json (new CartsResource($cart), Response::HTTP_ACCEPTED); // Devolver la sucursal actualizada
    }
    public function destroy($id){
        $this->authorize('Eliminar carrito');
        $this->authorize('delete', $cart);
        // Eliminar una sucursal existente
        $cart = cart::find($id); // Buscar la sucursal por ID
        if(!$cart){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Carrito no encontrado'], 404);
        }
        $cart -> delete(); // Eliminar la sucursal
        return response()->json (null, Response::HTTP_NO_CONTENT); // Devolver una respuesta sin contenido
    }
 
}
