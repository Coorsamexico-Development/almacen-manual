<?php

namespace App\Http\Controllers;

use App\Models\entrada;
use App\Models\entradas_real;
use App\Models\productos_tarimas;
use App\Models\tarima;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
            'tarimaExpress' => fn () => tarima::find(1),
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
            'name' => 'T' .  $last->id
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
    /**
     * Update or Storege Real Entrada of tarima
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tarima  $tarima
     * @return \Illuminate\Http\Response
     */
    public function storeEntradaProducto(Request $request, tarima $tarima)
    {

        $request->validate([
            'entrada_id' => ['required', 'numeric'],
            'cantidad' => ['required', 'numeric', 'min:0']
        ]);
        // Lo reconsulto para segurar de tener los datos actualizados
        $entrada = entrada::select(
            'entradas.id',
            'entradas.producto_id'
        )
            ->selectRaw('ifnull(sum(entradas_reals.disponible),0) as cantidad_disponible')
            ->join('entradas_reals', function ($join) {
                $join->on('entradas.id', '=', 'entradas_reals.entrada_id')
                    ->on('entradas_reals.disponible', '>=', DB::raw(0));
            })
            // ->leftJoin('productos_tarimas', function ($join) use ($tarima) {
            //     $join->on('entradas_reals.id', '=', 'productos_tarimas.entradas_real_id')
            //         ->on('productos_tarimas.tarima_id', '=', DB::raw($tarima->id));
            // })
            ->groupBy(
                'entradas.id',
            )->firstWhere('entradas.id', '=', $request->entrada_id);

        if ($entrada === null) {
            throw ValidationException::withMessages([
                'entrada_id' => 'La entrada no cuenta con Entradas Reales Invalida'
            ]);
        }
        // Validamos que la cantidad agregar sean manor a lo disponible

        if (($request->cantidad) > $entrada->cantidad_disponible) {
            throw ValidationException::withMessages([
                'cantidad' => 'La cantidad supera a lo disponible: ' . $entrada->cantidad_disponible
            ]);
        }
        $entradasReales = entradas_real::select('entradas_reals.*')
            ->where('entradas_reals.entrada_id', '=', $entrada->id)
            ->where('entradas_reals.disponible', '>', 0)
            ->get();
        $cantidad = $request->cantidad;
        try {
            DB::beginTransaction();

            foreach ($entradasReales as $entradaReal) {

                if ($entradaReal->disponible >= $cantidad) {
                    $entradaReal->disponible = $entradaReal->cantidad - $cantidad;
                    $tarima->entradasReales()->attach([
                        $entradaReal->id =>
                        ['cant_disponible' => $cantidad, 'producto_id' => $entrada->producto_id, 'user_id' => Auth::id()]
                    ]);
                    $cantidad = 0;
                } else {
                    $cantidad -= $entradaReal->cantidad;
                    $tarima->entradasReales()->attach([
                        $entradaReal->id =>  ['cant_disponible' => $entradaReal->cantidad, 'producto_id' => $entrada->producto_id, 'user_id' => Auth::id()]
                    ]);
                    $entradaReal->disponible = 0;
                }
                $entradaReal->save();
                if ($cantidad === 0) {
                    break;
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'actualizado'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'cantidad' => $e->getMessage()
            ]);
        }
    }


    /**
     * Update or Storege Real Entrada of tarima
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tarima  $tarima
     * @return \Illuminate\Http\Response
     */
    public function destroyEntradaProducto(Request $request, tarima $tarima)
    {

        $request->validate([
            'entrada_id' => ['required', 'numeric'],
            'cantidad' => ['required', 'numeric', 'min:0']
        ]);
        // Lo reconsulto para segurar de tener los datos actualizados
        $entrada = entrada::select(
            'entradas.id',
            'entradas.producto_id'
        )
            ->selectRaw('ifnull(sum(entradas_reals.disponible),0) as cantidad_disponible')
            ->selectRaw('ifnull(sum(productos_tarimas.cant_disponible),0) as cantidad_tarima')
            ->join('entradas_reals', function ($join) {
                $join->on('entradas.id', '=', 'entradas_reals.entrada_id')
                    ->on('entradas_reals.disponible', '>=', DB::raw(0));
            })
            ->leftJoin('productos_tarimas', function ($join) use ($tarima) {
                $join->on('entradas_reals.id', '=', 'productos_tarimas.entradas_real_id')
                    ->on('productos_tarimas.tarima_id', '=', DB::raw($tarima->id));
            })
            ->groupBy(
                'entradas.id',
            )->firstWhere('entradas.id', '=', $request->entrada_id);

        if ($entrada === null) {
            throw ValidationException::withMessages([
                'entrada_id' => 'La entrada no cuenta con Entradas Reales Invalida'
            ]);
        }
        // Validamos que la cantidad agregar sean manor a lo disponible

        if (($request->cantidad) > $entrada->cantidad_tarima) {
            throw ValidationException::withMessages([
                'cantidad' => 'La cantidad supera a lo que existe en la tarima: ' . $entrada->cantidad_tarima
            ]);
        }
        $entradasReales = entradas_real::select('entradas_reals.*')
            ->with(['productosTarimas' => function ($query) use ($tarima) {
                $query->where('productos_tarimas.tarima_id', '=', $tarima->id);
            }])
            ->where('entradas_reals.entrada_id', '=', $entrada->id)
            ->where('entradas_reals.disponible', '<', DB::raw('`entradas_reals`.`cantidad`'))
            ->orderBy('entradas_reals.updated_at', 'desc')
            ->get();
        $cantidad = $request->cantidad;
        try {
            DB::beginTransaction();

            foreach ($entradasReales as $entradaReal) {
                $cantidadTomada = $entradaReal->cantidad - $entradaReal->disponible;
                if (($cantidadTomada) >= $cantidad) {
                    $entradaReal->disponible = $entradaReal->disponible + $cantidad;
                    $auxTotal = $cantidad;
                    foreach ($entradaReal->productosTarimas as $productoTarima) {
                        if ($productoTarima->cant_disponible >= $auxTotal) {
                            $productoTarima->cant_disponible = $productoTarima->cant_disponible  - $auxTotal;
                            $productoTarima->save();
                            $auxTotal = 0;
                        } else {
                            $auxTotal -= $productoTarima->cant_disponible;
                            $productoTarima->delete();
                        }

                        if ($auxTotal === 0) {
                            break;
                        }
                    }
                    $cantidad = 0;
                } else {
                    $cantidad -= $cantidadTomada;
                    $entradaReal->disponible = $entradaReal->disponible + $cantidadTomada;
                    productos_tarimas::where('entradas_real_id', '=', $entradaReal->id)
                        ->where('tarima_id', '=', $tarima->id)->delete();
                }
                // Actualizamos los campos productos tarimas


                $entradaReal->save();
                if ($cantidad === 0) {
                    break;
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'actualizado'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'cantidad' => $e->getMessage()
            ]);
        }
    }
}
