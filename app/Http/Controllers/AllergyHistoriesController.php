<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\AllergyHistory;
use Illuminate\Http\Request;

class AllergyHistoriesController extends Controller
{
    public function index($proj_id, $use_id)
{
    $aHistory = AllergyHistory::select();
    Controller::NewRegisterTrigger("A search was performed on the Allergies Histories table", 1, $proj_id, $use_id);
    return response()->json([
        'status' => true,
        'data' => $aHistory
    ], 200);
}
    public function store($proj_id,$use_id,Request $request)
    {
            if ($request->acc_administrator == 1) {
                $rules = [
                    'per_id' =>'required|numeric',
                    'all_id' =>'required|numeric',
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
                    Controller::NewRegisterTrigger("An insertion was made in the Allergies Histories table",3,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The Allergy History has been created successfully."
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
    $aHistory = AllergyHistory::find($id);
    Controller::NewRegisterTrigger("A search was performed on the Allergies Histories table", 1, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $aHistory
    ]);
}
public function update($proj_id, $use_id, Request $request, $id)
{
    $aHistory = AllergyHistory::find($id);

    if ($request->acc_administrator == 1) {
        $aHistory = AllergyHistory::find($id);
        if ($aHistory == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched Allergy History was not found']
            ], 400);
        } else {
            $rules = [
                'per_id' =>'required|numeric',
                'all_id' =>'required|numeric',
            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $aHistory->per_id = $request->per_id;
                $aHistory->all_id = $request->all_id;
                $aHistory->save();
                Controller::NewRegisterTrigger("An update was made in the Allergies Histories table", 1, $proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'message' => "The Allergy History has been updated."
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
                    'message' => 'The requested Allergy History type has already been disabled previously'
                ]);
            
    }
}
