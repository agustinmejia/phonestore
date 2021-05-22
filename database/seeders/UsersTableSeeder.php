<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$RmwBHv2q6AD4.sF5iblL0uCwMDmhVj.mhKG0JHlTK5mbQQ18vQJVi',
                'remember_token' => NULL,
                'settings' => NULL,
                'created_at' => '2021-05-18 23:14:30',
                'updated_at' => '2021-05-18 23:14:30',
            ),
        ));
        
        
    }
}