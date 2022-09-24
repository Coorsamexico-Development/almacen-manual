<?php

use App\Http\Controllers\ColumnaController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\RackController;
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
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::apiResource('racks', RackController::class)->only('index', 'store');
    Route::name('racks.')->group(function () {

        Route::apiResource('racks/{rack}/nivels', NivelController::class)->only('index', 'store');
        Route::apiResource('racks/{rack}/columns', ColumnaController::class)->only('index', 'store');
    });
});
