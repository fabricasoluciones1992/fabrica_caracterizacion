<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BienestarActivitiesController extends Controller
{
    public function index($proj_id, $use_id)
{
    $bienestarActivity = BienestarActivity::select();

    foreach ($bienestarActivity as $activity) {
        $occupiedQuotas = DB::table('assistances')
            ->where('ass_status', 1)
            ->where('bie_act_id', $activity->bie_act_id)
            ->count();

        $activity->occupied_quotas = $occupiedQuotas;
    }

    Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table", 4, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $bienestarActivity
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
    $bienestarActivity = BienestarActivity::find($id);

    if (empty($bienestarActivity)) {
        return response()->json([
            'status' => false,
            'message' => 'The requested bienestar activity was not found.'
        ], 404);
    }

    $occupiedQuotas = DB::table('assistances')
        ->where('ass_status', 1)
        ->where('bie_act_id', $id)
        ->count();

    $bienestarActivity->occupied_quotas = $occupiedQuotas;

    Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table", 4, $proj_id, $use_id);

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
                Controller::NewRegisterTrigger("An update was made in the Bienestar Activities table", 1, $proj_id, $use_id);

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
