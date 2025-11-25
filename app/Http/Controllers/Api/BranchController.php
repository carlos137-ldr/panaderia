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

    /**
     * @OA\Get(
     *    path="/api/branches",
     *    summary="Consultar sucursales",
     *    description="Retorna todas las sucursales",
     *    tags={"Branches"},
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
        $this->authorize('Ver sucursal');
        $branches = Branch::with('orders')->get();
        return BranchesResource::collection($branches);
    }

    /**
     * @OA\Get(
     *    path="/api/branches/{branch}",
     *    summary="Consultar una sucursal",
     *    description="Retorna una sucursal específica",
     *    tags={"Branches"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="branch",
     *       in="path",
     *       description="ID de la sucursal",
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
     *       description="Sucursal no encontrada"
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    )
     * )
     */
    public function show(Branch $branch)
    {
        $this->authorize('Ver sucursal');
        $branch->load('orders');
        return new BranchesResource($branch);
    }

    /**
     * @OA\Post(
     *    path="/api/branches",
     *    summary="Crear sucursal",
     *    description="Crea una nueva sucursal",
     *    tags={"Branches"},
     *    security={{"bearer_token":{}}},
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          required={"nombre","direccion"},
     *          @OA\Property(property="nombre", type="string", example="Sucursal Centro"),
     *          @OA\Property(property="direccion", type="string", example="Av. Principal 123"),
     *          @OA\Property(property="telefono", type="string", example="555-1234")
     *       )
     *    ),
     *    @OA\Response(
     *       response=201,
     *       description="Sucursal creada",
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

    /**
     * @OA\Put(
     *    path="/api/branches/{branch}",
     *    summary="Actualizar sucursal",
     *    description="Actualiza una sucursal existente",
     *    tags={"Branches"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="branch",
     *       in="path",
     *       description="ID de la sucursal",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          @OA\Property(property="nombre", type="string", example="Sucursal Norte"),
     *          @OA\Property(property="direccion", type="string", example="Calle 456"),
     *          @OA\Property(property="telefono", type="string", example="555-5678")
     *       )
     *    ),
     *    @OA\Response(
     *       response=202,
     *       description="Sucursal actualizada",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Sucursal no encontrada"
     *    )
     * )
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->authorize('Editar sucursal');
        // $this->authorize('update', $branch); // Descomentar si tienes Policy específica para la instancia

        $branch->update($request->validated());
        
        return response()->json(new BranchesResource($branch), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *    path="/api/branches/{branch}",
     *    summary="Eliminar sucursal",
     *    description="Elimina una sucursal existente",
     *    tags={"Branches"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="branch",
     *       in="path",
     *       description="ID de la sucursal",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\Response(
     *       response=204,
     *       description="Sucursal eliminada",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Sucursal no encontrada"
     *    )
     * )
     */
    public function destroy(Branch $branch)
    {
        $this->authorize('Eliminar sucursal');
        // $this->authorize('delete', $branch); // Descomentar si tienes Policy específica para la instancia

        $branch->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}