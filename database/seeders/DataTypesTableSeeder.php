<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'users',
                'slug' => 'users',
                'display_name_singular' => 'Usuario',
                'display_name_plural' => 'Usuarios',
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2021-05-18 20:00:43',
                'updated_at' => '2021-05-18 20:00:43',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'menus',
                'slug' => 'menus',
                'display_name_singular' => 'Menú',
                'display_name_plural' => 'Menús',
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2021-05-18 20:00:44',
                'updated_at' => '2021-05-18 20:00:44',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'roles',
                'slug' => 'roles',
                'display_name_singular' => 'Rol',
                'display_name_plural' => 'Roles',
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'policy_name' => NULL,
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2021-05-18 20:00:44',
                'updated_at' => '2021-05-18 20:00:44',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'tipos_productos',
                'slug' => 'tipos-productos',
                'display_name_singular' => 'Tipo de Producto',
                'display_name_plural' => 'Tipos de Productos',
                'icon' => 'voyager-gift',
                'model_name' => 'App\\Models\\TiposProducto',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"nombre","order_display_column":"nombre","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-05-19 08:49:29',
                'updated_at' => '2021-06-25 14:26:13',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'marcas',
                'slug' => 'marcas',
                'display_name_singular' => 'Marca',
                'display_name_plural' => 'Marcas',
                'icon' => 'voyager-ticket',
                'model_name' => 'App\\Models\\Marca',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"nombre","order_display_column":"nombre","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-05-19 08:53:27',
                'updated_at' => '2021-05-19 08:54:03',
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'personas',
                'slug' => 'personas',
                'display_name_singular' => 'Persona',
                'display_name_plural' => 'Personas',
                'icon' => 'voyager-people',
                'model_name' => 'App\\Models\\Persona',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"nombre_completo","order_display_column":"nombre_completo","order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-05-19 09:06:44',
                'updated_at' => '2021-06-25 14:02:46',
            ),
            6 => 
            array (
                'id' => 8,
                'name' => 'proveedores',
                'slug' => 'proveedores',
                'display_name_singular' => 'Proveedor',
                'display_name_plural' => 'Proveedores',
                'icon' => 'voyager-book',
                'model_name' => 'App\\Models\\Proveedore',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"nombre_completo","order_display_column":"nombre_completo","order_direction":"asc","default_search_key":null}',
                'created_at' => '2021-05-19 09:43:46',
                'updated_at' => '2021-05-19 09:43:46',
            ),
            7 => 
            array (
                'id' => 11,
                'name' => 'categorias',
                'slug' => 'categorias',
                'display_name_singular' => 'Categoría',
                'display_name_plural' => 'Categorías',
                'icon' => 'voyager-tag',
                'model_name' => 'App\\Models\\Categoria',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2021-06-25 14:28:35',
                'updated_at' => '2021-06-25 14:28:35',
            ),
            8 => 
            array (
                'id' => 12,
                'name' => 'registros_cajas',
                'slug' => 'registros-cajas',
                'display_name_singular' => 'Registro de Caja',
                'display_name_plural' => 'Registros de Caja',
                'icon' => 'voyager-dollar',
                'model_name' => 'App\\Models\\RegistrosCaja',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2021-06-30 17:31:19',
                'updated_at' => '2021-06-30 17:52:39',
            ),
        ));
        
        
    }
}