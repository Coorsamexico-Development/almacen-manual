<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusOrdenesEntradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_ordenes_entradas')->insert([
            'id' => 1,
            'name' => 'PENDIENTE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status_ordenes_entradas')->insert([
            'id' => 2,
            'name' => 'EN PROCESO',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status_ordenes_entradas')->insert([
            'id' => 3,
            'name' => 'FINALIZADO',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status_ordenes_entradas')->insert([
            'id' => 4,
            'name' => 'FINALIZADO CON INCIDENCIA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status_ordenes_entradas')->insert([
            'id' => 5,
            'name' => 'CANCELADO',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
