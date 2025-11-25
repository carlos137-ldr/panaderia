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

    /**
     * @OA\Get(
     *    path="/api/cartitems",
     *    summary="Consultar items del carrito",
     *    description="Retorna todos los items del carrito",
     *    tags={"CartItems"},
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
    public function index(){
        $this->authorize('Ver item_carrito');
        $cartitem = CartItem::with('cart', 'product')->get();
        return CartItemsResource::collection($cartitem);
    }

    /**
     * @OA\Get(
     *    path="/api/cartitems/{cartitem}",
     *    summary="Consultar un item del carrito",
     *    description="Retorna un item del carrito específico",
     *    tags={"CartItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="cartitem",
     *       in="path",
     *       description="ID del item del carrito",
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
     *       description="Item no encontrado"
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    )
     * )
     */
    public function show(CartItem $cartitem){
        $this->authorize('Ver item_carrito');
        $cartitem = $cartitem->load('cart', 'product');
        return new CartItemsResource($cartitem);
    }

    /**
     * @OA\Post(
     *    path="/api/cartitems",
     *    summary="Agregar item al carrito",
     *    description="Crea un nuevo item en el carrito",
     *    tags={"CartItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          required={"cart_id","product_id","cantidad"},
     *          @OA\Property(property="cart_id", type="integer", example=1),
     *          @OA\Property(property="product_id", type="integer", example=1),
     *          @OA\Property(property="cantidad", type="integer", example=2)
     *       )
     *    ),
     *    @OA\Response(
     *       response=201,
     *       description="Item creado",
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
    public function store(StorecartitemRequest $request){
        $this->authorize('Crear item_carrito');

        $cartitem = CartItem::create($request->validated());
        $cartitem->cart()->associate(Cart::find($request->input('cart_id')));
        $cartitem->product()->associate(Product::find($request->input('product_id')));
        $cartitem->save();

        return response()->json(new CartItemsResource($cartitem), Response::HTTP_CREATED);// respuesta 201
    }

    /**
     * @OA\Put(
     *    path="/api/cartitems/{cartitem}",
     *    summary="Actualizar item del carrito",
     *    description="Actualiza un item existente en el carrito",
     *    tags={"CartItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="cartitem",
     *       in="path",
     *       description="ID del item del carrito",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          @OA\Property(property="cart_id", type="integer", example=1),
     *          @OA\Property(property="product_id", type="integer", example=1),
     *          @OA\Property(property="cantidad", type="integer", example=3)
     *       )
     *    ),
     *    @OA\Response(
     *       response=202,
     *       description="Item actualizado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Item no encontrado"
     *    )
     * )
     */
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

    /**
     * @OA\Delete(
     *    path="/api/cartitems/{cartitem}",
     *    summary="Eliminar item del carrito",
     *    description="Elimina un item del carrito existente",
     *    tags={"CartItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="cartitem",
     *       in="path",
     *       description="ID del item del carrito",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\Response(
     *       response=204,
     *       description="Item eliminado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Item no encontrado"
     *    )
     * )
     */
    public function destroy(CartItem $cartitem){
        $this->authorize('Eliminar item_carrito');
        
        $this->authorize('delete', $cartitem); //  ya existe, ahora se autoriza

        $cartitem->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);// respuesta 204
    }
}