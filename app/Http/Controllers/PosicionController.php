<?php

namespace App\Http\Controllers;

use App\Models\posicion;
use Illuminate\Http\Request;

class PosicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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


    public function indexDisponibles(Request $request)
    {
        $request->validate([
            'direction' => 'in:desc,asc'
        ]);
        $entradas = posicion::select(
            'posicions.id',
            'posicions.name',
            'status_posicions.name as status',
            'nivels.name as nivel',
            'columnas.name as columna',
            'racks.name as rack',
        )
            ->join('status_posicions', 'posicions.status_posicion_id', '=', 'status_posicions.id')
            ->join('nivels', 'posicions.nivel_id', '=', 'nivels.id')
            ->join('columnas', 'posicions.columna_id', '=', 'columnas.id')
            ->join('racks', 'nivels.rack_id', '=', 'racks.id')
            ->where('posicions.status_posicion_id', '=', 1);

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $entradas->where('posicions.name', 'like', '%' . $search . '%')
                ->orWhere('racks.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $entradas->orderBy(request('field'), request('direction'));
        } else {
            $entradas->orderBy('posicions.name', 'asc');
        }
        return response()->json([
            'posicions' =>  $entradas->paginate(20),
            'filters' => request(['search', 'field', 'direction'])
        ]);
    }

    /**
     * Obtiene las posiciones donde se encuentra disponible el producto
     */
    public function posicionesProducto(Request $request)
    {
        $request->validate([
            'direction' => 'in:desc,asc',
            'producto_id' => ['required', 'exists:productos,id'],
        ]);
        $entradas = posicion::select(
            'posicions.id',
            'posicions.name',
            'nivels.name as nivel',
            'columnas.name as columna',
            'racks.name as rack',
        )
            ->join('status_posicions', 'posicions.status_posicion_id', '=', 'status_posicions.id')
            ->join('nivels', 'posicions.nivel_id', '=', 'nivels.id')
            ->join('columnas', 'posicions.columna_id', '=', 'columnas.id')
            ->join('racks', 'nivels.rack_id', '=', 'racks.id')
            ->where('posicions.status_posicion_id', '=', 1);

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $entradas->where('posicions.name', 'like', '%' . $search . '%')
                ->orWhere('racks.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $entradas->orderBy(request('field'), request('direction'));
        } else {
            $entradas->orderBy('posicions.name', 'asc');
        }
        return response()->json([
            'posicions' =>  $entradas->paginate(20),
            'filters' => request(['search', 'field', 'direction'])
        ]);
    }
}
