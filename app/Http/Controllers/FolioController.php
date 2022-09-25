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
}
