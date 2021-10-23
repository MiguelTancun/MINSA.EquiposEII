<?php

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

Route::middleware('jwt.verify')->group(function () {
   
    Route::post('logout', 'AuthController@logout');
    Route::prefix('equipo')->group(function () {
        Route::get('all', 'EquipoController@getEquipo');
        Route::delete('delete/{equipo}', 'EquipoController@delete');
        Route::delete('all-delete/{equipos}', 'EquipoController@allDelete');
        Route::get('export/{equipos}', 'EquipoController@export');
        Route::post('save-relation', 'EquipoController@saveRelation');
    });

    Route::prefix('centro')->group(function () {
        Route::get('all', 'CentroController@getCentro');
    });

    Route::prefix('usuario')->group(function () {
        Route::get('all', 'AuthController@getUsuario');
    });
});
Route::get('me', 'AuthController@me');
Route::post('login', 'AuthController@login');