<?php

namespace App\Http\Controllers;

use App\Exports\ProductosExport;
use App\Models\producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $productos = DB::table(DB::raw('productos'))
        ->select(DB::raw(
            'productos.id AS id,
             productos.name AS name,
             productos.ean AS ean,
             productos.familia_id AS familia_id,
             productos_tarimas.cant_disponible AS disponible'
        ))
        ->leftjoin('productos_tarimas','productos.id','=','productos_tarimas.producto_id')
        ->get();

        return Inertia::render('Productos/ProductosIndex', [
            'productos' => $productos,
        ]);
    }

    public function exportExample()
    {
        try {
            return Excel::download(new ProductosExport, 'PRODUCTOS_IMPORT.xlsx');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->download(storage_path("app/public/errors/error_file.png"));
        }
    }


    public function import(Request $request)
    {
      
    }


    public function posicionTarimas () 
    {
        
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(producto $producto)
    {
        //
    }
}
