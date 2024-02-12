<?php

namespace App\Http\Controllers;

use App\Models\Permanence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermanencesController extends Controller
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
        $permanences = DB::select("SELECT permanences.perm_id,permanences.perm_date,permanences.perm_description,solicitudes.sol_description,actions.act_name FROM permanences
        INNER JOIN requests ON permanences.req_id = requests.req_id
        INNER JOIN actions ON permanences.act_id = actions.act_id
        ");
        Controller::NewRegisterTrigger("A search was performed in the permanences table", 4, $proj_id, 1);

        return response()->json([
            'status' => true,
            'data' => $permanences
        ], 200);

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
                'perm_date' =>'required|date',
                'perm_description' =>'required|string|min:1|max:50|/^[a-zA-Z0-9\s]+$/',
                'req_id' =>'required|integer|max:1',
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
                Controller::NewRegisterTrigger("An insertion was made in the permanences table", 3, $proj_id, 1);

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
            Controller::NewRegisterTrigger("A search was performed in the permanences table", 4,$proj_id, 1);

            return response()->json([
                'status' => true,
                'data' => $permanences
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
        $permanences = Permanence::find($id);
        
        if ($_SESSION['acc_administrator'] == 1) {
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
                    'req_id' =>'required|integer|max:1',
                    'act_id' =>'required|integer|max:1'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    Controller::NewRegisterTrigger("An update was made in the permanences table", 1, $proj_id, 1);

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
}

    public function destroy(Permanence $permanences)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ], 400);

    }

}
