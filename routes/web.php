<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\VentasController;

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
    return redirect('/admin');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    // Compras
    Route::resource('compras', ComprasController::class);
    Route::get('compras/ajax/list', [ComprasController::class, 'list']);
    // Ventas
    Route::resource('ventas', VentasController::class);
    Route::get('ventas/ajax/list', [VentasController::class, 'list']);
    Route::post('ventas/pago/store', [VentasController::class, 'pago_store'])->name('ventas.pago.store');
});
