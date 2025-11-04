<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CartitemController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\OrderitemController;
use App\Http\Controllers\Api\LoginController;
//use App\Http\Controllers\Api\LoginController;

route ::post ('login', [LoginController::class, 'store']);

route ::middleware('auth:sanctum')->group(function () { // Rutas protegidas por autenticación
Route::apiResource('orderitems', OrderitemController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('carts', CartController::class);
Route::apiResource('branches', BranchController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('roles', RolesController::class);  
Route::apiResource('cartitems', CartitemController::class);
});


