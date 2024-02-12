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

define("URL", "/{proj_id}/");

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
Route::resource('actions'.URL, ActionsController::class)->names('actions')->parameter('', 'actions');
Route::resource('types/solicitudes'.URL, solicitudesTypesController::class)->names('types/solicitudes')->parameter('', 'types/solicitudes');
Route::resource('permanences'.URL, PermanencesController::class)->names('permanences')->parameter('', 'permanences');

Route::resource('solicitudes'.URL, SolicitudesController::class)->names('solicitudes')->parameter('', 'solicitudes');

Route::resource('bienestarActTypes'.URL, BienestarActivityTypesController::class)->names('bienestarActTypes')->parameter('', 'bienestarActTypes');
Route::resource('bienestar/activities'.URL, BienestarActivitiesController::class)->names('bienestarActivities')->parameter('', 'bienestarActivities');
Route::resource('assistances'.URL, AssistancesController::class)->names('assistances')->parameter('', 'assistances');

Route::resource('monetaryStates'.URL, MonetaryStatesController::class)->names('monetaryStates')->parameter('', 'monetaryStates');

Route::resource('factors'.URL, FactorsController::class)->names('factors')->parameter('', 'factors');

Route::get('/student/medical/{code}', [Controller::class, 'viewStudentMed'])->name('student.viewStudentMed');
Route::get('/student/solicitudes/{code}', [Controller::class, 'viewStudentSol'])->name('student.viewStudentSol');
Route::get('/student/activities/{code}', [Controller::class, 'viewStudentBie'])->name('student.viewStudentBie');


Route::get('/genders', [AuthController::class, 'genders']);

Route::post('/login', [Controller::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

