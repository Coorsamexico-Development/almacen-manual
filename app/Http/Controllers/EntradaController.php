<?php

namespace App\Http\Controllers;

use App\Models\entrada;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'direction' => 'in:desc,asc'
        ]);
        $entradas = entrada::select('entradas.*', 'productos.ean', 'folios.name as folio')
            ->join('productos', 'entradas.producto_id', '=', 'productos.id')
            ->join('folios', 'entradas.folio_id', '=', 'folios.id');

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $entradas->where('productos.ean', 'like', '%' . $search . '%');
            $entradas->orWhere('folios.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $entradas->orderBy(request('field'), request('direction'));
        } else {
            $entradas->orderBy('entradas.created_at', 'desc');
        }
        return Inertia::render('Tarimas/EntarimadoIndex', [
            'entradas' => fn () => $entradas->get(),
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
