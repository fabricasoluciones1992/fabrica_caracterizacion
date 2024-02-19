<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BienestarActivitiesController extends Controller
{
    public function index($proj_id, $use_id)
{
    $bienestarActivity = DB::select("
        SELECT ba.bie_act_id, ba.bie_act_status, ba.bie_act_date, ba.bie_act_quotas, ba.bie_act_name, bat.bie_act_typ_name 
        FROM bienestar_activities ba
        INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
    ");

    foreach ($bienestarActivity as $activity) {
        $availableQuotas = DB::table('assistances')
            ->where('ass_assistance', 1)
            ->where('bie_act_id', $activity->bie_act_id)
            ->count();

        $activity->remaining_quotas = $activity->bie_act_quotas - $availableQuotas;
    }

    Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table", 4, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $bienestarActivity
    ], 200);
}

    public function store($proj_id,$use_id,Request $request)
    {
        $availableQuotas = DB::table('assistances')
        ->where('ass_assistance', 1)
        ->where('bie_act_id', $request->bie_act_id)
        ->count();

    $totalQuotas = $request->bie_act_quotas;

    $remainingQuotas = $totalQuotas - $availableQuotas;

    if ($request->bie_act_quotas > $remainingQuotas) {
        return response()->json([
            'status' => false,
            'message' => 'There are not enough quotas available for this activity.'
        ], 400);
    }
            if ($request->acc_administrator == 1) {
                $rules = [
                    'bie_act_date' =>'required|date',
                    'bie_act_quotas' => 'required|integer',
                    'bie_act_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
                    'bie_act_typ_id' =>'required|integer|max:1'
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
                    Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities table",3,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The bienestar activity has been created successfully."
                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }

    public function show($proj_id, $use_id, $id)
{
    $bienestarActivity = DB::select("
        SELECT ba.bie_act_id, ba.bie_act_status, ba.bie_act_date, ba.bie_act_quotas, ba.bie_act_name, bat.bie_act_typ_name 
        FROM bienestar_activities ba
        INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
        WHERE ba.bie_act_id = $id
    ");

    if (empty($bienestarActivity)) {
        return response()->json([
            'status' => false,
            'message' => 'The requested bienestar activity was not found.'
        ], 404);
    }

    $availableQuotas = DB::table('assistances')
        ->where('ass_assistance', 1)
        ->where('bie_act_id', $id)
        ->count();

    $totalQuotas = $bienestarActivity[0]->bie_act_quotas;

    $remainingQuotas = $totalQuotas - $availableQuotas;

    $bienestarActivity[0]->remaining_quotas = $remainingQuotas;

    Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table", 4, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $bienestarActivity
    ]);
}

    public function update($proj_id,$use_id,Request $request, $id)
    {
            $availableQuotas = DB::table('assistances')
            ->where('ass_assistance', 1)
            ->where('bie_act_id', $id)
            ->count();

        $totalQuotas = $request->bie_act_quotas;

        $remainingQuotas = $totalQuotas - $availableQuotas;

        if ($request->bie_act_quotas > $remainingQuotas) {
            return response()->json([
                'status' => false,
                'message' => 'There are not enough quotas available for this activity.'
            ], 400);
        }
            $bienestarActivity = BienestarActivity::find($id);
            
            if ($request->acc_administrator == 1) {
                $bienestarActivity = BienestarActivity::find($id);
                if ($bienestarActivity == null) {
                    return response()->json([
                        'status' => false,
                        'data' => ['message' => 'The searched bienestar activity was not found']
                    ],400);
                } else {
                    $rules = [
                        'bie_act_date' =>'required|date',
                    'bie_act_quotas' => 'required|integer',
                    'bie_act_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9ÁÉÍÓÚÜáéíóúü\s]+$/',
                    'bie_act_typ_id' =>'required|integer|max:1'
                    ];
                    $validator = Validator::make($request->input(), $rules);
                    if ($validator->fails()) {
                        return response()->json()([
                            'status' => False,
                            'message' => $validator->errors()->all()
                        ]);
                    } else {
                        $bienestarActivity->bie_act_date = $request->bie_act_date;
                        $bienestarActivity->bie_act_quotas = $request->bie_act_quotas;
                        $bienestarActivity->bie_act_name = $request->bie_act_name;
                        $bienestarActivity->bie_act_typ_id = $request->bie_act_typ_id;
                        $bienestarActivity->save();
                        Controller::NewRegisterTrigger("An update was made in the Bienestar Activities table",1,$proj_id, $use_id);

                        return response()->json([
                            'status' => True,
                            'message' => "The bienestar activity has been updated."
                        ],200);
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
                Controller::NewRegisterTrigger("An delete was made in the bienestar activity type table",2,$proj_id,$use_id);
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
