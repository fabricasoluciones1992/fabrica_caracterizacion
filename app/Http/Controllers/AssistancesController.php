<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssistancesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        
                $assistances = DB::select("
            SELECT ass.ass_id, ass.ass_date, 
                IF(ass.ass_status = 1, 'Attended', 'Did not attend') AS ass_status, 
                stu.stu_code, ba.bie_act_quotas, ba.bie_act_name,
                per.per_name
            FROM assistances ass
            INNER JOIN students stu ON stu.stu_id = ass.stu_id
            INNER JOIN bienestar_activities ba ON ba.bie_act_id = ass.bie_act_id
            INNER JOIN persons per ON per.per_id = stu.per_id
        ");
        $assistances = DB::select("SELECT * FROM Vista_Actividades_Bienestar_Estudiante");
        Controller::NewRegisterTrigger("A search was performed on the assistences table",4,$proj_id, $use_id);

        return response()->json([
            'status' => true,
            'data' => $assistances
        ],200);
    

    }
    public function store($proj_id,$use_id,Request $request)
    {
        
        if ($request->acc_administrator == 1) {
            $rules = [
                'ass_date' =>'date',
                'ass_status' =>'required|integer|max:1',
                'stu_id' =>'required|integer|max:1',
                'per_id' =>'required|integer|max:1',

                'bie_act_id' =>'required|integer|max:1'
            ];

            $validator = Validator::make($request->input(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $currentDate = now()->toDateString();

                $request->merge(['ass_date' => $currentDate]);

                $assistances = new assistance($request->input());
                $assistances->save();

                Controller::NewRegisterTrigger("An insertion was made in the assistences table",3,$proj_id, $use_id);

                return response()->json([
                    'status' => True,
                    'message' => "The assistance has been created successfully."
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
        
        $assistances =  DB::select("
            SELECT ass.ass_id, ass.ass_date, 
            IF(ass.ass_status = 1, 'Attended', 'Did not attend') AS ass_status, 
            stu.stu_code, ba.bie_act_quotas, ba.bie_act_name,
            per.per_name
        FROM assistances ass
        INNER JOIN students stu ON stu.stu_id = ass.stu_id
        INNER JOIN bienestar_activities ba ON ba.bie_act_id = ass.bie_act_id
        INNER JOIN persons per ON per.per_id = stu.per_id
                WHERE ass.ass_id = $id;
        ");
        $assistances =  DB::select("SELECT * FROM Vista_Actividades_Bienestar_Estudiante WHERE ass_id = $id; ");
        if ($assistances == null) {

            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched assistance was not found']
            ],400);
        }else{
            
            Controller::NewRegisterTrigger("A search was performed on the assistences table",4,$proj_id, $use_id);

            return response()->json([
                'status' => true,
                'data' => $assistances
            ]);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        
        $assistances = assistance::find($id);
        
        if ($_SESSION['acc_administrator'] == 1) {
            $assistances = assistance::find($id);
            if ($assistances == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The searched assistance was not found']
                ],400);
            } else {
                $rules = [
                    'ass_date' =>'date',
                    'ass_status' =>'required|integer|max:1',
                    'stu_id' =>'required|integer|max:1',
                    'bie_act_id' =>'required|integer|max:1',
                    'per_id' =>'required|integer|max:1'

                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                } else {
                    $currentDate = now()->toDateString();

                    $request->merge(['ass_date' => $currentDate]);

                    $assistances->ass_date = $request->ass_date;
                    $assistances->ass_status = $request->ass_status;
                    $assistances->stu_id = $request->stu_id;
                    $assistances->bie_act_id = $request->bie_act_id;
                    $assistances->save();
                    Controller::NewRegisterTrigger("An update was made in the assistences table",1,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The assistance has been updated."
                    ],200);
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
        $assistances = assistance::find($id);
        
            if ($assistances->ass_status == 1){
                $assistances->ass_status = 0;
                $assistances->save();
                Controller::NewRegisterTrigger("An delete was made in the actions table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested assistances has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested assistances has already been disabled previously'
                ]);
            } 

    }
}
