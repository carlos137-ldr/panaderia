<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Branch;
use App\Http\Resources\OrdersResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *    path="/api/orders",
     *    summary="Consultar todas las órdenes",
     *    description="Retorna todas las órdenes",
     *    tags={"Orders"},
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
     *     description="No se encontraron órdenes"
     *   ),
     *   @OA\Response(
     *    response=405,
     *    description="Método no permitido"
     *   )
     * )
     */
    public function index()
    {
        $this->authorize('Ver orden');
        $orders = Order::with(['branch', 'users'])->get();
        return OrdersResource::collection($orders);
    }

    /**
     * @OA\Get(
     *    path="/api/orders/{order}",
     *    summary="Consultar una orden",
     *    description="Retorna una orden específica",
     *    tags={"Orders"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="order",
     *       in="path",
     *       description="ID de la orden",
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
     *       description="Orden no encontrada"
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    )
     * )
     */
    public function show(Order $order)
    {
        $this->authorize('Ver orden');
        $order->load(['branch', 'users']);
        return new OrdersResource($order);
    }

    /**
     * @OA\Post(
     *    path="/api/orders",
     *    summary="Crear orden",
     *    description="Crear una nueva orden",
     *    tags={"Orders"},
     *    security={{"bearer_token":{}}},
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          required={"user_id","branch_id","fecha_pedido","fecha_recogida","estado","total"},
     *          @OA\Property(property="user_id", type="integer", example=1),
     *          @OA\Property(property="branch_id", type="integer", example=1),
     *          @OA\Property(property="fecha_pedido", type="string", format="date", example="2023-10-27"),
     *          @OA\Property(property="fecha_recogida", type="string", format="date", example="2023-10-28"),
     *          @OA\Property(property="estado", type="string", example="pendiente"),
     *          @OA\Property(property="total", type="number", format="float", example=150.50)
     *       )
     *    ),
     *    @OA\Response(
     *       response=201,
     *       description="Orden creada",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    )
     * )
     */
    public function store(StoreOrderRequest $request)
    {
        $this->authorize('Crear orden');
        
        // create() usará los datos validados. user_id y branch_id deben estar en $fillable
        $order = Order::create($request->validated());
        
        return response()->json(new OrdersResource($order), Response::HTTP_CREATED);

    }

    /**
     * @OA\Put(
     *    path="/api/orders/{order}",
     *    summary="Actualizar orden",
     *    description="Actualizar una orden existente",
     *    tags={"Orders"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="order",
     *       in="path",
     *       description="ID de la orden",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          @OA\Property(property="user_id", type="integer", example=1),
     *          @OA\Property(property="branch_id", type="integer", example=1),
     *          @OA\Property(property="fecha_pedido", type="string", format="date", example="2023-10-27"),
     *          @OA\Property(property="fecha_recogida", type="string", format="date", example="2023-10-28"),
     *          @OA\Property(property="estado", type="string", example="preparando"),
     *          @OA\Property(property="total", type="number", format="float", example=150.50)
     *       )
     *    ),
     *    @OA\Response(
     *       response=202,
     *       description="Orden actualizada",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Orden no encontrada"
     *    )
     * )
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $this->authorize('Editar orden');
        // $this->authorize('update', $order);

        $order->update($request->validated());
        
        // Si necesitas re-asociar branch o user manualmente (normalmente update ya lo hace si están en el request)
        if($request->has('branch_id')) {
            $order->branch()->associate(Branch::find($request->input('branch_id')));
        }
        if($request->has('user_id')) {
            $order->user()->associate(User::find($request->input('user_id')));
        }
        $order->save();

        return response()->json(new OrdersResource($order), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *    path="/api/orders/{order}",
     *    summary="Eliminar orden",
     *    description="Elimina una orden existente",
     *    tags={"Orders"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="order",
     *       in="path",
     *       description="ID de la orden",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\Response(
     *       response=204,
     *       description="Orden eliminada",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Orden no encontrada"
     *    )
     * )
     */
    public function destroy(Order $order)
    {
        $this->authorize('Eliminar orden');
        // $this->authorize('delete', $order);

        $order->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}