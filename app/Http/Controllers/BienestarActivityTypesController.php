<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivityTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BienestarActivityTypesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        
            $bienestarActTypes = BienestarActivityTypes::all();
            Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities Types table",4,$proj_id, $use_id);

            return response()->json([
                'status' => true,
                'data' => $bienestarActTypes
            ],200);
        
    }
    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [
                    'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $bienestarActTypes = new BienestarActivityTypes($request->input());
                    $bienestarActTypes->bie_act_typ_status=1;
                    $bienestarActTypes->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities types table",4,$proj_id, $use_id);

                    return response()->json([
                        'status' => true,
                        'message' => "The bienestar activity type '".$bienestarActTypes->bie_act_typ_name."' has been created successfully."
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
        
            $bienestarActTypes = BienestarActivityTypes::find($id);
            if ($bienestarActTypes == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested bienestar activity type was not found']
                ],400);
            } else {
                Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities types table",4,$proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'data' => $bienestarActTypes
                ]);
            }
        
    }
    public function update($proj_id,$use_id,Request $request, $id)
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
                    if ($validator->fails()) {
                        return response()->json([
                            'status' => False,
                            'message' => $validator->errors()->all()
                        ]);
                    } else {
                        $bienestarActTypes->bie_act_typ_name = $request->bie_act_typ_name;
                        $bienestarActTypes->bie_act_typ_status=1;
                        $bienestarActTypes->save();
                        Controller::NewRegisterTrigger("An update was made in the Bienestar Activities types table",1,2,$use_id);

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
    public function destroy($proj_id,$use_id, $id)
    {

        $bienestarActTypes = BienestarActivityTypes::find($id);
        
            if ($bienestarActTypes->bie_act_typ_status == 1){
                $bienestarActTypes->bie_act_typ_status = 0;
                $bienestarActTypes->save();
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
