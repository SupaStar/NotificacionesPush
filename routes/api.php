<?php

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/notificacion', [NotificacionesController::class, 'notificacion']);
Route::post('/notificacionGlobal',[NotificacionesController::class, 'notificacionGlobal']);
Route::post('/registro', [UsuarioController::class, 'registrar']);
Route::post('/login', [UsuarioController::class, 'login']);