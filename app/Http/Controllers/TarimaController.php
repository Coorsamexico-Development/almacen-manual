<?php

namespace App\Http\Controllers;

use App\Models\entrada;
use App\Models\tarima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TarimaController extends Controller
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
        $tarimas = tarima::select(
            'tarimas.id',
            'tarimas.name',
            'tarimas.active',
            'tarimas.created_at',
            'posicions.name as posicion',
        )
            ->selectRaw('ifnull(sum(productos_tarimas.cant_disponible),0) as canitdad_cajas')
            ->leftJoin('productos_tarimas', 'tarimas.id', '=', 'productos_tarimas.tarima_id')
            ->leftJoin('tarima_posicions', function ($join) {
                $join->on('tarimas.id', '=', 'tarima_posicions.tarima_id')
                    ->on('tarima_posicions.active', '=', DB::raw(1));
            })
            ->leftJoin('posicions', 'tarima_posicions.posicion_id', '=', 'posicions.id')
            ->groupBy(
                'tarimas.id',
                'tarimas.name',
                'tarimas.active',
                'tarimas.created_at',
                'posicions.name',
            )->where('tarimas.active', '=', 1);

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $tarimas->where('tarimas.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $tarimas->orderBy(request('field'), request('direction'));
        } else {
            $tarimas->orderBy('tarimas.created_at', 'desc');
        }
        return Inertia::render('Tarimas/TarimasIndex', [
            'tarimas' => fn () => $tarimas->paginate(20),
            'filters' => request(['search', 'field', 'direction'])
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $last = tarima::select('id')->orderBy('id', 'desc')->first();
        tarima::create([
            'name' => 'T' . ($last === null ? 1 : $last->id + 1)
        ]);
        return redirect()->back();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tarima  $tarima
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tarima $tarima)
    {
        //
    }
}
