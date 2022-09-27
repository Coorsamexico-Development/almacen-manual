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
            'name' => 'Papás Fritas',
            'ean' => '7370'
        ]);
        DB::table('productos')->insert([
            'name' => 'Refrescos',
            'ean' => '3614'
        ]);
        DB::table('productos')->insert([
            'name' => 'Gomitas',
            'ean' => '3622'
        ]);
        DB::table('productos')->insert([
            'name' => 'Palomitas',
            'ean' => '7352'
        ]);
        DB::table('productos')->insert([
            'name' => 'Chocolate Amargo',
            'ean' => '8005'
        ]);
    }
}
