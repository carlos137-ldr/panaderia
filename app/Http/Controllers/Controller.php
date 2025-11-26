<?php

namespace App\Http\Controllers;
 /**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearer_token",
 *     type="http", 
 *     scheme="bearer",
 *     bearerFormat="token",
 *     in="header",
 *     name="Authorization"
 * )
 * @OA\Server(url="https://panaderia-production-8071.up.railway.app")
 */

abstract class Controller
{
    //
}