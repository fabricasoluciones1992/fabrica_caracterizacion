<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\AllergyHistory;
use Illuminate\Http\Request;

class AllergyHistoriesController extends Controller
{
    public function index()
{
    $aHistories = AllergyHistory::select();//unique
    return response()->json([
        'status' => true,
        'data' => $aHistories
    ], 200);
}
    public function store(Request $request)
    {
            if ($request->acc_administrator == 1) {
                $rules = [

                    'per_id' =>'required|exists:persons|numeric',
                    'all_id' =>'required|exists:allergies|numeric',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $aHistory = new AllergyHistory($request->input());
                    $aHistory->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Allergies Histories table'$aHistory->all_his_id'",3,$request->use_id);
                    
                    return response()->json([
                        'status' => True,
                        'message' => "The Allergy History has been created successfully.",

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
    $aHistory = AllergyHistory::find($id);

    if ($aHistory == null) {
        return response()->json([
            'status' => false,
            'data' => ['message' => 'The requested Allergies Histories was not found']
        ],400);
    }else{
        
        return response()->json([
            'status' => true,
            'data' => $aHistory
        ]);
    }
}
public function update(Request $request, $id)
{
    return response()->json([
        'status' => false,
        'message' => 'Function not available'
    ]);
}
    public function destroy(Request $request,$id)
    {
        
                return response()->json([
                    'status' => false,
                    'message' => 'Function not available'
                ]);
            
    }
}
