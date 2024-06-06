<?php

namespace App\Http\Controllers;

use App\Models\MonetaryState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class MonetaryStatesController extends Controller
{
    public function index()
    {
        
        $monStates = MonetaryState::all();

        return response()->json([
            'status' => true,
            'data' => $monStates
        ], 200);
    
}

    public function store(Request $request)
    {
        

        if ($request->acc_administrator == 1) {
            $rules = [

                'mon_sta_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u|unique:monetary_states'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $monState = new MonetaryState($request->input());
                $monState->mon_sta_status=1;
                $monState->save();
                Controller::NewRegisterTrigger("An insertion was made in the monetary states table'$monState->mon_sta_id'", 3,$request->use_id);
 
                return response()->json([
                    'status' => True,
                    'message' => "The economic state type '".$monState->mon_sta_name."' has been created successfully.",

                ], 200);
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
         
        $monState = MonetaryState::find($id);

        if ($monState == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested economic state was not found']
            ], 400);
        } else {
         
            return response()->json([
                'status' => true,
                'data' => $monState
            ]);
        }
    
}

    public function update(Request $request, $id)
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
                $validate = Controller::validate_exists($request->mon_sta_name, 'monetary_states', 'mon_sta_name', 'mon_sta_id', $id);

                if ($validator->fails()||$validate==0) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $monState->mon_sta_name = $request->mon_sta_name;
                    $monState->save();
                    Controller::NewRegisterTrigger("An update was made in the monetary states table", 4,$request->use_id);

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

public function destroy(Request $request, $id)
{

    return response()->json([
        'status' => false,
        'message' => 'Function not available.'
    ]);
}
}
