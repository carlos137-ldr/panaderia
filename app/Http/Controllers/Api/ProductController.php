<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductsResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Symfony\Component\HttpFoundation\Response;  

class ProductController extends Controller
{
    public function index(){
    $product = product::with('orderitems', 'cartitems','carts' )->get();
    return ProductsResource::collection($product);
 }

 public function show(Product $product){
    $product = $product -> load('orderitems', 'cartitems','carts' );
    if(!$product){
        return response()->json(['message' => 'producto no encontrado'], 404);
    }
    return new ProductsResource($product);
 }
 public function store(StoreproductRequest $request){
    $product = product::create ($request-> validated());
    //$product -> orders() -> sync($request->intput('orders', [])); 
   $product -> orderitems() -> sync($request->intput('orderitems', []));    
   $product -> cartitems() -> sync($request->intput('cartitems', [] ));
    $product -> carts() -> sync($request->intput('carts', []));
    $product -> save();
    return response()->json (new ProductsResource($product), Response::HTTP_CREATED);
 }
    public function update(UpdateproductRequest $request, $id){
        // Actualizar una producto existente
        $product = product::find($id); // Buscar la producto por ID
        if(!$product){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'producto no encontrado'], 404);
        }
        $product -> update ($request-> validated()); // Actualizar la producto con los datos validados
       // $product -> orders() -> sync($request->intput('orders', []));// Sincronizar las órdenes relacionadas
         $product -> orderitems() -> sync($request->intput('orderitems', []));  
            $product -> cartitems() -> sync($request->intput('cartitems', [] ));
                $product -> carts() -> sync($request->intput('carts', []));
                $product -> save(); // Guardar los cambios
        return response()->json (new ProductsResource($product), Response::HTTP_ACCEPTED); // Devolver la producto actualizada
    }
    public function destroy($id){
        // Eliminar una producto existente
        $product = product::find($id); // Buscar la producto por ID
        if(!$product){ // Si no se encuentra, devolver un error 404
            return response()->json(['message' => 'producto no encontrado'], 404);
        }
        $product -> delete(); // Eliminar la producto
        return response()->json (null, Response::HTTP_NO_CONTENT); // Devolver una respuesta sin contenido
    }
}
