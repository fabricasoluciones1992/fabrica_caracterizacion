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
use App\Http\Controllers\FactorsController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\GymAssitancesController;
use App\Http\Controllers\GymInscriptionsController;
use App\Http\Controllers\HistoryConsultationsController;
use App\Http\Controllers\MedicalHistoriesController;
use App\Http\Controllers\MonetaryStatesController;
use App\Http\Controllers\PermanencesController;
use App\Http\Controllers\ReasonsTypeController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\solicitudesTypesController;
use App\Models\Consultation;
use App\Models\Gym_assitance;
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

Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('actions', ActionsController::class)->names('actions')->parameter('', 'actions');
    Route::resource('reasons/types', ReasonsTypeController::class)->names('reasons.types')->parameter('', 'reasons_types');
    Route::resource('allergies', AllergiesController::class)->names('allergies')->parameter('', 'allergies');
    Route::resource('diseases', DiseasesController::class)->names('diseases')->parameter('', 'diseases');
    Route::resource('medical/histories', MedicalHistoriesController::class)->names('medical.histories')->parameter('', 'medical_histories');
    Route::resource('allergy/histories', AllergyHistoriesController::class)->names('allergy.histories')->parameter('', 'allergy_histories');



    Route::resource('type/solicitudes', SolicitudesTypesController::class)->names('type.solicitudes')->parameter('', 'type_solicitudes');
    Route::resource('permanences', PermanencesController::class)->names('permanences')->parameter('', 'permanences');
    Route::resource('assistences', AssistancesController::class)->names('assistences')->parameter('', 'assistences');


    Route::resource('solicitudes', SolicitudesController::class)->names('solicitudes')->parameter('', 'solicitudes');

    Route::resource('bienestar/activities/types', BienestarActivityTypesController::class)->names('bienestar.activities.types')->parameter('', 'bienestar_activities_types');
    Route::resource('consultation', ConsultationController::class)->names('consultation')->parameter('', 'consultation');
    Route::resource('history/consultation', HistoryConsultationsController::class)->names('history.consultation')->parameter('', 'history_consultation');
    Route::resource('bienestar/activities', BienestarActivitiesController::class)->names('bienestar.activities')->parameter('', 'bienestar_activities');
    // Route::resource('assistances', AssistancesController::class)->names('assistances')->parameter('', 'assistances');
    Route::resource('gym/assistances', GymAssitancesController::class)->names('gym.assistances')->parameter('', 'gym_assistances');
    Route::resource('gym/inscriptions', GymInscriptionsController::class)->names('gym.inscriptions')->parameter('', 'gym_inscriptions');

    Route::resource('monetary/states', MonetaryStatesController::class)->names('monetary.states')->parameter('', 'monetary_states');


    Route::get('/student/medical/{code}', [Controller::class, 'viewStudentMed'])->name('student.viewStudentMed');
    Route::get('/student/solicitudes/{code}', [Controller::class, 'viewStudentSol'])->name('student.viewStudentSol');
    Route::get('/student/activities/{code}', [Controller::class, 'viewStudentBie'])->name('student.viewStudentBie');

    Route::get('persons/filtred/{id}/{docTypeId}', [Controller::class, 'filtredforDocument'])->name('filtredforDocument');


    Route::get('filtredforTSolicitud'.'{id}', [PermanencesController::class, 'filtredforTSolicitud'])->name('filtredforTSolicitud');

    Route::get('solicitudesFiltred'.'{column}/{data}', [SolicitudesController::class, 'filtredforSolicitudes'])->name('filtredforSolicitudes');
    Route::get('/students', [Controller::class, 'students'])->name('students');
    Route::get('/students'.'{id}', [Controller::class, 'student'])->name('studentById');
    Route::post('/reports', [Controller::class, 'reports'])->name('reports');
    Route::post('/reports/individual', [Controller::class, 'reportsIndi'])->name('reports');
    Route::get('/documentTypeFilter', [Controller::class, 'docsTypesId'])->name('docs_types_id');



 });

Route::post('/login', [Controller::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

