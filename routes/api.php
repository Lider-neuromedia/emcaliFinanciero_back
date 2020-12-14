<?php

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    // Autenticación - todas estan protegidas por token menos login.
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
});

// Rutas protegidas por token.
Route::group(['middleware' => 'auth:api'], function () {
    // Usuarios
    Route::get('users', 'ConfigurationController@getUsers');
    Route::post('users', 'ConfigurationController@registerUser');
    Route::post('users/{id}', 'ConfigurationController@updateUser');
    Route::get('roles', 'ConfigurationController@roles');
    Route::post('delete/{id}', 'ConfigurationController@destroyUser');

    // Carga
    Route::post('uploadFile', 'ConfigurationController@uploadFile');
});

Route::get('download-template/{module}', 'ConfigurationController@downloadTemplate');
// Ruta de error de autenticación
Route::get('error', function (){
    return response()->json([
        'response' => 'error',
        'message' => 'Unauthorized'
    ], 403);
})->name('error');
