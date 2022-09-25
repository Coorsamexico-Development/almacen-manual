<?php

namespace App\Http\Controllers;

use App\Models\folio;
use App\Models\ordenes_entrada;
use Illuminate\Http\Request;

class FolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ordenes_entrada $ordenEntrada, Request $request)
    {
        $request->validate([
            'direction' => 'in:desc,asc'
        ]);
        $folios = $ordenEntrada->folios()->select('folios.id', 'folios.name')
            ->selectRaw('sum(entradas.cantidad) as totals_productos')
            ->leftJoin('entradas', 'folios.id', '=', 'entradas.folio_id')
            ->groupBy('folios.id', 'folios.name');

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $folios->where('folios.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $folios->orderBy(request('field'), request('direction'));
        } else {
            $folios->orderBy('folios.created_at', 'desc');
        }
        return response()->json([
            'folios' =>  $folios->paginate(10),
            'filters' => request(['search', 'field', 'direction'])
        ]);
    }


    public function indexEntradas(folio $folio, Request $request)
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
}
