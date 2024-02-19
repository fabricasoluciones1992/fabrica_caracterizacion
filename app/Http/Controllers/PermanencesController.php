<?php

namespace App\Http\Controllers;

use App\Models\Permanence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermanencesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        
        $permanences = DB::select("SELECT permanences.perm_id,permanences.perm_date,permanences.perm_description,solicitudes.sol_name,actions.act_name FROM permanences
        INNER JOIN solicitudes ON permanences.sol_id = solicitudes.sol_id
        INNER JOIN actions ON permanences.act_id = actions.act_id
        ");
        Controller::NewRegisterTrigger("A search was performed in the permanences table", 4, $proj_id, $use_id);

        return response()->json([
            'status' => true,
            'data' => $permanences
        ], 200);

    
}

    public function store($proj_id,$use_id,Request $request)
    {
        
        
        if ($request->acc_administrator == 1) {
            $rules = [
                'perm_date' =>'required|date',
                'perm_description' =>'required|string|min:1|max:50|/^[a-zA-Z0-9\s]+$/',
                'sol_id' =>'required|integer|max:1',
                'act_id' =>'required|integer|max:1'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $permanences = new Permanence($request->input());
                $permanences->save();
                Controller::NewRegisterTrigger("An insertion was made in the permanences table", 3, $proj_id, $use_id);

                return response()->json([
                    'status' => True,
                    'message' => "The permanences has been created successfully."
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
        
        $permanences =  DB::select("SELECT permanences.perm_id,permanences.perm_date,permanences.perm_description,requests.req_description,actions.act_name FROM permanences
        INNER JOIN requests ON permanences.req_id = requests.req_id
        INNER JOIN actions ON permanences.act_id = actions.act_id
         WHERE permanences.perm_id = $id;
        ");
        if ($permanences == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched permanences was not found']
            ], 400);
        } else {
            Controller::NewRegisterTrigger("A search was performed in the permanences table", 4,$proj_id, $use_id);

            return response()->json([
                'status' => true,
                'data' => $permanences
            ]);
        }

    
}

    public function update($proj_id,$use_id,Request $request, $id)
    {
        
        $permanences = Permanence::find($id);
        
        if ($request->acc_administrator == 1) {
            $permanences = Permanence::find($id);
            if ($permanences == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched permanence was not found']
                ], 400);
            } else {
                $rules = [
                    'perm_date' =>'required|date',
                    'perm_description' =>'required|string|min:1|max:50|/^[a-zA-Z0-9\s]+$/',
                    'sol_id' =>'required|integer|max:1',
                    'act_id' =>'required|integer|max:1'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    Controller::NewRegisterTrigger("An update was made in the permanences table", 1, $proj_id, $use_id);

                    $permanences->perm_date = $request->perm_date;
                    $permanences->perm_description = $request->perm_description;
                    $permanences->req_id = $request->req_id;
                    $permanences->act_id = $request->act_id;
                    $permanences->save();
                    return response()->json([
                        'status' => True,
                        'message' => "The permanence has been updated successfully."
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

    public function destroy($use_id,Permanence $permanences)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ], 400);

    }

}
