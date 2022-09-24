<?php

namespace App\Http\Controllers;

use App\Exports\ExampleEntradaExport;
use App\Imports\OrdenEntradaImport;
use App\Models\ordenes_entrada;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class OrdenesEntradaController extends Controller
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
    public function store(Request $request)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ordenes_entrada  $ordenes_entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ordenes_entrada $ordenes_entrada)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ordenes_entrada  $ordenes_entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy(ordenes_entrada $ordenes_entrada)
    {
        //
    }

    /**
     * Download ejemplo de importacion de entradas skus
     *
     */
    public function exportExample()
    {
        try {
            return Excel::download(new ExampleEntradaExport, 'entradas_example.xlsx');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->download(public_path("/images/errors/error_file.png"));
        }
    }


    /**
     *  Importacion de entradas skus
     *
     */
    public function importEntradas(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:xlsx']
        ]);
        try {
            $entradasImport = new OrdenEntradaImport(Auth::id());
            Excel::import($entradasImport, $request->file('file'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw ValidationException::withMessages([
                'message' => $e->getMessage()
            ]);
        }
        if (count($entradasImport->errors) > 0) {
            throw ValidationException::withMessages(
                $entradasImport->errors
            );
        }
        return redirect()->back();
    }
}
