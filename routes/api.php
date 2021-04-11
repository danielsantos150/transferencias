<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CarteiraController;
use App\Http\Controllers\TransferenciaController;
use Illuminate\Http\Request;

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
    Route::post('/auth/registrar', [AuthController::class, 'registrar']);

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/login', function () {
        return response()->json(['code' => 403, 'status' => 'Error', 'message' => 'É obrigatório informar um Authorization Bearer válido para realizar a requisição.']);
    })->name('login');

    Route::get('/', function () {
        return response()->json(['code' => 200, 'status' => 'Conectado', 'message' => 'API de Transferências']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::get('/me', function(Request $request) {
            return auth()->user();
        });

        Route::resource('carteira', CarteiraController::class);

        Route::resource('transferencia', TransferenciaController::class);
    });
});
