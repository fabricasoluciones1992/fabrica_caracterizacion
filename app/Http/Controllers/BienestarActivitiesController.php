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
    public function index()
{
    $bienestarActivities = BienestarActivity::select();

    foreach ($bienestarActivities as $activity) {
        $activity->quotas = BienestarActivity::countQuotas($activity->bie_act_id);
        $activity->total_assistances = BienestarActivity::countAssitances($activity->bie_act_id);
        
        $assistancesStudents = DB::table('viewAssitances AS vA')
                                ->select('vA.*')
                                ->where('vA.bie_act_id', $activity->bie_act_id)
                                ->get();

        foreach ($assistancesStudents as $assistance) {
            $assistance->last_enrollment = BienestarActivity::lastEnrollment($assistance->stu_id);
        }

        $activity->assistances = $assistancesStudents;
    }

    return response()->json([
        'status' => true,
        'data' => $bienestarActivities
    ], 200);
}




public function store(Request $request)
{
    if ($request->acc_administrator == 1) {

        $rules = [
            'bie_act_date' =>'required|date',
            'bie_act_quotas' => 'required|numeric|min:1',
            'bie_act_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9ÑÁÉÍÓÚÜáéíóúü\s]+$/',
            'bie_act_typ_id' =>'required|exists:bienestar_activity_types|numeric',
            'bie_act_hour' => 'required|date_format:H:i'
        ];

        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }

        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');
        $activityDate = $request->bie_act_date;
        $activityHour = $request->bie_act_hour;

        if ($activityDate == $currentDate && $activityHour < $currentTime && ($activityHour < '08:00' || $activityHour > '19:00')) {
            return response()->json([
                'status' => false,
                'message' => 'The activity hour must be after the current time for today.'
            ]);
        }

        if ($activityDate != $currentDate && ($activityHour < '08:00' || $activityHour > '19:00')) {
            return response()->json([
                'status' => false,
                'message' => 'The activity hour must be between 08:00 and 19:00 for dates other than today.'
            ]);
        }

        $existingActivity = BienestarActivity::where('bie_act_date', $request->bie_act_date)
            ->where('bie_act_quotas', $request->bie_act_quotas)
            ->where('bie_act_name', $request->bie_act_name)
            ->where('bie_act_typ_id', $request->bie_act_typ_id)
            ->where('bie_act_hour', $request->bie_act_hour)
            ->first();

        if ($existingActivity) {
            return response()->json([
                'status' => false,
                'message' => 'A bienestar activity with the same characteristics already exists.'
            ], 409);
        }

        $bienestarActivity = new BienestarActivity($request->input());
        $bienestarActivity->bie_act_status = 1;
        $bienestarActivity->save();
        Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities table '$bienestarActivity->bie_act_id'", 3, $request->use_id);

        return response()->json([
            'status' => True,
            'message' => "The bienestar activity has been created successfully.",
        ], 200);
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This action can only be performed by active administrators.'
        ], 403); 
    }
}


   
    public function show($id)
    {
        $bienestarActivity = BienestarActivity::search($id);
    
        if (empty($bienestarActivity)) {
            return response()->json([
                'status' => false,
                'message' => 'The requested bienestar activity was not found.'
            ], 404);
        }
    
        $bienestarActivity->quotas = BienestarActivity::countQuotas($bienestarActivity->bie_act_id);
        $bienestarActivity->total_assistances = BienestarActivity::countAssitances($bienestarActivity->bie_act_id);
        $assistancesStudents = DB::table('viewAssitances AS vA')
        ->select('vA.*')
        ->where('vA.bie_act_id', $bienestarActivity->bie_act_id)
        ->get();

        $bienestarActivity->assistances = $assistancesStudents;
        foreach ($assistancesStudents as $assistance) {
            $assistance->last_enrollment = BienestarActivity::lastEnrollment($assistance->stu_id);
        }
    
        return response()->json([
            'status' => true,
            'data' => $bienestarActivity
        ]);
    }
    


public function update(Request $request, $id)
{

    $bienestarActivity = BienestarActivity::find($id);

    if ($request->acc_administrator == 1) {
        if ($bienestarActivity == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched bienestar activity was not found']
            ], 400);
        } else {
            $rules = [

                'bie_act_typ_id' => 'required|exists:bienestar_activity_types|numeric',
                'bie_act_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9ÑÁÉÍÓÚÜáéíóúü\s]+$/',
                'bie_act_quotas' => 'required|numeric|min:1',
                'bie_act_date' => 'required|date',
                'bie_act_hour' => 'required',
            ];
            

            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->bie_act_name, 'bienestar_activities', 'bie_act_name', 'bie_act_id', $id);

            if ($validator->fails()||$validate==0) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                            $occupiedQuotas = DB::table('assistances')
                    ->where('bie_act_id', $id)
                    ->count();
                
                if ($request->bie_act_quotas < $occupiedQuotas) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Cannot update to full quotas. There are currently ' . $occupiedQuotas . ' pre-registrations.'
                    ]);
                }
                $currentDate = now()->toDateString();
            
                $currentTime = now()->format('H:i');
                $activityDate = $request->bie_act_date;
                $activityHour = $request->bie_act_hour;
        
                if ($activityDate == $currentDate && $activityHour < $currentTime && ($activityHour < '08:00' || $activityHour > '19:00')) {//changes
                    return response()->json([
                        'status' => false,
                        'message' => 'The activity hour must be after the current time for today.'
                    ]);
                }
        
                if ($activityDate != $currentDate && ($activityHour < '08:00' || $activityHour > '19:00')) {
                    return response()->json([
                        'status' => false,
                        'message' => 'The activity hour must be between 08:00 and 19:00 for dates other than today.'
                    ]);
                }
                $bienestarActivity->bie_act_typ_id = $request->bie_act_typ_id;
                $bienestarActivity->bie_act_name = $request->bie_act_name;
                $bienestarActivity->bie_act_quotas = $request->bie_act_quotas;
                $bienestarActivity->bie_act_date = $request->bie_act_date;
                $bienestarActivity->bie_act_hour = $request->bie_act_hour;
                $bienestarActivity->save();
                Controller::NewRegisterTrigger("An update was made in the Bienestar Activities table", 4,$request->use_id);

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

    public function destroy(Request $request,$id)
    {
        $bienestarActivity = BienestarActivity::find($id);
        $newBi=($bienestarActivity->bie_act_status==1)?0:1;
                $bienestarActivity->bie_act_status =$newBi;
                $bienestarActivity->save();
                Controller::NewRegisterTrigger("An change status was made in the bienestar activity type table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested bienestar activity type has been change successfully'
                ]);
                
            
    }
    public function filtreduserP($id)
{
    try {
        $solicitudes = BienestarActivity::findByUse($id);
        
        
        return response()->json([
            'status' => true,
            'data' => $solicitudes
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}
    
   
}
