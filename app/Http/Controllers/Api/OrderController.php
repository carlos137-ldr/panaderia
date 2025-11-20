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

    public function index()
    {
        $this->authorize('Ver orden');
        $orders = Order::with(['branch', 'users'])->get();
        return OrdersResource::collection($orders);
    }

    public function show(Order $order)
    {
        $this->authorize('Ver orden');
        $order->load(['branch', 'users']);
        return new OrdersResource($order);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('Crear orden');
        
        // create() usará los datos validados. user_id y branch_id deben estar en $fillable
        $order = Order::create($request->validated());
        
        return response()->json(new OrdersResource($order), Response::HTTP_CREATED);

    }

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

    public function destroy(Order $order)
    {
        $this->authorize('Eliminar orden');
        // $this->authorize('delete', $order);

        $order->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}