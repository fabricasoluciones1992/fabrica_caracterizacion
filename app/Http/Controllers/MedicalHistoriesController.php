<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\MedicalHistory;
use Illuminate\Http\Request;

class MedicalHistoriesController extends Controller
{
    public function index()
{
    $mHistory = MedicalHistory::select();

    return response()->json([
        'status' => true,
        'data' => $mHistory
    ], 200);
}


    public function store(Request $request)
    {
        
            if ($request->acc_administrator == 1) {

                $rules = [

                    
                    'per_id' =>'required|exists:persons|numeric',
                    'dis_id' =>'required|exists:diseases|numeric',


                ];
                
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $existingdisease = MedicalHistory::where('per_id', $request->per_id)
                                          ->where('dis_id', $request->dis_id)
                                          ->first();

                if ($existingdisease) {
                    return response()->json([
                        'status' => false,
                        'message' => 'A disease with the same characteristics already exists.'
                    ], 409);
                }
                    $mHistory = new MedicalHistory($request->input());
                    $mHistory->med_his_status=1;
                    $mHistory->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Medical Histories table'$mHistory->med_his_id'",3, $request->use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The Medical history has been created successfully.",

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
    $mHistory = MedicalHistory::find($id);
    if ($mHistory == null) {
        return response()->json([
            'status' => false,
            'data' => ['message' => 'The requested Medical Histories was not found']
        ],400);
    }else{

        return response()->json([
            'status' => true,
            'data' => $mHistory
        ]);
    }
    
}


public function update(Request $request, $id)
{

    return response()->json([
        'status' => false,
        'message' => 'Function not available.'
    ]);
}

    public function destroy(Request $request,$id)
    {
        
        return response()->json([
            'status' => false,
            'message' => 'Function not available.'
        ]);
            
    }
}