<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //agregamos seeder default
        DB::table('users')->insert([
            'name' => 'admin',
            'nombre' => 'Admin',
            'ap_paterno' => 'Sistemas',
            'ap_materno' => 'Coorsamexico',
            'rol_id' => 1,
            'active' => 1,
            'email' => 'admin@coorsamexico.com',
            'password' => Hash::make('admin123'),
        ]);
    }
}
