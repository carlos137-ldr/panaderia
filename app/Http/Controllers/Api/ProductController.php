<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductsResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('Ver producto');
        // Cargar relaciones necesarias. Verifica si 'carts' es la relación correcta en tu modelo Product
        $products = Product::with(['orderItems', 'cartItems', 'carts'])->get();
        return ProductsResource::collection($products);
    }

    public function show(Product $product)
    {
        $this->authorize('Ver producto');
        $product->load(['orderItems', 'cartItems', 'carts']);
        return new ProductsResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('Crear producto');

        $product = Product::create($request->validated());

        // Manejo de imágenes si viene en el request (ejemplo básico)
        /*
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('products', 'public');
            $product->imagen = $path;
            $product->save();
        }
        */

        return response()->json(new ProductsResource($product), Response::HTTP_CREATED);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('Editar producto');
        // $this->authorize('update', $product);

        $product->update($request->validated());

        // Lógica opcional para sincronizar relaciones si se envían en el request
        /*
        if ($request->has('carts')) {
            $product->carts()->sync($request->input('carts'));
        }
        */

        return response()->json(new ProductsResource($product), Response::HTTP_ACCEPTED);
    }

    public function destroy(Product $product)
    {
        $this->authorize('Eliminar producto');
        // $this->authorize('delete', $product);

        $product->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}