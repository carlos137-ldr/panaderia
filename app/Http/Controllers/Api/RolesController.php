<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Http\Resources\RolesResource;
use App\Http\Requests\StoreRolesRequest;
use App\Http\Requests\UpdateRolesRequest;
use Symfony\Component\HttpFoundation\Response;  

class RolesController extends Controller
{
    public function index(){
    $roles = roles::with('users')->get();
    return rolesresource::collection($roles);
 }

 public function show(Roles $roles){
    $roles = $roles -> load('users');
    if(!$roles){
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }
    return new rolesResource($roles);
 }
 public function store(StorerolesRequest $request){
    $roles = roles::create ($request-> validated());
    $roles -> orders() -> sync($request->intput('users', [])); 
    return response()->json (new rolesResource($roles), Response::HTTP_CREATED);
 }
    public function update(UpdaterolesRequest $request, $id){
        // Actualizar una Usuario existente
        $roles = roles::find($id); // Buscar la Usuario por ID
        if(!$roles){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $roles -> update ($request-> validated()); // Actualizar la Usuario con los datos validados
        $roles -> orders() -> sync($request->intput('users', []));// Sincronizar las órdenes relacionadas
        return response()->json (new rolesResource($roles), Response::HTTP_ACCEPTED); // Devolver la Usuario actualizada
    }
    public function destroy($id){
        // Eliminar una Usuario existente
        $roles = roles::find($id); // Buscar la Usuario por ID
        if(!$roles){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $roles -> delete(); // Eliminar la Usuario
        return response()->json (null, Response::HTTP_NO_CONTENT); // Devolver una respuesta sin contenido
    }
}
