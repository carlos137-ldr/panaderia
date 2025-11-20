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

    public function index()
    {
        $this->authorize('Ver item_orden');
        $orderItems = OrderItem::with(['products', 'orders'])->get();
        return OrderItemsResource::collection($orderItems);
    }

    public function show(OrderItem $orderitem)
    {
        $this->authorize('Ver item_orden');
        $orderitem->load(['products', 'orders']);
        return new OrderItemsResource($orderitem);
    }

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

    public function update(UpdateOrderitemRequest $request, OrderItem $orderitem)
    {
        $this->authorize('Editar item_orden');
        // $this->authorize('update', $orderitem); // Si tienes policy

        $orderitem->update($request->validated());

        return response()->json(new OrderItemsResource($orderitem), Response::HTTP_ACCEPTED);
    }

    public function destroy(OrderItem $orderitem)
    {
        $this->authorize('Eliminar item_orden');
        // $this->authorize('delete', $orderitem); // Si tienes policy

        $orderitem->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}