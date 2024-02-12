<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivityTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BienestarActivityTypesController extends Controller
{
    public function index($proj_id)
    {
        $token = Controller::auth();
        if($token =='Token not found in session'){
            return response()->json([
            'status' => False,
            'message' => 'Token not found, please login and try again.'
            ],400);
    }else{
        $bienestarActTypes = BienestarActivityTypes::all();
        Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities Types table",4,$proj_id, 1);

        return response()->json([
            'status' => true,
            'data' => $bienestarActTypes
        ],200);
    }
}
    public function store($proj_id,Request $request)
    {
        $token = Controller::auth();
        if($token =='Token not found in session'){
            return response()->json([
            'status' => False,
            'message' => 'Token not found, please login and try again.'
            ],400);
    }else{
        if ($_SESSION['acc_administrator'] == 1) {
            $rules = [
                'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-ZÑ\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $bienestarActTypes = new BienestarActivityTypes($request->input());
                $bienestarActTypes->save();
                Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities types table",4,$proj_id, 1);

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
}
    public function show($proj_id,$id)
    {
        $token = Controller::auth();
        if($token =='Token not found in session'){
            return response()->json([
            'status' => False,
            'message' => 'Token not found, please login and try again.'
            ],400);
    }else{
        $bienestarActTypes = BienestarActivityTypes::find($id);
        if ($bienestarActTypes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested bienestar activity type was not found']
            ],400);
        } else {
            Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities types table",4,$proj_id, 1);

            return response()->json([
                'status' => true,
                'data' => $bienestarActTypes
            ]);
        }
    }
    }
    public function update($proj_id,Request $request, $id)
    {
        $token = Controller::auth();
        if($token =='Token not found in session'){
            return response()->json([
            'status' => False,
            'message' => 'Token not found, please login and try again.'
            ],400);
    }else{
        $bienestarActTypes = BienestarActivityTypes::find($id);
 
        if ($_SESSION['acc_administrator'] == 1) {
            $bienestarActTypes = BienestarActivityTypes::find($id);
            if ($bienestarActTypes == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested bienestar activity type was not found']
                ],400);
            } else {
                $rules = [
                    'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-ZÑ\s]+$/',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $bienestarActTypes->bie_act_typ_name = $request->bie_act_typ_name;
                    $bienestarActTypes->save();
                    Controller::NewRegisterTrigger("An update was made in the Bienestar Activities types table",1,2,1);

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
}
    public function destroy(BienestarActivityTypes $bienestarActTypes)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ],400);

    }
}
