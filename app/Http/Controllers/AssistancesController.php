<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssistancesController extends Controller
{
    public function index($proj_id)
    {
        $token = Controller::auth();

        $assistances = DB::select("
            SELECT ass.ass_id, ass.ass_date, if(ass.ass_assistance=1,'Attended','Did not attend') ass_assistance, stu.stu_code, ba.bie_act_quotas
            FROM assistances ass
            INNER JOIN students stu ON stu.stu_id = ass.stu_id
            INNER JOIN bienestar_activities ba ON ba.bie_act_id = ass.bie_act_id 
        ");
        Controller::NewRegisterTrigger("A search was performed on the assistences table",4,$proj_id, $token['use_id']);

        return response()->json([
            'status' => true,
            'data' => $assistances
        ],200);

    }
    public function store($proj_id,Request $request)
    {
        $token = Controller::auth();

        $rules = [
            'ass_date' =>'date',
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
            $currentDate = now()->toDateString();

            $request->merge(['ass_date' => $currentDate]);

            $assistances = new assistance($request->input());
            $assistances->save();

            Controller::NewRegisterTrigger("An insertion was made in the assistences table",3,$proj_id, $token['use_id']);

            return response()->json([
                'status' => True,
                'message' => "The assistance has been created successfully."
            ], 200);
        }
    }

    public function show($proj_id,$id)
    {
        $token = Controller::auth();

        $assistances =  DB::select("
            SELECT ass.ass_id, ass.ass_date, if(ass.ass_assistance=1,'Attended','Did not attend') ass_assistance, stu.stu_code, ba.bie_act_quotas
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
            
            Controller::NewRegisterTrigger("A search was performed on the assistences table",4,$proj_id, $token['use_id']);

            return response()->json([
                'status' => true,
                'data' => $assistances
            ]);
        }

    }
    public function update($proj_id,Request $request, $id)
    {
        $token = Controller::auth();

        $assistances = assistance::find($id);
        if ($assistances == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched assistance was not found']
            ],400);
        } else {
            $rules = [
                'ass_date' =>'date',
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
                $currentDate = now()->toDateString();

                $request->merge(['ass_date' => $currentDate]);

                $assistances->ass_date = $request->ass_date;
                $assistances->ass_assistance = $request->ass_assistance;
                $assistances->stu_id = $request->stu_id;
                $assistances->bie_act_id = $request->bie_act_id;
                $assistances->save();
                Controller::NewRegisterTrigger("An update was made in the assistences table",1,$proj_id, $token['use_id']);

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
            'message' => "Function not available"
        ],400);

    }
}
