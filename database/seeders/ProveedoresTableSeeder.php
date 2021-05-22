<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProveedoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('proveedores')->delete();
        
        \DB::table('proveedores')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre_completo' => 'Sin nombre',
                'nit' => '00000',
                'telefono' => '000000',
                'direccion' => NULL,
                'foto' => NULL,
                'created_at' => '2021-05-20 15:25:10',
                'updated_at' => '2021-05-20 15:25:10',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}