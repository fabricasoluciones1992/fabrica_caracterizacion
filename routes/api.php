<?php

use App\Http\Controllers\ActionsController;
use App\Http\Controllers\AllergiesController;
use App\Http\Controllers\AllergyHistoriesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssistancesController;
use App\Http\Controllers\BienestarActivitiesController;
use App\Http\Controllers\BienestarActivityTypesController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DiseasesController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\FactorsController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\GymAssitancesController;
use App\Http\Controllers\GymInscriptionsController;
use App\Http\Controllers\HistoryConsultationsController;
use App\Http\Controllers\MedicalHistoriesController;
use App\Http\Controllers\MonetaryStatesController;
use App\Http\Controllers\PermanencesController;
use App\Http\Controllers\ReasonsController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\solicitudesTypesController;
use App\Models\Consultation;
use App\Models\Gym_assitance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

define("URL", "/{proj_id}/{use_id}/");

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

// Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('actions'.URL, ActionsController::class)->names('actions')->parameter('', 'actions');
    Route::resource('reasons'.URL, ReasonsController::class)->names('reasons')->parameter('', 'reasons');
    Route::resource('allergies'.URL, AllergiesController::class)->names('allergies')->parameter('', 'allergies');
    Route::resource('diseases'.URL, DiseasesController::class)->names('diseases')->parameter('', 'diseases');
    Route::resource('medical/histories'.URL, MedicalHistoriesController::class)->names('medical.histories')->parameter('', 'medical_histories');
    Route::resource('allergy/histories'.URL, AllergyHistoriesController::class)->names('allergy.histories')->parameter('', 'allergy_histories');
    
    
    
    Route::resource('type/solicitudes'.URL, SolicitudesTypesController::class)->names('type.solicitudes')->parameter('', 'type_solicitudes');
    Route::resource('permanences'.URL, PermanencesController::class)->names('permanences')->parameter('', 'permanences');
    Route::resource('assistences'.URL, AssistancesController::class)->names('assistences')->parameter('', 'assistences');
    
    
    Route::resource('solicitudes'.URL, SolicitudesController::class)->names('solicitudes')->parameter('', 'solicitudes');
    
    Route::resource('bienestar/activities/types'.URL, BienestarActivityTypesController::class)->names('bienestar.activities.types')->parameter('', 'bienestar_activities_types');
    Route::resource('consultation'.URL, ConsultationController::class)->names('consultation')->parameter('', 'consultation');
    Route::resource('history/consultation'.URL, HistoryConsultationsController::class)->names('history.consultation')->parameter('', 'history_consultation');
    Route::resource('bienestar/activities'.URL, BienestarActivitiesController::class)->names('bienestar.activities')->parameter('', 'bienestar_activities');
    Route::resource('assistances'.URL, AssistancesController::class)->names('assistances')->parameter('', 'assistances');
    Route::resource('gym/assistances'.URL, GymAssitancesController::class)->names('gym.assistances')->parameter('', 'gym_assistances');
    Route::resource('gym/inscriptions'.URL, GymInscriptionsController::class)->names('gym.inscriptions')->parameter('', 'gym_inscriptions');
    
    
    Route::resource('monetary/states'.URL, MonetaryStatesController::class)->names('monetary.states')->parameter('', 'monetary_states');
    
    Route::resource('factors'.URL, FactorsController::class)->names('factors')->parameter('', 'factors');
    
    Route::get('/student/medical/{code}', [Controller::class, 'viewStudentMed'])->name('student.viewStudentMed');
    Route::get('/student/solicitudes/{code}', [Controller::class, 'viewStudentSol'])->name('student.viewStudentSol');
    Route::get('/student/activities/{code}', [Controller::class, 'viewStudentBie'])->name('student.viewStudentBie');
    
    
    Route::get('/genders', [AuthController::class, 'genders']);
    
    
//  });
Route::post('/login', [Controller::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

