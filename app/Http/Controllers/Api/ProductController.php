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

    /**
     * @OA\Get(
     *    path="/api/products",
     *    summary="Consultar todos los productos",
     *    description="Retorna todos los productos",
     *    tags={"Products"},
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
     *     description="No se encontraron productos"
     *   ),
     *   @OA\Response(
     *    response=405,
     *    description="Método no permitido"
     *   )
     * )
     */
    public function index()
    {
        $this->authorize('Ver producto');
        // Cargar relaciones necesarias. Verifica si 'carts' es la relación correcta en tu modelo Product
        $products = Product::with(['orderItems', 'cartItems', 'carts'])->get();
        return ProductsResource::collection($products);
    }

    /**
     * @OA\Get(
     *    path="/api/products/{product}",
     *    summary="Consultar un producto",
     *    description="Retorna un producto específico",
     *    tags={"Products"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="product",
     *       in="path",
     *       description="ID del producto",
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
     *       description="Producto no encontrado"
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    )
     * )
     */
    public function show(Product $product)
    {
        $this->authorize('Ver producto');
        $product->load(['orderItems', 'cartItems', 'carts']);
        return new ProductsResource($product);
    }
    /**
     * @OA\Post(
     *    path="/api/products",
     *    summary="Crear producto",
     *    description="Crear un nuevo producto",
     *    tags={"Products"},
     *    security={{"bearer_token":{}}},
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"nombre","precio","stock","imagen"},
     *              @OA\Property(property="nombre", type="string", example="Concha"),
     *              @OA\Property(property="descripcion", type="string", example="Pan dulce tradicional"),
     *              @OA\Property(property="precio", type="number", format="float", example=12.50),
     *              @OA\Property(property="stock", type="integer", example=50),
     *              @OA\Property(property="imagen", type="string", format="binary")
     *         )
     *       )
     *    ),
     *    @OA\Response(
     *       response=201,
     *       description="Producto creado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    )
     * )
     */
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

    /**
     * @OA\Post(
     *    path="/api/products/{product}",
     *    summary="Actualizar producto",
     *    description="Actualizar un producto existente",
     *    tags={"Products"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="product",
     *       in="path",
     *       description="ID del producto",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"_method"},
     *              @OA\Property(property="_method", type="string", example="PUT"),
     *              @OA\Property(property="nombre", type="string", example="Concha"),
     *              @OA\Property(property="descripcion", type="string", example="Pan dulce tradicional"),
     *              @OA\Property(property="precio", type="number", format="float", example=12.50),
     *              @OA\Property(property="stock", type="integer", example=50),
     *              @OA\Property(property="imagen", type="string", format="binary")
     *         )
     *       )
     *    ),
     *    @OA\Response(
     *       response=202,
     *       description="Producto actualizado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Producto no encontrado"
     *    )
     * )
     */
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

    /**
     * @OA\Delete(
     *    path="/api/products/{product}",
     *    summary="Eliminar producto",
     *    description="Elimina un producto existente",
     *    tags={"Products"},
     *    security={{"bearer_token":{}}},
     *    @OA\Parameter(
     *       name="product",
     *       in="path",
     *       description="ID del producto",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *    @OA\Response(
     *       response=204,
     *       description="Producto eliminado",
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="No autorizado"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Producto no encontrado"
     *    )
     * )
     */
    public function destroy(Product $product)
    {
        $this->authorize('Eliminar producto');
        // $this->authorize('delete', $product);

        $product->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}