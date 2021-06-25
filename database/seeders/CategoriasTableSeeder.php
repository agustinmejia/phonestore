<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categorias')->delete();
        
        \DB::table('categorias')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Celulares',
                'slug' => 'celulares',
                'imagen' => NULL,
                'detalles' => NULL,
                'created_at' => '2021-06-25 14:32:20',
                'updated_at' => '2021-06-25 14:32:20',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Computadoras portátil',
                'slug' => 'computadoras-portatil',
                'imagen' => NULL,
                'detalles' => NULL,
                'created_at' => '2021-06-25 15:14:17',
                'updated_at' => '2021-06-25 16:26:45',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Televisiones',
                'slug' => 'televisiones',
                'imagen' => NULL,
                'detalles' => NULL,
                'created_at' => '2021-06-25 16:27:06',
                'updated_at' => '2021-06-25 16:27:06',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'Lavadoras',
                'slug' => 'lavadoras',
                'imagen' => NULL,
                'detalles' => NULL,
                'created_at' => '2021-06-25 16:27:13',
                'updated_at' => '2021-06-25 16:27:13',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}