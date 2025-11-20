<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Http\Resources\CartsResource;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CartController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('Ver carrito');
        // Corregido 'users' a 'user' si la relación es belongsTo en el modelo, 
        // o 'users' si es hasOne/Many. Asumo 'users' por tu modelo Cart.
        $carts = Cart::with(['users', 'cartItems', 'products'])->get();
        return CartsResource::collection($carts);
    }

    public function show(Cart $cart)
    {
        $this->authorize('Ver carrito');
        $cart->load(['users', 'cartItems', 'products']);
        return new CartsResource($cart);
    }

    public function store(StoreCartRequest $request)
    {
        $this->authorize('Crear carrito');

        $cart = Cart::create($request->validated());
        
        // Si el user_id no viene en el validated(), lo asociamos manualmente si es necesario
        // Pero como está en el request rules, create() ya lo debería tomar.
        
        $cart->load(['users', 'cartItems', 'products']);
        return response()->json(new CartsResource($cart), Response::HTTP_CREATED);
    }

    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $this->authorize('Editar carrito');
        // $this->authorize('update', $cart); 

        $cart->update($request->validated());
        
        // Si se necesita actualizar el usuario explícitamente:
        if ($request->has('user_id')) {
             $cart->user_id = $request->input('user_id');
             $cart->save();
        }

        $cart->load(['users', 'cartItems', 'products']);
        return response()->json(new CartsResource($cart), Response::HTTP_ACCEPTED);
    }

    public function destroy(Cart $cart)
    {
        $this->authorize('Eliminar carrito');
        // $this->authorize('delete', $cart);

        $cart->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}