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
use App\Http\Controllers\EnfermeriaInscriptionsController;
use App\Http\Controllers\FactorsController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\GymAssitancesController;
use App\Http\Controllers\GymInscriptionsController;
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

Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('actions', ActionsController::class)->names('actions')->parameter('', 'actions');
    Route::resource('allergies', AllergiesController::class)->names('allergies')->parameter('', 'allergies');
    Route::resource('allergy/histories', AllergyHistoriesController::class)->names('allergy.histories')->parameter('', 'allergy_histories');
    Route::resource('assistances', AssistancesController::class)->names('assistences')->parameter('', 'assistences');
    Route::resource('bienestar/activities/types', BienestarActivityTypesController::class)->names('bienestar.activities.types')->parameter('', 'bienestar_activities_types');
    Route::resource('bienestar/activity', BienestarActivitiesController::class)->names('bienestar.activities')->parameter('', 'bienestar_activities');
    Route::resource('consultation', ConsultationController::class)->names('consultation')->parameter('', 'consultation');
    Route::resource('diseases', DiseasesController::class)->names('diseases')->parameter('', 'diseases');
    Route::delete('/destroyAR/{id}', [AssistancesController::class, 'destroyAR'])->name('destroyAR');
    Route::resource('enfermeria/inscriptions', EnfermeriaInscriptionsController::class)->names('enfermeria.inscription')->parameter('', 'enfermeria.inscription');
    Route::get('filtredforTSolicitud/{sol_typ_id}/{sol_typ_name}', [PermanencesController::class, 'filtredforTSolicitud'])->name('filtredforTSolicitud');
    Route::get('filtredPesolicitud/{id}', [SolicitudesController::class, 'filtredPesolicitud'])->name('filtredPesolicitud');
    Route::get('filtredPsolicitud/{id}', [PermanencesController::class, 'filtredPsolicitud'])->name('filtredPsolicitud');
    Route::get('filtreduser/{id}/{rea_typ_type?}', [SolicitudesController::class, 'filtreduser'])->name('filtreduser');
    Route::get('filtreduserP/{id}', [BienestarActivitiesController::class, 'filtreduserP'])->name('filtreduserP');
    Route::get('last/disease/{id}', [EnfermeriaInscriptionsController::class, 'lastDisease'])->name('last_disease');
    Route::resource('medical/histories', MedicalHistoriesController::class)->names('medical.histories')->parameter('', 'medical_histories');
    Route::resource('monetary/states', MonetaryStatesController::class)->names('monetary.states')->parameter('', 'monetary_states');
    Route::get('persons/filtred/{id}/{docTypeId}', [Controller::class, 'filtredforDocument'])->name('filtredforDocument');
    Route::resource('permanences', PermanencesController::class)->names('permanences')->parameter('', 'permanences');
    Route::post('/reports', [Controller::class, 'reports'])->name('reports');
    Route::post('/reports/individual', [Controller::class, 'reportsIndi'])->name('reports');
    Route::resource('reasons/types', ReasonsTypeController::class)->names('reasons.types')->parameter('', 'reasons_types');
    Route::resource('solicitudes', SolicitudesController::class)->names('solicitudes')->parameter('', 'solicitudes');
    Route::get('solicitudesFiltred/{column}/{data}', [SolicitudesController::class, 'filtredforSolicitudes'])->name('filtredforSolicitudes');
    Route::get('/student/activities/{code}', [Controller::class, 'viewStudentBie'])->name('student.viewStudentBie');
    Route::get('/student/medical/{code}', [Controller::class, 'viewStudentMed'])->name('student.viewStudentMed');
    Route::get('/student/solicitudes/{code}', [Controller::class, 'viewStudentSol'])->name('student.viewStudentSol');
    Route::get('/students/{id}', [Controller::class, 'student'])->name('studentById');
    Route::post('upload/assistances', [AssistancesController::class, 'uploadFile'])->name('upload.assistances');
    Route::resource('gym/assistances', GymAssitancesController::class)->names('gym.assistances')->parameter('', 'gym_assistances');
    Route::resource('gym/inscriptions', GymInscriptionsController::class)->names('gym.inscriptions')->parameter('', 'gym_inscriptions');
    Route::resource('type/solicitudes', SolicitudesTypesController::class)->names('type.solicitudes')->parameter('', 'type_solicitudes');
    Route::get('/documentTypeFilter', [Controller::class, 'docsTypesId'])->name('docs_types_id');
});

Route::post('/login', [Controller::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

