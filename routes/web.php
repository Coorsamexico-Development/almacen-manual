<?php

use App\Http\Controllers\ColumnaController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\EntradasRealController;
use App\Http\Controllers\FolioController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\OcController;
use App\Http\Controllers\OrdenesEntradaController;
use App\Http\Controllers\PosicionController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\TarimaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canResetPassword' => Route::has('password.reset'),
    ]);
})->middleware('guest');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    //Rutas users
    Route::apiResource('users', UserController::class)->except('show');
    // Rutas Racks
    Route::apiResource('racks', RackController::class)->only('index', 'store');
    Route::name('racks.')->group(function () {

        Route::apiResource('racks/{rack}/nivels', NivelController::class)->only('index', 'store');
        Route::apiResource('racks/{rack}/columns', ColumnaController::class)->only('index', 'store');
    });
    // Rutas Ordendes de Entrada
    Route::apiResource('ordenes-entrada', OrdenesEntradaController::class)->only('index', 'store');
    // Entarimado
    Route::apiResource('tarimas', TarimaController::class)->only('index', 'store');
    Route::post('tarimas/{tarima}/entrdas-productos', [TarimaController::class, 'storeEntradaProducto'])->name('tarimas.entradas-productos.store');

    Route::delete('tarimas/{tarima}/entrdas-productos', [TarimaController::class, 'destroyEntradaProducto'])->name('tarimas.entradas-productos.destroy');
    Route::get('tarimas/{tarima}/productos', [TarimaController::class, 'productoIndex'])->name('tarimas.productos.index');
    Route::get('entradas/{tarima}', [EntradaController::class, 'index'])->name('entradas.index');
    //Posiciones
    Route::get('posiciones-disponibles', [PosicionController::class, 'indexDisponibles'])->name('posiciones-disponible.index');
    Route::put('tarimas/{tarima}/posiciones', [TarimaController::class, 'updatePosicion'])->name('tarimas.posiciones.update');

    // Ruta folios en una Orden de Entrada
    Route::get('ordenes-entrada/{ordenEntrada}/folios', [FolioController::class, 'index'])->name('ordenes-entradas.folios.index');
    // Ruta productos  en un folio
    Route::get('folios/{folio}/entradas', [FolioController::class, 'indexEntradas'])->name('folios.entradas.index');
    Route::post('entradas-folios/{entrada}/entradas-reales', [EntradasRealController::class, 'store'])->name('entradas.entradas-reales.store');


    // Import Entradas
    Route::get('ordenes-entradas/export/example', [OrdenesEntradaController::class, 'exportExample'])->name('ordenes-entradas.export.example');
    Route::post('ordenes-entradas/import', [OrdenesEntradaController::class, 'importEntradas'])->name('ordenes-entradas.import');

    // Ocs
    Route::apiResource('ocs', OcController::class)->only('index');
    Route::get('ocs/export/example', [OcController::class, 'exportExample'])->name('ocs.export.example');
    Route::post('ocs/import', [OcController::class, 'import'])->name('ocs.import');
    Route::get('ocs/{oc}/salidas', [OcController::class, 'indexSalidas'])->name('ocs.salidas.index');
});
