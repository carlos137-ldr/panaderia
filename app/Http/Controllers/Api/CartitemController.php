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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CartitemController extends Controller
{
    use AuthorizesRequests;

    public function index(){
        $this->authorize('Ver item_carrito');
        $cartitem = CartItem::with('cart', 'product')->get();
        return CartItemsResource::collection($cartitem);
    }

    public function show(CartItem $cartitem){
        $this->authorize('Ver item_carrito');
        $cartitem = $cartitem->load('cart', 'product');
        return new CartItemsResource($cartitem);
    }

    public function store(StorecartitemRequest $request){
        $this->authorize('Crear item_carrito');

        $cartitem = CartItem::create($request->validated());
        $cartitem->cart()->associate(Cart::find($request->input('cart_id')));
        $cartitem->product()->associate(Product::find($request->input('product_id')));
        $cartitem->save();

        return response()->json(new CartItemsResource($cartitem), Response::HTTP_CREATED);// respuesta 201
    }

    public function update(UpdatecartitemRequest $request,CartItem $cartitem){
        $this->authorize('Editar item_carrito');

        $this->authorize('update', $cartitem); //  ahora sí se puede autorizar
        // $cartitem = CartItem::find($cartitem); //  primero lo buscamos

        if(!$cartitem){
            return response()->json(['message' => 'Artículo del carrito no encontrado'], 404);
        }       
        $cartitem->update($request->all());// se actualiza el cartitem

        return response()->json(new CartItemsResource($cartitem), Response::HTTP_ACCEPTED);// respuesta 202
    }

    public function destroy(CartItem $cartitem){
        $this->authorize('Eliminar item_carrito');
        
        $this->authorize('delete', $cartitem); //  ya existe, ahora se autoriza

        $cartitem->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);// respuesta 204
    }
}
