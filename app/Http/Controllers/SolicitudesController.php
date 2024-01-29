<?php

namespace App\Http\Controllers;

use App\Models\solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SolicitudesController extends Controller
{
 
    public function index()
    {
        $solicitudes = DB::select("SELECT solicitudes.sol_id,solicitudes.sol_date,solicitudes.sol_description,request_types.rea_typ_name,studens.stu_name FROM permanences
        INNER JOIN reasons ON solicitudes.sol_id = solicitudes.sol_id
        INNER JOIN actions ON permanences.act_id = actions.act_id
        ");
        return response()->json([
           'status' => true,
            'data' => $solicitudes
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
            $solicitudes = new solicitud($request->input());
            $solicitudes->save();
            return response()->json([
             'status' => True,
             'message' => "The request success has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $solicitudes =  DB::select("SELECT permanences.perm_id,permanences.perm_date,permanences.perm_description,solicitudes.sol_description,actions.act_name FROM permanences
        INNER JOIN solicitudes ON permanences.sol_id = solicitudes.sol_id
        INNER JOIN actions ON permanences.act_id = actions.act_id
         WHERE $id = permanences.perm_id;
        ");
        if ($solicitudes == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched solicitudes was not found']
            ],400);
        }else{
            return response()->json([
               'status' => true,
                'data' => $solicitudes
            ]);
        }
    }
    public function update(Request $request,$id)
    {
        $solicitudes = permanence::find($id);
        if ($solicitudes == null) {
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
                $solicitudes->perm_date = $request->perm_date;
                $solicitudes->perm_description = $request->perm_description;
                $solicitudes->sol_id = $request->sol_id;
                $solicitudes->act_id = $request->act_id;
                $solicitudes->save();
                return response()->json([
                  'status' => True,
                  'message' => "The solicitudes has been updated."
                ],200);
            }
        }
    }
    public function destroy(solicitud $solicitudes)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
         ],400);
    }
}
