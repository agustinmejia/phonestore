<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ReportesController;

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
Route::get('login', function () {
    return redirect('admin/login');
})->name('login');

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
    // Productos
    Route::resource('productos', ProductosController::class);
    Route::get('productos/ajax/list', [ProductosController::class, 'list']);
    Route::get('productos/ajax/list/type', [ProductosController::class, 'list_group_type']);

    // Reportes
    Route::get('reportes/deudas', [ReportesController::class, 'index_deudas'])->name('index.deudas');
    Route::get('reportes/deudas/{dias}', [ReportesController::class, 'index_deudas_list']);
    Route::get('reportes/ventas', [ReportesController::class, 'index_ventas'])->name('index.ventas');
    Route::post('reportes/ventas/lista', [ReportesController::class, 'ventas_lista'])->name('ventas.lista');
});
