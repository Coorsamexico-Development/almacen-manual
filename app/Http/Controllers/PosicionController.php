<?php

namespace App\Http\Controllers;

use App\Models\posicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $posicions = posicion::select(
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
            $posicions->where('posicions.name', 'like', '%' . $search . '%')
                ->orWhere('racks.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $posicions->orderBy(request('field'), request('direction'));
        } else {
            $posicions->orderBy('posicions.name', 'asc');
        }
        return response()->json([
            'posicions' =>  $posicions->paginate(10),
            'filters' => request(['search', 'field', 'direction'])
        ]);
    }

    /**
     * Obtiene las posiciones donde se encuentra disponible el producto
     */
    public function posicionesProducto(Request $request)
    {
        $validData = $request->validate([
            'direction' => 'in:desc,asc',
            'producto_id' => ['required', 'exists:productos,id'],
        ]);
        $posicions = posicion::select(
            'posicions.id',
            'posicions.name',
            'tarima_posicions.tarima_id',
        )
            ->selectRaw('sum(productos_tarimas.cant_disponible) as cant_disponible')
            ->join('tarima_posicions', function ($query) {
                $query->on('posicions.id', '=', 'tarima_posicions.posicion_id')
                    ->on('tarima_posicions.active', '=', DB::raw(1));
            })
            ->join('tarimas', function ($query) {
                $query->on('tarima_posicions.tarima_id', '=', 'tarimas.id')
                    ->on('tarimas.active', '=', DB::raw(1));
            })
            ->join('productos_tarimas', function ($query) use ($validData) {
                $query->on('tarimas.id', '=', 'productos_tarimas.tarima_id')
                    ->on('productos_tarimas.producto_id', '=', DB::raw($validData['producto_id']))
                    ->on('productos_tarimas.cant_disponible', '>', DB::raw(0));
            })
            ->where('posicions.status_posicion_id', '=', 2)
            ->groupBy([
                'posicions.id',
                'posicions.name',
                'tarima_posicions.tarima_id'
            ]);

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $posicions->where('posicions.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $posicions->orderBy(request('field'), request('direction'));
        } else {
            $posicions->orderBy('posicions.name', 'asc');
        }
        return response()->json([
            'posicions' =>  $posicions->paginate(10),
            'filters' => request(['search', 'field', 'direction'])
        ]);
    }
}
