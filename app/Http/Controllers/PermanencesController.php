<?php

namespace App\Http\Controllers;

use App\Models\permanence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermanencesController extends Controller
{
    public function index()
    {
        $permanences = DB::select("SELECT permanences.perm_id,permanences.perm_date,permanences.perm_description,solicitudes.sol_description,actions.act_name FROM permanences
        INNER JOIN solicitudes ON permanences.sol_id = solicitudes.sol_id
        INNER JOIN actions ON permanences.act_id = actions.act_id
        ");
        return response()->json([
           'status' => true,
            'data' => $permanences
        ],200);
    }
 
    public function store(Request $request)
    {
        // return $request;
        $rules = [
            'perm_date' =>'required|date',
            'perm_description' =>'required|string|min:1|max:50',
            'sol_id' =>'required|integer',
            'act_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
             'status' => False,
             'message' => $validator->errors()->all()
            ]);
        }else{
            $permanences = new permanence($request->input());
            $permanences->save();
            return response()->json([
             'status' => True,
             'message' => "The permanences success has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $permanences =  DB::select("SELECT permanences.perm_id,permanences.perm_date,permanences.perm_description,solicitudes.sol_description,actions.act_name FROM permanences
        INNER JOIN solicitudes ON permanences.sol_id = solicitudes.sol_id
        INNER JOIN actions ON permanences.act_id = actions.act_id
         WHERE permanences.perm_id = $id;
        ");
        if ($permanences == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched permanences was not found']
            ],400);
        }else{
            return response()->json([
               'status' => true,
                'data' => $permanences
            ]);
        }
    }
    public function update(Request $request,$id)
    {
        $permanences = permanence::find($id);
        if ($permanences == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched novelty was not found']
            ],400);
        }else{
            $rules = [
                'perm_date' =>'required|date',
            'perm_description' =>'required|string|min:1|max:50',
            'sol_id' =>'required|integer',
            'act_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json()([
                   'status' => False,
                  'message' => $validator->errors()->all()
                ]);
            }else{
                $permanences->perm_date = $request->perm_date;
                $permanences->perm_description = $request->perm_description;
                $permanences->sol_id = $request->sol_id;
                $permanences->act_id = $request->act_id;
                $permanences->save();
                return response()->json([
                  'status' => True,
                  'message' => "The permanences has been updated."
                ],200);
            }
        }
    }
    public function destroy(permanence $permanences)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
         ],400);
    }
}
