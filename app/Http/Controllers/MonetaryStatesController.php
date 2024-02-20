<?php

namespace App\Http\Controllers;

use App\Models\MonetaryState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonetaryStatesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        
        $monState = MonetaryState::all();
        Controller::NewRegisterTrigger("A search was performed in the monetary states table", 4, $proj_id, $use_id);

        return response()->json([
            'status' => true,
            'data' => $monState
        ], 200);
    
}

    public function store($proj_id,$use_id,Request $request)
    {
        

        if ($request->acc_administrator == 1) {
            $rules = [
                'mon_sta_name' =>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $monState = new MonetaryState($request->input());
                $monState->save();
                Controller::NewRegisterTrigger("An insertion was made in the monetary states table", 3,$proj_id, $use_id);

                return response()->json([
                    'status' => True,
                    'message' => "The economic state type '".$monState->mon_sta_name."' has been created successfully."
                ], 200);
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
         
        $monState = MonetaryState::find($id);
        if ($monState == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested economic state was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the monetary states table", 4, $proj_id, $use_id);

            return response()->json([
                'status' => true,
                'data' => $monState
            ]);
        }
    
}

    public function update($proj_id,$use_id,Request $request, $id)
    {
        
        $monState = MonetaryState::find($id);
        
        if ($request->acc_administrator == 1) {
            $monState = MonetaryState::find($id);
            if ($monState == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested economic state was not found']
                ], 400);
            } else {
                $rules = [
                    'mon_sta_name' =>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $monState->mon_sta_name = $request->mon_sta_name;
                    $monState->save();
                    Controller::NewRegisterTrigger("An update was made in the monetary states table", 1, $proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The economic state '".$monState->mon_sta_name."' has been updated successfully."
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
        $monState = MonetaryState::find($id);
        
            if ($monState->mon_sta_status == 1){
                $monState->mon_sta_status = 0;
                $monState->save();
                Controller::NewRegisterTrigger("An delete was made in the permanences table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested economic state has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested economic state has already been disabled previously'
                ]);
            }
    }
}
