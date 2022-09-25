<?php

namespace App\Http\Controllers;

use App\Models\entrada;
use App\Models\folio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(folio $folio, Request $request)
    {
        $request->validate([
            'direction' => 'in:desc,asc'
        ]);
        $entradas = $folio->entradas()->select(
            'entradas.id',
            'entradas.cantidad',
            'productos.ean as ean',
            'productos.name as producto'
        )->selectRaw('ifnull(sum(entradas_reals.cantidad),0) as cantidad_real')
            ->join('productos', 'entradas.producto_id', '=', 'productos.id')
            ->leftJoin('entradas_reals', 'entradas.id', '=', 'entradas_reals.entrada_id')
            ->groupBy(
                'entradas.id',
                'entradas.cantidad',
                'productos.ean',
                'productos.name'
            );

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $entradas->where('productos.ean', 'like', '%' . $search . '%')
                ->orWhere('productos.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $entradas->orderBy(request('field'), request('direction'));
        } else {
            $entradas->orderBy('entradas.created_at', 'desc');
        }
        return response()->json([
            'entradas' => $entradas->paginate(10),
            'filters' => request(['search', 'field', 'direction'])
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, entrada $entrada)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy(entrada $entrada)
    {
        //
    }
}
