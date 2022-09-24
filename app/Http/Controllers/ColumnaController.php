<?php

namespace App\Http\Controllers;

use App\Models\columna;
use App\Models\posicion;
use App\Models\rack;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ColumnaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(rack $rack)
    {
        $columnas = $rack->columns()->select('id', 'name')->get();
        return response()->json($columnas);
    }

    /**
     * Almacena una cantidad especifica de columnas
     */
    public function store(rack $rack, Request $request)
    {
        $validate = $request->validate([
            'cantidad' => ['required', 'numeric'], // unicamente el el componente vue tiene el nombre name
        ]);
        try {
            DB::beginTransaction();
            $columnas = [];


            $totalCreate = (int) $validate['cantidad'];
            $niveles =   $rack->nivels()->select('nivels.*')->get();
            $totalColumns = $rack->columns()->count();

            for ($i = 1; $i <= $totalCreate; $i++) {
                $newColumna['name'] = $totalColumns + $i;
                $columna = $rack->columns()->create($newColumna);
                //Store Posiciones de acuerdo a los niveles existentes
                foreach ($niveles as $nivel) {
                    posicion::create([
                        'name' => $nivel->name . $columna->name,
                        'nivel_id' => $nivel->id,
                        'columna_id' => $columna->id,
                        'status_posicion_id' => 1
                    ]);
                }
                $columnas[] = $nivel;
            }
            DB::commit();
            return response()->json($columnas);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
