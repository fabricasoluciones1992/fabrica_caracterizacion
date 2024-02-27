<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\MedicalHistory;
use Illuminate\Http\Request;

class MedicalHistoriesController extends Controller
{
    public function index($proj_id, $use_id)
{
    $mHistory = DB::select("
        SELECT mh.med_his_id, pe.per_name, di.dis_name
        FROM medical_histories mh
        INNER JOIN persons pe ON pe.per_id = mh.per_id
        INNER JOIN diseases di ON di.dis_id = mh.dis_id

    ");

    

    Controller::NewRegisterTrigger("A search was performed on the Medical Histories table", 4, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $mHistory
    ], 200);
}


    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {

                $rules = [
                    
                    'per_id' =>'required|numeric',
                    'dis_id' =>'required|numeric',


                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $mHistory = new MedicalHistory($request->input());
                    $mHistory->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Medical Histories table",3,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The Medical history has been created successfully."
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
    $mHistory = DB::select("
    SELECT mh.med_his_id, pe.per_name, di.dis_name
    FROM medical_histories mh
    INNER JOIN persons pe ON pe.per_id = mh.per_id
    INNER JOIN diseases di ON di.dis_id = mh.dis_id
        WHERE mh.med_his_id = $id
    ");


    Controller::NewRegisterTrigger("A search was performed on the Medical Histories table", 4, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $mHistory
    ]);
}


public function update($proj_id, $use_id, Request $request, $id)
{

    $mHistory = MedicalHistory::find($id);

    if ($request->acc_administrator == 1) {
        $mHistory = MedicalHistory::find($id);
        if ($mHistory == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched Medical history was not found']
            ], 400);
        } else {
            $rules = [
                'per_id' =>'required|numeric',
                'dis_id' =>'required|numeric',
            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $mHistory->per_id = $request->per_id;
                $mHistory->dis_id = $request->dis_id;
                $mHistory->save();
                Controller::NewRegisterTrigger("An update was made in the Medical Histories table", 1, $proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'message' => "The Medical history has been updated."
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
        
                return response()->json([
                    'status' => false,
                    'message' => 'The requested Medical history type has already been disabled previously'
                ]);
            
    }
}