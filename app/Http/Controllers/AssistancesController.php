<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssistancesController extends Controller
{
    public function index()
    {
        $assistances = DB::select("
            SELECT ass.ass_id, ass.ass_date, ass.ass_assistance, stu.stu_code, ba.bie_act_quotas
            FROM assistances ass
            INNER JOIN students stu ON stu.stu_id = ass.stu_id
            INNER JOIN bienestar_activities ba ON ba.bie_act_id = ass.bie_act_id 
        ");
        return response()->json([
            'status' => true,
            'data' => $assistances
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'ass_date' =>'required|date',
            'ass_assistance' =>'integer|max:1',
            'stu_id' =>'required|integer',
            'bie_act_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $assistances = new assistance($request->input());
            $assistances->save();
            return response()->json([
                'status' => True,
                'message' => "The assistance successfully has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $assistances =  DB::select("
            SELECT ass.ass_id, ass.ass_date, ass.ass_assistance, stu.stu_code, ba.bie_act_quotas
            FROM assistances ass
            INNER JOIN students stu ON stu.stu_id = ass.stu_id
            INNER JOIN bienestar_activities ba ON ba.bie_act_id = ass.bie_act_id
            WHERE ass.ass_id = $id;
        ");
        if ($assistances == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched assistance was not found']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $assistances
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $assistances = assistance::find($id);
        if ($assistances == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched assistance was not found']
            ],400);
        } else {
            $rules = [
                'ass_date' =>'required|date',
                'ass_assistance' =>'required|integer|max:1',
                'stu_id' =>'required|integer',
                'bie_act_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $assistances->ass_date = $request->ass_date;
                $assistances->ass_assistance = $request->ass_assistance;
                $assistances->stu_id = $request->stu_id;
                $assistances->bie_act_id = $request->bie_act_id;
                $assistances->save();
                return response()->json([
                    'status' => True,
                    'message' => "The assistance has been updated."
                ],200);
            }
        }
    }
    public function destroy(Assistance $assitance)
    {
        return response()->json([
           'status' => false,
           'message' => "Funcion no disponible"
         ],400);
    }
}
