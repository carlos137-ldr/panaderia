<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Http\Resources\BranchesResource;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BranchController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('Ver sucursal');
        $branches = Branch::with('orders')->get();
        return BranchesResource::collection($branches);
    }

    public function show(Branch $branch)
    {
        $this->authorize('Ver sucursal');
        $branch->load('orders');
        return new BranchesResource($branch);
    }

    public function store(StoreBranchRequest $request)
    {
        $this->authorize('Crear sucursal');
        
        $branch = Branch::create($request->validated());
        
        // Si envías órdenes al crear (opcional)
        if ($request->has('orders')) {
             // Asegúrate de que la relación y los datos sean correctos para sync/save
             // $branch->orders()->sync($request->input('orders')); 
        }

        return response()->json(new BranchesResource($branch), Response::HTTP_CREATED);
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->authorize('Editar sucursal');
        // $this->authorize('update', $branch); // Descomentar si tienes Policy específica para la instancia

        $branch->update($request->validated());
        
        return response()->json(new BranchesResource($branch), Response::HTTP_ACCEPTED);
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('Eliminar sucursal');
        // $this->authorize('delete', $branch); // Descomentar si tienes Policy específica para la instancia

        $branch->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}