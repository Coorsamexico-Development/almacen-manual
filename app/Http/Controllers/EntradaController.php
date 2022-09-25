<?php

namespace App\Http\Controllers;

use App\Models\entrada;
use App\Models\tarima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(tarima $tarima, Request $request)
    {
        $request->validate([
            'direction' => 'in:desc,asc'
        ]);
        $entradas = entrada::select(
            'entradas.id',
            'folios.name as folio',
            'productos.ean',
            'productos.name as producto',
        )
            ->selectRaw('ifnull(sum(entradas_reals.disponible),0) as cantidad_disponible')
            ->join('folios', 'entradas.folio_id', '=', 'folios.id')
            ->join('productos', 'entradas.producto_id', '=', 'productos.id')
            ->join('entradas_reals', function ($join) {
                $join->on('entradas.id', '=', 'entradas_reals.entrada_id')
                    ->on('entradas_reals.disponible', '>', DB::raw(0));
            })
            ->groupBy(
                'entradas.id',
                'folios.name',
                'productos.name',
                'productos.ean'
            );

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $entradas->where('folios.name', 'like', '%' . $search . '%')
                ->orWhere('productos.ean', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $entradas->orderBy(request('field'), request('direction'));
        } else {
            $entradas->orderBy('entradas.created_at', 'desc');
        }
        return response()->json([
            'entradas' =>  $entradas->paginate(20),
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
