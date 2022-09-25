<?php

namespace App\Http\Controllers;

use App\Models\entrada;
use App\Models\entradas_real;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EntradasRealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(entrada $entrada, Request $request)
    {
        $validateData = $request->validate([
            'cantidad' => ['required', 'numeric', 'min:1']
        ]);

        $totalAvance = $entrada->entradasReales()->selectRaw('ifnull(sum(cantidad), 0) as avance')->first()->avance;

        if ($totalAvance + $validateData['cantidad'] > $entrada->cantidad) {
            throw ValidationException::withMessages([
                'cantidad' => 'La cantidad supera a lo esperado'
            ]);
        } else {
            $entrada->entradasReales()->create([
                'cantidad' => $validateData['cantidad'],
                'disponible' => $validateData['cantidad'],
                'user_id' => Auth::id()
            ]);
        }
        return response()->json([
            'message' => 'guardado'
        ]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\entradas_real  $entradas_real
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, entradas_real $entradas_real)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\entradas_real  $entradas_real
     * @return \Illuminate\Http\Response
     */
    public function destroy(entradas_real $entradas_real)
    {
        //
    }
}
