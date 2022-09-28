<?php

namespace App\Http\Controllers;

use App\Models\posicion;
use App\Models\productos_tarimas;
use App\Models\salida;
use App\Models\tarima;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SalidaController extends Controller
{





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, salida $salida)
    {
        $validaData = $request->validate([
            'posicon_id' => ['required', 'exists:posicions,id'],
            'tarima_id' => ['required', 'exists:posicions,id'],
            'cantidad' => ['required', 'numeric', 'min:1']
        ]);

        if ($salida->surtido + $validaData['cantidad'] > $salida->solicitado) {
            @throw ValidationException::withMessages([
                'cantidad' => "La cantidad supera lo solicitado"
            ]);
            return;
        }

        $productosTarima = productos_tarimas::select(
            'productos_tarimas.id',
            'productos_tarimas.cant_disponible',
        )->where('productos_tarimas.tarima_id', '=', $validaData['tarima_id'])
            ->where('productos_tarimas.producto_id', '=', $salida->producto_id)
            ->where('productos_tarimas.cant_disponible', '>', 0)
            ->get();

        if ($productosTarima->sum('cant_disponible') < $validaData['cantidad']) {
            @throw ValidationException::withMessages([
                'cantidad' => "La cantidad supera a lo disponible"
            ]);
            return;
        }
        $cantidad = $validaData['cantidad'];
        try {
            DB::beginTransaction();
            foreach ($productosTarima as $productoTarima) {
                if ($productoTarima->cant_disponible >= $cantidad) {
                    $productoTarima->cant_disponible = $productoTarima->cant_disponible - $cantidad;
                    $productoTarima->save();
                    if ($productoTarima->cant_disponible === 0) {
                        // Buscamos si la tarima no cuenta con productos
                        $withProductos = productos_tarimas::where('productos_tarimas.tarima_id', '=', $validaData['tarima_id'])
                            ->where('productos_tarimas.cant_disponible', '>', 0)
                            ->exists();
                        // En caso de no tener productos la tarima se ponene como inactiva y se desocupa libera
                        if (!$withProductos) {
                            $tarima = tarima::find($validaData['tarima_id']);
                            $tarima->active = 0;
                            $tarima->save();
                            $posicon = posicion::find($validaData['posicon_id']);
                            $posicon->status_posicion_id = 1;
                            $posicon->save();
                        }
                    }
                    $cantidad = 0;
                } else {
                    $cantidad -= $productoTarima->cant_disponible;
                    $productoTarima->cant_disponible = 0;
                    $productoTarima->save();
                }
                if ($cantidad === 0) {
                    break;
                }
            }
            $salida->surtido = $salida->surtido +  $validaData['cantidad'];
            $salida->save();
            DB::commit();
            return response()->json([
                'message' => 'Guardado'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            @throw ValidationException::withMessages([
                'message' => $e->getMessage()
            ]);
        }
    }
}
