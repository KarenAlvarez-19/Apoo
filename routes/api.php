<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Events\eventsController;
use App\Http\Controllers\Users\usersController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getevents',[eventsController::class,"getEvents"]); //mostrar registros
Route::post('/postevents', [eventsController::class,"postEvents"]); //insertar registros
Route::put('/putevents',[eventsController::class,"putEvents"]); //editar registros
Route::delete('/deleteevent',[eventsController::class,"deleteEvents"]); //eliminar registros

Route::get('/getUsers', [usersController::class, 'getUsers']);
Route::post('/postUsers', [usersController::class, 'postUsers']);
Route::put('/putUsers', [usersController::class, 'putUsers']);
Route::delete('/deleteUsers', [usersController::class, 'deleteUsers']);