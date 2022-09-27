<?php

namespace App\Http\Controllers;

use App\Exports\OcExampleExport;
use App\Imports\OcImport;
use App\Models\oc;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class OcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->validate([
            'direction' => 'in:desc,asc'
        ]);
        $ocs = oc::select(
            'ocs.id',
            'ocs.name',
            'ocs.fecha_entrega',
            'ocs.cliente_id',
            'clientes.name as cliente',
            'ocs.active',
        )
            ->join('clientes', 'ocs.cliente_id', '=', 'clientes.id');


        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $ocs->where('ocs.oc', 'like', '%' . $search . '%');
            $ocs->orWhere('ocs.fecha_entrega', 'like', '%' . $search . '%');
            $ocs->orWhere('clientes.name', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $ocs->orderBy(request('field'), request('direction'));
        } else {
            $ocs->orderBy('ocs.created_at', 'desc');
        }

        return Inertia::render('Ocs/OcsIndex', [
            'filters' => request(['search', 'field', 'direction']),
            'ocs' => $ocs->paginate(10)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\oc  $oc
     * @return \Illuminate\Http\Response
     */
    public function destroy(oc $oc)
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
            return Excel::download(new OcExampleExport, 'OC_IMPORT.xlsx');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->download(storage_path("app/public/errors/error_file.png"));
        }
    }

    /**
     *  Importacion de salidas skus
     *
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:xlsx'],
        ]);
        try {
            $ocImport = new OcImport(Auth::id());
            Excel::import($ocImport, $request->file('file'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw ValidationException::withMessages([
                'message' => $e->getMessage()
            ]);
        }
        if (count($ocImport->errors) > 0) {
            throw ValidationException::withMessages(
                $ocImport->errors
            );
        }
        return redirect()->back();
    }


    public function indexSalidas(oc $oc, Request $request)
    {
        $request->validate([
            'direction' => 'in:desc,asc'
        ]);
        $salidas = $oc->salidas()->select(
            'salidas.id',
            'salidas.producto_id',
            'productos.ean',
            'productos.name as producto',
            'salidas.solicitado',
            'salidas.surtido',
        )
            ->join('productos', 'salidas.producto_id', '=', 'productos.id');

        if (request()->has('search')) {
            $search =  strtr(request('search'), array("'" => "\\'", "%" => "\\%"));
            $salidas->where('productos.name', 'like', '%' . $search . '%')
                ->orWhere('productos.ean', 'like', '%' . $search . '%');
        }
        if (request()->has('field')) {
            $salidas->orderBy(request('field'), request('direction'));
        } else {
            $salidas->orderBy('salidas.created_at', 'desc');
        }
        return response()->json([
            'salidas' =>  $salidas->paginate(10),
            'filters' => request(['search', 'field', 'direction'])
        ]);
    }
}
