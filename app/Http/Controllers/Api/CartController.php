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

    /**
     * @OA\Get(
     *    path="/api/carts",
     *    summary="Consultar carritos",
     *    description="Retorna todos los carritos",
     *    tags={"Carts"},
     *    security={{"bearer_token":{}}},
     *    @OA\Response(
     *       response=200,
     *      description="Operación exitosa",
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="No autorizado"
     *   )
     * )
     */
    public function index()
    {
        $this->authorize('Ver carrito');
        // Corregido 'users' a 'user' si la relación es belongsTo en el modelo, 
        // o 'users' si es hasOne/Many. Asumo 'users' por tu modelo Cart.
        $carts = Cart::with(['users', 'cartItems', 'products'])->get();
        return CartsResource::collection($carts);
    }

    /**
     * @OA\Get(
     *    path="/api/carts/{cart}",
     *    summary="Consultar un carrito",
     *    description="Retorna un carrito específico",
     *    tags={"Carts"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="cart",
     *       in="path",
     *       description="ID del carrito",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\Response(
     *       response=200,
     *       description="Operación exitosa",
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Carrito no encontrado"
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    )
     * )
     */
    public function show(Cart $cart)
    {
        $this->authorize('Ver carrito');
        $cart->load(['users', 'cartItems', 'products']);
        return new CartsResource($cart);
    }

    /**
     * @OA\Post(
     *    path="/api/carts",
     *    summary="Crear carrito",
     *    description="Crea un nuevo carrito",
     *    tags={"Carts"},
     *    security={{"bearer_token":{}}},
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          required={"user_id"},
     *          @OA\Property(property="user_id", type="integer", example=1)
     *       )
     *    ),
     *    @OA\Response(
     *       response=201,
     *       description="Carrito creado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=422,
     *       description="Error de validación"
     *    )
     * )
     */
    public function store(StoreCartRequest $request)
    {
        $this->authorize('Crear carrito');

        $cart = Cart::create($request->validated());
        
        // Si el user_id no viene en el validated(), lo asociamos manualmente si es necesario
        // Pero como está en el request rules, create() ya lo debería tomar.
        
        $cart->load(['users', 'cartItems', 'products']);
        return response()->json(new CartsResource($cart), Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *    path="/api/carts/{cart}",
     *    summary="Actualizar carrito",
     *    description="Actualiza un carrito existente",
     *    tags={"Carts"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="cart",
     *       in="path",
     *       description="ID del carrito",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          @OA\Property(property="user_id", type="integer", example=1)
     *       )
     *    ),
     *    @OA\Response(
     *       response=202,
     *       description="Carrito actualizado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Carrito no encontrado"
     *    )
     * )
     */
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

    /**
     * @OA\Delete(
     *    path="/api/carts/{cart}",
     *    summary="Eliminar carrito",
     *    description="Elimina un carrito existente",
     *    tags={"Carts"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="cart",
     *       in="path",
     *       description="ID del carrito",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\Response(
     *       response=204,
     *       description="Carrito eliminado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Carrito no encontrado"
     *    )
     * )
     */
    public function destroy(Cart $cart)
    {
        $this->authorize('Eliminar carrito');
        // $this->authorize('delete', $cart);

        $cart->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}