<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Http\Resources\BranchesResource;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use Symfony\Component\HttpFoundation\Response;

class BranchController extends Controller
{
public function index(){
    $branch = Branch::with('orders')->get();
    return BranchesResource::collection($branch);
 }

 public function show(Branch $branch){
    $branch = $branch -> load('orders');
    if(!$branch){
        return response()->json(['message' => 'Sucursal no encontrada'], 404);
    }
    return new BranchesResource($branch);
 }
 public function store(StoreBranchRequest $request){
    $branch = Branch::create ($request-> validated());
    $branch -> orders() -> sync($request->intput('orders', [])); 
    return response()->json (new BranchesResource($branch), Response::HTTP_CREATED);
 }
    public function update(UpdateBranchRequest $request, $id){
        // Actualizar una sucursal existente
        $branch = Branch::find($id); // Buscar la sucursal por ID
        if(!$branch){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Sucursal no encontrada'], 404);
        }
        $branch -> update ($request-> validated()); // Actualizar la sucursal con los datos validados
        $branch -> orders() -> sync($request->intput('orders', []));// Sincronizar las órdenes relacionadas
        return response()->json (new BranchesResource($branch), Response::HTTP_ACCEPTED); // Devolver la sucursal actualizada
    }
    public function destroy($id){
        // Eliminar una sucursal existente
        $branch = Branch::find($id); // Buscar la sucursal por ID
        if(!$branch){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Sucursal no encontrada'], 404);
        }
        $branch -> delete(); // Eliminar la sucursal
        return response()->json (null, Response::HTTP_NO_CONTENT); // Devolver una respuesta sin contenido
    }
}

