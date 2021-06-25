<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TiposProductosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tipos_productos')->delete();
        
        \DB::table('tipos_productos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'marca_id' => 1,
                'categoria_id' => 1,
                'nombre' => 'A10',
                'slug' => 'a10',
                'imagenes' => '["tipos-productos\\/May2021\\/wzufgod1ukvsNrZ2G7no.jpg"]',
                'detalles' => NULL,
                'created_at' => '2021-05-20 18:29:38',
                'updated_at' => '2021-05-20 19:43:09',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'marca_id' => 1,
                'categoria_id' => 1,
                'nombre' => 'A70',
                'slug' => 'a70',
                'imagenes' => '["tipos-productos\\/May2021\\/1t7T4k3WPVV7ARPzzhmb.jpg"]',
                'detalles' => NULL,
                'created_at' => '2021-05-20 18:29:47',
                'updated_at' => '2021-05-20 19:49:05',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'marca_id' => 1,
                'nombre' => 'A50',
                'categoria_id' => 1,
                'slug' => 'a50',
                'imagenes' => '["tipos-productos\\/May2021\\/yQzUMGbtJfVkReOgH0I8.jpg"]',
                'detalles' => NULL,
                'created_at' => '2021-05-20 18:30:10',
                'updated_at' => '2021-05-20 19:44:17',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'marca_id' => 2,
                'categoria_id' => 1,
                'nombre' => 'Y5',
                'slug' => 'y5',
                'imagenes' => '["tipos-productos\\/May2021\\/7ObMll5AFytbyYSH8Zuu.jpg"]',
                'detalles' => NULL,
                'created_at' => '2021-05-20 18:30:35',
                'updated_at' => '2021-05-20 19:48:45',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}