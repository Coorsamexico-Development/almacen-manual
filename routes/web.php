<?php

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

    Route::get('racks', function () {
        return Inertia::render('Racks/RacksIndex');
    })->name('racks.index');
    Route::post('racks', function () {
        return Redirect::back();
    })->name('racks.store');
    Route::get('racks/{rack}/filas', function (Int $rack) {
        return [
            ['id' => 1, 'name' => 'Fila 1'],
            ['id' => 2, 'name' => 'Fila 2'],
        ];
    })->name('racks.filas.index');
    Route::get('racks/{rack}/columnas', function (Int $rack) {
        return [
            ['id' => 1, 'name' => 'Columna 1'],
            ['id' => 2, 'name' => 'Columna 2'],
        ];
    })->name('racks.columnas.index');
});
