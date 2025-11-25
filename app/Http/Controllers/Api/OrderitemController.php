<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Http\Resources\OrderItemsResource;
use App\Http\Requests\StoreOrderitemRequest;
use App\Http\Requests\UpdateOrderitemRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderitemController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *    path="/api/orderitems",
     *    summary="Consultar todos los items de orden",
     *    description="Retorna todos los items de orden",
     *    tags={"OrderItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\Response(
     *       response=200,
     *      description="Operación exitosa",
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="No autorizado"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="No se encontraron items"
     *   ),
     *   @OA\Response(
     *    response=405,
     *    description="Método no permitido"
     *   )
     * )
     */
    public function index()
    {
        $this->authorize('Ver item_orden');
        $orderItems = OrderItem::with(['products', 'orders'])->get();
        return OrderItemsResource::collection($orderItems);
    }

    /**
     * @OA\Get(
     *    path="/api/orderitems/{orderitem}",
     *    summary="Consultar un item de orden",
     *    description="Retorna un item de orden específico",
     *    tags={"OrderItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="orderitem",
     *       in="path",
     *       description="ID del item de orden",
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
    public function show(OrderItem $orderitem)
    {
        $this->authorize('Ver item_orden');
        $orderitem->load(['products', 'orders']);
        return new OrderItemsResource($orderitem);
    }

    /**
     * @OA\Post(
     *    path="/api/orderitems",
     *    summary="Crear item de orden",
     *    description="Crear un nuevo item de orden",
     *    tags={"OrderItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          required={"order_id","product_id","cantidad","precio_unitario"},
     *          @OA\Property(property="order_id", type="integer", example=1),
     *          @OA\Property(property="product_id", type="integer", example=1),
     *          @OA\Property(property="cantidad", type="integer", example=2),
     *          @OA\Property(property="precio_unitario", type="number", format="float", example=15.50)
     *       )
     *    ),
     *    @OA\Response(
     *       response=201,
     *       description="Item creado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    )
     * )
     */
    public function store(StoreOrderitemRequest $request)
    {
        $this->authorize('Crear item_orden');

        $orderitem = OrderItem::create($request->validated());
        
        // No es necesario usar associate si los IDs ya vienen en el validated array y son fillable
        // Pero si quieres asegurar la relación explícita:
        // $orderitem->order_id = $request->input('order_id');
        // $orderitem->product_id = $request->input('product_id');
        // $orderitem->save();

        return response()->json(new OrderItemsResource($orderitem), Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *    path="/api/orderitems/{orderitem}",
     *    summary="Actualizar item de orden",
     *    description="Actualizar un item de orden existente",
     *    tags={"OrderItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="orderitem",
     *       in="path",
     *       description="ID del item de orden",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          @OA\Property(property="order_id", type="integer", example=1),
     *          @OA\Property(property="product_id", type="integer", example=1),
     *          @OA\Property(property="cantidad", type="integer", example=3),
     *          @OA\Property(property="precio_unitario", type="number", format="float", example=15.50)
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
    public function update(UpdateOrderitemRequest $request, OrderItem $orderitem)
    {
        $this->authorize('Editar item_orden');
        // $this->authorize('update', $orderitem); // Si tienes policy

        $orderitem->update($request->validated());

        return response()->json(new OrderItemsResource($orderitem), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *    path="/api/orderitems/{orderitem}",
     *    summary="Eliminar item de orden",
     *    description="Elimina un item de orden existente",
     *    tags={"OrderItems"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="orderitem",
     *       in="path",
     *       description="ID del item de orden",
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
    public function destroy(OrderItem $orderitem)
    {
        $this->authorize('Eliminar item_orden');
        // $this->authorize('delete', $orderitem); // Si tienes policy

        $orderitem->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}