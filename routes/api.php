<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CarteiraController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(array('prefix' => 'v1'), function()
{
    Route::get('/', function () {
        return response()->json(['code' => 200, 'status' => 'Conectado', 'mensagem' => 'API de Transferências']);
    });

    Route::resource('usuario', UsuarioController::class);

    Route::resource('carteira', CarteiraController::class);
});
