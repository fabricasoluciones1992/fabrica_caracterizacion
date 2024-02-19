<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BienestarActivitiesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        
            $bienestarActivity = DB::select("
            SELECT ba.bie_act_id, ba.bie_act_date, ba.bie_act_quotas,ba.bie_act_name, bat.bie_act_typ_name 
            FROM bienestar_activities ba
            INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
            ");
            Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table",4,$proj_id, $use_id);

            return response()->json([
                'status' => true,
                'data' => $bienestarActivity
            ],200);

        
    }

    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [
                    'bie_act_date' =>'required|date',
                    'bie_act_quotas' =>'string|max:25|regex:/^[A-ZÑ\s]+$/',
                    'bie_act_name' =>'required|string|max:255|/^[a-zA-Z0-9\s]+$/',
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

    public function show($proj_id,$use_id,$id)
    {
        
            $bienestarActivity = DB::select("
            SELECT ba.bie_act_id, ba.bie_act_date, ba.bie_act_quotas,ba.bie_act_name, bat.bie_act_typ_name 
            FROM bienestar_activities ba
            INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
            WHERE ba.bie_act_id = $id;
            ");
            if ($bienestarActivity == null) {
                return response()->json([
                'status' => false,
                    "data" => ['message' => 'The searched bienestar activity was not found']
                ],400);
            } else {
                Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table",4,$proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'data' => $bienestarActivity
                ]);
            }

        
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        
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
                        'bie_act_quotas' =>'string|max:25|regex:/^[A-ZÑ\s]+$/',
                        'bie_act_description' =>'string|max:255|/^[a-zA-Z0-9\s]+$/',
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
                        $bienestarActivity->bie_act_description = $request->bie_act_description;
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
    public function destroy($use_id,BienestarActivity $bienestarActivity)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ],400);
    }
}
