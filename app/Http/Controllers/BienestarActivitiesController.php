<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use App\Models\BienestarActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BienestarActivitiesController extends Controller
{
    public function index($proj_id, $use_id)//falta get 
{
    $bienestarActivities = BienestarActivity::getbienestar_news();
    $assistances = Assistance::select();

    foreach ($bienestarActivities as $activity) {
        $activity->quotas = BienestarActivity::countQuotas($activity->bie_act_id);
        $assistancesStudents = array();
        foreach ($assistances as $assistance) {
            $date = date('Y-m-d');
            if ($assistance->ass_status == 1 || $activity->bie_act_date < $date) {
                $assistance->ass_status = 'ASISTIO';
            }else if($assistance->ass_status == 0 || $activity->bie_act_date > $date){
                $assistance->ass_status = 'NO ASISTIO';
            }else{
                $assistance->ass_status = 'PRE-REGISTRADO';
            }
            if ($assistance->bie_act_id == $activity->bie_act_id) {
                array_push($assistancesStudents,$assistance);
            }
        }
        $activity->assistances = $assistancesStudents;
    }
    return response()->json([
        'status' => true,
        'data' => $bienestarActivities
    ], 200);
}



    public function store($proj_id,$use_id,Request $request)
    {
            if ($request->acc_administrator == 1) {

                $rules = [
                    'bie_act_date' =>'required|date',
                    'bie_act_quotas' => 'required|numeric',
                    'bie_act_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
                    'bie_act_typ_id' =>'required|numeric',
                    'bie_act_hour' => 'required',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $bienestarActivity = new BienestarActivity($request->input());
                    $bienestarActivity->bie_act_status=1;
                    $bienestarActivity->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities table'$bienestarActivity->bie_act_id'",3,$use_id);
                    $id = $bienestarActivity->bie_act_id;
                    $bienestar_news=BienestarActivitiesController::Getbienestar_news($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The bienestar activity has been created successfully.",
                        'data' => $bienestar_news

                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }

    public function Getbienestar_news($id)
    {
        $bie_act_id = $id;
        $bienestar_news = DB::table('bienestar_news')
            ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
            ->select('bie_new_date', 'persons.per_name')
            ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Bienestar Activities table\'$bie_act_id\''")
            ->get();
    
        if ($bienestar_news->count() > 0) {
            return $bienestar_news[0];
        } else {
            return null;
        }
    }
    public function show($proj_id, $use_id, $id)
{
    $bienestarActivity = BienestarActivity::find($id);
    $bienestar_news = BienestarActivitiesController::Getbienestar_news($id);

    if (empty($bienestarActivity)) {
        return response()->json([
            'status' => false,
            'message' => 'The requested bienestar activity was not found.'
        ], 404);
    }

    $occupiedQuotas = DB::table('assistances')
        ->select('assistances.*', 'persons.per_name')
        ->join('students', 'assistances.stu_id', '=', 'students.stu_id')
        ->leftJoin('persons', 'students.stu_code', '=', 'persons.per_document')
        ->where('assistances.ass_status', 1)
        ->where('assistances.bie_act_id', $id)
        ->get();

    $bienestarActivity->occupied_quotas = $occupiedQuotas->count();
<<<<<<< Updated upstream
    $bienestarActivity->person_names = $occupiedQuotas->pluck('per_name')->toArray();
    $bienestarActivity->new_date = $bienestar_news->bie_new_date;
    $bienestarActivity->createdBy = $bienestar_news->per_name;
=======
    $bienestarActivity->person_names = $occupiedQuotas->pluck('
    ')->toArray();

    Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table", 4, $proj_id, $use_id);
>>>>>>> Stashed changes

    return response()->json([
        'status' => true,
        'data' => $bienestarActivity
    ]);
}




public function update($proj_id, $use_id, Request $request, $id)
{
    $occupiedQuotas = DB::table('assistances')
        ->where('bie_act_id', $id)
        ->count();

    if ($request->bie_act_quotas <= $occupiedQuotas) {
        return response()->json([
            'status' => false,
            'message' => 'Cannot update to full quotas. There are currently ' . $occupiedQuotas . ' pre-registrations.'
        ], 400);
    }

    $bienestarActivity = BienestarActivity::find($id);

    if ($request->acc_administrator == 1) {
        $bienestarActivity = BienestarActivity::find($id);
        if ($bienestarActivity == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched bienestar activity was not found']
            ], 400);
        } else {
            $rules = [
                'bie_act_typ_id' => 'required|numeric',
                
                'bie_act_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
                'bie_act_quotas' => 'required|numeric',
                'bie_act_date' => 'required|date',
                'bie_act_hour' => 'required',
            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $bienestarActivity->bie_act_typ_id = $request->bie_act_typ_id;
                $bienestarActivity->bie_act_name = $request->bie_act_name;
                $bienestarActivity->bie_act_quotas = $request->bie_act_quotas;
                $bienestarActivity->bie_act_date = $request->bie_act_date;
                $bienestarActivity->bie_act_hour = $request->bie_act_hour;
                $bienestarActivity->save();
                Controller::NewRegisterTrigger("An update was made in the Bienestar Activities table", 4,$use_id);

                return response()->json([
                    'status' => true,
                    'message' => "The bienestar activity has been updated."
                ], 200);
            }
        }
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This action can only be performed by active administrators.'
        ], 403);
    }
}

    public function destroy($proj_id,$use_id, $id)
    {
        $bienestarActivity = BienestarActivity::find($id);
        
            if ($bienestarActivity->bie_act_status == 1){
                $bienestarActivity->bie_act_status = 0;
                $bienestarActivity->save();
                Controller::NewRegisterTrigger("An delete was made in the bienestar activity type table",2,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested bienestar activity type has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested bienestar activity type has already been disabled previously'
                ]);
            } 
    }
}
