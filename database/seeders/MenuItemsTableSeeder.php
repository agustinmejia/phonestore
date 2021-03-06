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
                'created_at' => '2021-05-18 19:46:41',
                'updated_at' => '2021-07-03 14:27:23',
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
                'created_at' => '2021-05-18 19:46:41',
                'updated_at' => '2021-05-18 19:51:17',
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
                'created_at' => '2021-05-18 19:46:42',
                'updated_at' => '2021-05-18 19:52:34',
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
                'created_at' => '2021-05-18 19:46:42',
                'updated_at' => '2021-05-18 19:52:38',
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
                'order' => 10,
                'created_at' => '2021-05-18 19:46:42',
                'updated_at' => '2021-09-01 22:19:50',
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
                'created_at' => '2021-05-18 19:46:42',
                'updated_at' => '2021-05-18 19:51:17',
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
                'created_at' => '2021-05-18 19:46:42',
                'updated_at' => '2021-05-18 19:51:17',
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
                'created_at' => '2021-05-18 19:46:42',
                'updated_at' => '2021-05-18 19:51:17',
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
                'created_at' => '2021-05-18 19:46:42',
                'updated_at' => '2021-05-18 19:51:17',
                'route' => 'voyager.bread.index',
                'parameters' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'title' => 'Configuraci??n',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 9,
                'created_at' => '2021-05-18 19:46:42',
                'updated_at' => '2021-07-05 17:21:36',
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
                'order' => 8,
                'created_at' => '2021-05-18 19:52:27',
                'updated_at' => '2021-06-30 14:18:47',
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
                'order' => 2,
                'created_at' => '2021-05-19 04:49:29',
                'updated_at' => '2021-06-25 10:29:02',
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
                'order' => 3,
                'created_at' => '2021-05-19 04:53:28',
                'updated_at' => '2021-06-25 10:29:02',
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
                'order' => 4,
                'created_at' => '2021-05-19 05:06:44',
                'updated_at' => '2021-06-25 10:29:02',
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
                'created_at' => '2021-05-19 05:07:58',
                'updated_at' => '2021-05-19 05:08:16',
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
                'order' => 5,
                'created_at' => '2021-05-19 05:43:46',
                'updated_at' => '2021-06-25 10:29:03',
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
                'order' => 4,
                'created_at' => '2021-05-20 14:26:37',
                'updated_at' => '2021-06-14 06:58:51',
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
                'order' => 5,
                'created_at' => '2021-05-20 14:26:56',
                'updated_at' => '2021-06-14 06:58:51',
                'route' => 'ventas.index',
                'parameters' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'menu_id' => 1,
                'title' => 'Productos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-credit-cards',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 3,
                'created_at' => '2021-06-14 06:57:57',
                'updated_at' => '2021-06-14 06:58:51',
                'route' => 'productos.index',
                'parameters' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'menu_id' => 1,
                'title' => 'Reportes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-pie-graph',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 7,
                'created_at' => '2021-06-18 10:12:36',
                'updated_at' => '2021-06-30 14:18:46',
                'route' => NULL,
                'parameters' => '',
            ),
            20 => 
            array (
                'id' => 23,
                'menu_id' => 1,
                'title' => 'Ventas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-basket',
                'color' => '#000000',
                'parent_id' => 20,
                'order' => 2,
                'created_at' => '2021-06-19 11:18:15',
                'updated_at' => '2021-06-30 14:18:47',
                'route' => 'index.ventas',
                'parameters' => NULL,
            ),
            21 => 
            array (
                'id' => 25,
                'menu_id' => 1,
                'title' => 'Categor??as',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tag',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 1,
                'created_at' => '2021-06-25 10:28:35',
                'updated_at' => '2021-06-25 10:29:02',
                'route' => 'voyager.categorias.index',
                'parameters' => NULL,
            ),
            22 => 
            array (
                'id' => 26,
                'menu_id' => 1,
                'title' => 'Limpiar cache',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-refresh',
                'color' => '#000000',
                'parent_id' => 5,
                'order' => 6,
                'created_at' => '2021-06-25 12:05:20',
                'updated_at' => '2021-06-25 12:05:28',
                'route' => 'clear.cache',
                'parameters' => NULL,
            ),
            23 => 
            array (
                'id' => 27,
                'menu_id' => 1,
                'title' => 'Registros de Caja',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 6,
                'created_at' => '2021-06-30 13:31:20',
                'updated_at' => '2021-06-30 13:31:37',
                'route' => 'voyager.registros-cajas.index',
                'parameters' => NULL,
            ),
            24 => 
            array (
                'id' => 28,
                'menu_id' => 1,
                'title' => 'Diario',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => '#000000',
                'parent_id' => 20,
                'order' => 1,
                'created_at' => '2021-06-30 14:18:30',
                'updated_at' => '2021-06-30 14:18:46',
                'route' => 'index.diario',
                'parameters' => NULL,
            ),
            25 => 
            array (
                'id' => 29,
                'menu_id' => 1,
                'title' => 'Deudas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-book',
                'color' => '#000000',
                'parent_id' => 20,
                'order' => 4,
                'created_at' => '2021-07-05 17:21:25',
                'updated_at' => '2021-09-01 22:19:50',
                'route' => 'index.deudores',
                'parameters' => NULL,
            ),
            26 => 
            array (
                'id' => 30,
                'menu_id' => 1,
                'title' => 'Pagos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-dollar',
                'color' => '#000000',
                'parent_id' => 20,
                'order' => 3,
                'created_at' => '2021-09-01 22:19:41',
                'updated_at' => '2021-09-01 22:19:50',
                'route' => 'index.pagos',
                'parameters' => NULL,
            ),
        ));
        
        
    }
}