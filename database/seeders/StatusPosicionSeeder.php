<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPosicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_posicions')->insert([
            'id' => 1,
            'name' => 'Libre'
        ]);
        DB::table('status_posicions')->insert([
            'id' => 2,
            'name' => 'Ocupado'
        ]);
        DB::table('status_posicions')->insert([
            'id' => 3,
            'name' => 'Próximo a expirar'
        ]);
        DB::table('status_posicions')->insert([
            'id' => 4,
            'name' => 'Desabilitado'
        ]);
        DB::table('status_posicions')->insert([
            'id' => 5,
            'name' => 'Mantenimiento'
        ]);
    }
}
