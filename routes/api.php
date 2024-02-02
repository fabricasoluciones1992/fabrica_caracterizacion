<?php

use App\Http\Controllers\ActionsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssistancesController;
use App\Http\Controllers\BienestarActivitiesController;
use App\Http\Controllers\BienestarActivityTypesController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\FactorsController;
use App\Http\Controllers\GenderController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::resource('actions', ActionsController::class)->names('actions');
Route::resource('TSolicitudes', solicitudesTypesController::class)->names('TSolicitudes');
Route::resource('permanences', PermanencesController::class)->names('permanences');

Route::resource('solicitudes', SolicitudesController::class)->names('solicitudes');

Route::resource('bienestarActTypes', BienestarActivityTypesController::class)->names('bienestarActTypes');
Route::resource('bienestarActivities', BienestarActivitiesController::class)->names('bienestarActivities');
Route::resource('assistances', AssistancesController::class)->names('assistances');

Route::resource('monetaryStates', MonetaryStatesController::class)->names('monetaryStates');

Route::resource('factors', FactorsController::class)->names('factors');


Route::get('/genders', [AuthController::class, 'genders']);

Route::post('/login', [Controller::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

