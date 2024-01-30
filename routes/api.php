<?php

use App\Http\Controllers\ActionsController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\GendersController;
use App\Http\Controllers\MonetaryStatesController;
use App\Http\Controllers\PermanencesController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\solicitudesTypesController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('actions', ActionsController::class)->names('actions');
Route::resource('TSolicitudes', solicitudesTypesController::class)->names('TSolicitudes');
Route::resource('permanences', PermanencesController::class)->names('permanences');

Route::resource('solicitudes', SolicitudesController::class)->names('solicitudes');

Route::resource('monetaryStates', MonetaryStatesController::class)->names('monetaryStates');


// Route::resource('genders', GendersController::class)->names('genders');
