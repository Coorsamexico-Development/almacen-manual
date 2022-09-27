<?php

namespace App\Http\Controllers;

use App\Models\nivel;
use App\Models\posicion;
use App\Models\rack;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NivelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(rack $rack)
    {
        $niveles = $rack->nivels()->select('id', 'name')->get();
        return response()->json($niveles);
    }

    /**
     * Almacena una cantidad especifica de niveles
     */
    public function store(rack $rack, Request $request)
    {
        $validate = $request->validate([
            'cantidad' => ['required', 'numeric'], // unicamente el el componente vue tiene el nombre name
        ]);
        try {
            DB::beginTransaction();
            $niveles = [];
            $totalCreate = (int) $validate['cantidad'];
            $columnas =   $rack->columns()->select('columnas.*')->get();

            $totalNivels = $rack->nivels()->count();
            for ($i = 1; $i <= $totalCreate; $i++) {
                $newNivel['name'] = lettersNivel($totalNivels + $i);
                $nivel = $rack->nivels()->create($newNivel);
                //Store Posiciones de acuerdo a las columnas existentes
                foreach ($columnas as $columna) {
                    posicion::create([
                        'name' => $rack->termino . '-' .  $nivel->name . $columna->name,
                        'nivel_id' => $nivel->id,
                        'columna_id' => $columna->id,
                        'status_posicion_id' => 1
                    ]);
                }
                $niveles[] = $nivel;
            }
            DB::commit();
            return response()->json($niveles);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\nivel  $nivel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, nivel $nivel)
    {
        //
    }
}
