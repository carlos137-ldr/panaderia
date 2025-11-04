<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use App\Http\Resources\CartItemsResource;
use App\Http\Requests\StoreCartitemRequest;
use App\Http\Requests\UpdateCartitemRequest;
use Symfony\Component\HttpFoundation\Response;

class CartitemController extends Controller
{
   public function index(){
    $cartitem = CartItem::with('carts', 'products')->get();
    return CartItemsResource::collection($cartitem);
 }

 public function show(CartItem $cartitem){
    $cartitem = $cartitem -> load('carts', 'products');
    if(!$cartitem){
        return response()->json(['message' => 'Articulo del carrito no encontrado'], 404);
    }
    return new CartItemsResource($cartitem);
 }
 public function store(StorecartitemRequest $request){
    $cartitem = cartitem::create ($request-> validated());
    //$cartitem -> orders() -> sync($request->intput('cart', []));
    $cartitem -> cart() -> associate(Cart::find($request->input('cart_id'))); // Asociar el carrito al cartitem
    $cartitem -> product() -> associate(Product::find($request->input('product_id'))); // Asociar el producto al cartitem
    $cartitem -> save(); 
    return response()->json (new CartItemsResource($cartitem), Response::HTTP_CREATED);
 }
    public function update(UpdatecartitemRequest $request, $id){
        // Actualizar una Articulo del carrito existente
        $cartitem = cartitem::find($id); // Buscar la Articulo del carrito por ID
        if(!$cartitem){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Articulo del carrito no encontrado'], 404);
        }
       // $cartitem -> update ($request-> validated()); // Actualizar la sucursal con los datos validados
       // $cartitem -> orders() -> sync($request->intput('orders', []));// Sincronizar las órdenes relacionadas
         $cartitem -> cart() -> associate(Cart::find($request->input('cart_id'))); // Asociar el carrito al cartitem
            $cartitem -> product() -> associate(Product::find($request->input('product_id'))); // Asociar el producto al cartitem
            $cartitem -> save();
        return response()->json (new CartItemsResource($cartitem), Response::HTTP_ACCEPTED); // Devolver la Articulo del carrito actualizada
    }
    public function destroy($id){
        // Eliminar una Articulo del carrito existente
        $cartitem = cartitem::find($id); // Buscar la Articulo del carrito por ID
        if(!$cartitem){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Articulo del carrito no encontrado'], 404);
        }
        $cartitem -> delete(); // Eliminar la Articulo del carrito
        return response()->json (null, Response::HTTP_NO_CONTENT); // Devolver una respuesta sin contenido
    }
}
