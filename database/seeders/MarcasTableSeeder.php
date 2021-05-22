<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MarcasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('marcas')->delete();
        
        \DB::table('marcas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Samsung',
                'slug' => 'samsung',
                'logo' => NULL,
                'created_at' => '2021-05-19 10:07:45',
                'updated_at' => '2021-05-19 10:07:45',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Huawei',
                'slug' => 'huawei',
                'logo' => NULL,
                'created_at' => '2021-05-19 10:07:59',
                'updated_at' => '2021-05-19 10:07:59',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Iphone',
                'slug' => 'iphone',
                'logo' => NULL,
                'created_at' => '2021-05-19 10:08:39',
                'updated_at' => '2021-05-19 10:08:39',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'Xiamoni',
                'slug' => 'xiamoni',
                'logo' => NULL,
                'created_at' => '2021-05-19 10:08:55',
                'updated_at' => '2021-05-19 10:08:55',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'nombre' => 'Readmin',
                'slug' => 'readmin',
                'logo' => NULL,
                'created_at' => '2021-05-19 10:09:15',
                'updated_at' => '2021-05-19 10:09:15',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}