<?php

namespace App\Http\Controllers;

use App\Models\tarima_posicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarimaPosicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function tarima_posicion ($x)
    {
     $sql =  DB::table(DB::raw('productos'))
            ->select(DB::raw('
               productos.id,
               productos.name,
               productos.ean,
               tarimas.name AS tarima_nombre,
               posicions.name AS posicion_name,
               racks.name AS rack_name,
               productos_tarimas.cant_disponible AS disponible
            '))
            ->leftjoin('productos_tarimas','productos.id','=','productos_tarimas.producto_id')
            ->leftjoin('tarimas','productos_tarimas.tarima_id','=','tarimas.id')
            ->leftjoin('tarima_posicions','tarima_posicions.tarima_id','=','tarimas.id')
            ->leftjoin('posicions','tarima_posicions.posicion_id','=','posicions.id')
            ->leftjoin('columnas','posicions.columna_id','=','columnas.id')
            ->leftjoin('racks','columnas.rack_id','=','racks.id')
            ->where('productos.id', 'like', '%' . $x . '%')
            ->get();

     return $sql;
    }
}
