<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('productos')->insert([
            'name' => 'Producto 1 ',
            'ean' => '7370'
        ]);
        DB::table('productos')->insert([
            'name' => 'Producto 2',
            'ean' => '3614'
        ]);
        DB::table('productos')->insert([
            'name' => 'Producto 3 ',
            'ean' => '3622'
        ]);
        DB::table('productos')->insert([
            'name' => 'Producto 1 ',
            'ean' => '7352'
        ]);
        DB::table('productos')->insert([
            'name' => 'Producto 4 ',
            'ean' => '8005'
        ]);
    }
}
