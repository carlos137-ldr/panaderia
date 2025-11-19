<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;  // Importar el modelo Role
use Spatie\Permission\Models\Permission;  // Importar el modelo Permission

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        Administrador -> CRUD
        Editor -> CRU
        Usuario -> R
        */


        $administrador = Role::create(['name' => 'Administrador']);
        $empleado = Role::create(['name' => 'Empleado']);
        $usuario = Role::create(['name' => 'Usuario']);
        //Branch
        Permission::create(['name' => 'Crear sucursal'])->syncRoles([$administrador, $empleado]);
        Permission::create(['name' => 'Editar sucursal'])->syncRoles([$administrador, $usuario]);
        Permission::create(['name' => 'Eliminar sucursal'])->syncRoles([$administrador]);
        Permission::create(['name' => 'Ver sucursal'])->syncRoles([$administrador, $empleado, $usuario]);

        //Cart
        Permission::create(['name' => 'Crear carrito'])->syncRoles([$administrador, $empleado]);
        Permission::create(['name' => 'Editar carrito'])->syncRoles([$administrador, $usuario]);
        Permission::create(['name' => 'Eliminar carrito'])->syncRoles([$administrador]);
        Permission::create(['name' => 'Ver carrito'])->syncRoles([$administrador, $empleado, $usuario]);

        //Cart_item
        Permission::create(['name' => 'Crear item_carrito'])->syncRoles([$administrador, $empleado]);
        Permission::create(['name' => 'Editar item_carrito'])->syncRoles([$administrador, $usuario]);
        Permission::create(['name' => 'Eliminar item_carrito'])->syncRoles([$administrador]);
        Permission::create(['name' => 'Ver item_carrito'])->syncRoles([$administrador, $empleado, $usuario]);
        //Order
        Permission::create(['name' => 'Crear orden'])->syncRoles([$administrador, $empleado]);
        Permission::create(['name' => 'Editar orden'])->syncRoles([$administrador, $usuario]);
        Permission::create(['name' => 'Eliminar orden'])->syncRoles([$administrador]);
        Permission::create(['name' => 'Ver orden'])->syncRoles([$administrador, $empleado, $usuario]);
        //Order_item
        Permission::create(['name' => 'Crear item_orden'])->syncRoles([$administrador, $empleado]);
        Permission::create(['name' => 'Editar item_orden'])->syncRoles([$administrador, $usuario]);
        Permission::create(['name' => 'Eliminar item_orden'])->syncRoles([$administrador]);
        Permission::create(['name' => 'Ver item_orden'])->syncRoles([$administrador, $empleado, $usuario]);
        //Product
        Permission::create(['name' => 'Crear producto'])->syncRoles([$administrador, $empleado]);
        Permission::create(['name' => 'Editar producto'])->syncRoles([$administrador, $usuario]);
        Permission::create(['name' => 'Eliminar producto'])->syncRoles([$administrador]);
        Permission::create(['name' => 'Ver producto'])->syncRoles([$administrador, $empleado, $usuario]);
    }
}