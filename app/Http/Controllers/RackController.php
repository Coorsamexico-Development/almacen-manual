<?php

namespace App\Http\Controllers;

use App\Models\rack;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class RackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Racks/RacksIndex', [
            'racks' => fn () => rack::get(),
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'cantidad' => ['required', 'numeric'], // unicamente el el componente vue tiene el nombre name
        ]);

        try {
            DB::beginTransaction();

            $totalCreate = (int) $validate['cantidad'];
            $totalRacks = Rack::selectRaw('count(id) as total')->first()->total;

            for ($i = 1; $i <= $totalCreate; $i++) {
                $newRack['name'] = 'Rack ' . $totalRacks + $i;
                $rack = Rack::create($newRack);
            }
            DB::commit();
            return redirect()->back();
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
     * @param  \App\Models\rack  $rack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rack $rack)
    {
        //
    }
}
