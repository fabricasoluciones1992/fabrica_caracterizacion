<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivity;
use App\Models\BienestarActivityTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BienestarActivityTypesController extends Controller
{
    public function index()
    {
        
            $bienestarActTypes = BienestarActivityTypes::all();

            return response()->json([
                'status' => true,
                'data' => $bienestarActTypes
            ],200);
        
    }
    public function store(Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [

                    'bie_act_typ_name' => 'required|string|min:1|max:55|unique:bienestar_activity_types|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $bienestarActType = new BienestarActivityTypes($request->input());
                    $bienestarActType->bie_act_typ_status=1;
                    $bienestarActType->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities types table'$bienestarActType->bie_act_typ_id'",3,$request->use_id);
                   
                    return response()->json([
                        'status' => true,
                        'message' => "The bienestar activity type '".$bienestarActType->bie_act_typ_name."' has been created successfully.",

                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }

    public function show($id)
    {
        
            $bienestarActType = BienestarActivity::category($id);

            if ($bienestarActType == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested bienestar activity type was not found']
                ]);
            } else {
                foreach ($bienestarActType as $activity) {
                    $activity->quotas = BienestarActivity::countQuotas($activity->bie_act_id);
                    $activity->total_assistances = BienestarActivity::countAssitances($activity->bie_act_id);
                    
                    
            
                }
            

                return response()->json([
                    'status' => true,
                    'data' => $bienestarActType
                ]);
            }
        
    }
    public function update(Request $request, $id)
    {
        
        $bienestarActTypes = BienestarActivityTypes::find($id);
 
            if ($request->acc_administrator == 1) {
                $bienestarActTypes = BienestarActivityTypes::find($id);
                if ($bienestarActTypes == null) {
                    return response()->json([
                        'status' => false,
                        'data' => ['message' => 'The requested bienestar activity type was not found']
                    ],400);
                } else {
                    $rules = [

                        'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'

                    ];
                    $validator = Validator::make($request->input(), $rules);
                    $validate = Controller::validate_exists($request->bie_act_typ_name, 'bienestar_activity_types', 'bie_act_typ_name', 'bie_act_typ_id', $id);

                    if ($validator->fails() || $validate == 0) {
                        $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                        return response()->json([
                            'status' => False,
                            'message' => $msg
                        ]);
                    } else {
                        $bienestarActTypes->bie_act_typ_name = $request->bie_act_typ_name;
                        $bienestarActTypes->bie_act_typ_status=1;
                        $bienestarActTypes->save();
                        Controller::NewRegisterTrigger("An update was made in the Bienestar Activities types table",4,$request->use_id);

                        return response()->json([
                        'status' => True,
                            'data' => "The bienestar activity type ".$bienestarActTypes->bie_act_typ_name." has been updated successfully."
                        ],200);
                    };
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

        $bienestarActTypes = BienestarActivityTypes::find($id);
        
                $newBiAct=($bienestarActTypes->bie_act_typ_status==1)?0:1;
                $bienestarActTypes->bie_act_typ_status =$newBiAct ;
                $bienestarActTypes->save();
                Controller::NewRegisterTrigger("An change status was made in the bienestar activity type table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested bienestar activity type has been change successfully'
                ]);
    
        
    }
}
