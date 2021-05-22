<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_items')->delete();
        
        \DB::table('menu_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Inicio',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-play',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 1,
                'created_at' => '2021-05-18 23:46:41',
                'updated_at' => '2021-05-19 09:08:40',
                'route' => 'voyager.dashboard',
                'parameters' => 'null',
            ),
            1 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Media',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 1,
                'created_at' => '2021-05-18 23:46:41',
                'updated_at' => '2021-05-18 23:51:17',
                'route' => 'voyager.media.index',
                'parameters' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'menu_id' => 1,
                'title' => 'Users',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 1,
                'created_at' => '2021-05-18 23:46:42',
                'updated_at' => '2021-05-18 23:52:34',
                'route' => 'voyager.users.index',
                'parameters' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'menu_id' => 1,
                'title' => 'Roles',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 2,
                'created_at' => '2021-05-18 23:46:42',
                'updated_at' => '2021-05-18 23:52:38',
                'route' => 'voyager.roles.index',
                'parameters' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'menu_id' => 1,
                'title' => 'Herramientas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 6,
                'created_at' => '2021-05-18 23:46:42',
                'updated_at' => '2021-05-20 18:27:05',
                'route' => NULL,
                'parameters' => '',
            ),
            5 => 
            array (
                'id' => 6,
                'menu_id' => 1,
                'title' => 'Menu Builder',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 2,
                'created_at' => '2021-05-18 23:46:42',
                'updated_at' => '2021-05-18 23:51:17',
                'route' => 'voyager.menus.index',
                'parameters' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'menu_id' => 1,
                'title' => 'Database',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 3,
                'created_at' => '2021-05-18 23:46:42',
                'updated_at' => '2021-05-18 23:51:17',
                'route' => 'voyager.database.index',
                'parameters' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'title' => 'Compass',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-compass',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 4,
                'created_at' => '2021-05-18 23:46:42',
                'updated_at' => '2021-05-18 23:51:17',
                'route' => 'voyager.compass.index',
                'parameters' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'menu_id' => 1,
                'title' => 'BREAD',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bread',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 5,
                'created_at' => '2021-05-18 23:46:42',
                'updated_at' => '2021-05-18 23:51:17',
                'route' => 'voyager.bread.index',
                'parameters' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'title' => 'Configuración',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => '#000000',
                'parent_id' => 5,
                'order' => 6,
                'created_at' => '2021-05-18 23:46:42',
                'updated_at' => '2021-05-18 23:51:41',
                'route' => 'voyager.settings.index',
                'parameters' => 'null',
            ),
            10 => 
            array (
                'id' => 11,
                'menu_id' => 1,
                'title' => 'Seguridad',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 5,
                'created_at' => '2021-05-18 23:52:27',
                'updated_at' => '2021-05-20 18:27:07',
                'route' => NULL,
                'parameters' => '',
            ),
            11 => 
            array (
                'id' => 12,
                'menu_id' => 1,
                'title' => 'Tipos de Productos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-gift',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 1,
                'created_at' => '2021-05-19 08:49:29',
                'updated_at' => '2021-05-19 09:08:03',
                'route' => 'voyager.tipos-productos.index',
                'parameters' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'menu_id' => 1,
                'title' => 'Marcas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-ticket',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 2,
                'created_at' => '2021-05-19 08:53:28',
                'updated_at' => '2021-05-19 09:08:07',
                'route' => 'voyager.marcas.index',
                'parameters' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'menu_id' => 1,
                'title' => 'Personas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-people',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 3,
                'created_at' => '2021-05-19 09:06:44',
                'updated_at' => '2021-05-19 09:08:09',
                'route' => 'voyager.personas.index',
                'parameters' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'menu_id' => 1,
                'title' => 'Registros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 2,
                'created_at' => '2021-05-19 09:07:58',
                'updated_at' => '2021-05-19 09:08:16',
                'route' => NULL,
                'parameters' => '',
            ),
            15 => 
            array (
                'id' => 16,
                'menu_id' => 1,
                'title' => 'Proveedores',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-book',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 4,
                'created_at' => '2021-05-19 09:43:46',
                'updated_at' => '2021-05-19 09:43:57',
                'route' => 'voyager.proveedores.index',
                'parameters' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'menu_id' => 1,
                'title' => 'Compras',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-receipt',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 3,
                'created_at' => '2021-05-20 18:26:37',
                'updated_at' => '2021-05-20 18:27:03',
                'route' => 'compras.index',
                'parameters' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'menu_id' => 1,
                'title' => 'Ventas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-basket',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 4,
                'created_at' => '2021-05-20 18:26:56',
                'updated_at' => '2021-05-20 18:27:07',
                'route' => 'ventas.index',
                'parameters' => NULL,
            ),
        ));
        
        
    }
}