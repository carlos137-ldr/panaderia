<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CartController extends Controller
{
 public function index(){
    $users = User::all(); // Obtener todos los usuarios
    return response()->json($users); // Devolver los usuarios en formato JSON
 }
}
