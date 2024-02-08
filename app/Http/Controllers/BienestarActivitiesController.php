<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BienestarActivitiesController extends Controller
{
    public function index()
    {
        $bienestarActivity = DB::select("
        SELECT ba.bie_act_id, ba.bie_act_date, ba.bie_act_quotas, ba.bie_act_description, bat.bie_act_typ_name
        FROM bienestar_activities ba
        INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
        ");
        Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table",4,2,1);

        return response()->json([
            'status' => true,
            'data' => $bienestarActivity
        ],200);

    }

    public function store(Request $request)
    {
        $rules = [
            'bie_act_date' =>'required|date',
            'bie_act_quotas' =>'string|max:25',
            'bie_act_description' =>'required|string|max:255',
            'bie_act_typ_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
            ]);
        } else {
            $bienestarActivity = new BienestarActivity($request->input());
            $bienestarActivity->save();
            Controller::NewRegisterTrigger("An insertion was made in the Bienestar Activities table",3,2,1);

            return response()->json([
                'status' => True,
                'message' => "The bienestar activity has been created successfully."
            ],200);
        }

    }
    public function show($id)
    {
        $bienestarActivity = DB::select("
        SELECT ba.bie_act_id, ba.bie_act_date, ba.bie_act_quotas, ba.bie_act_description, bat.bie_act_typ_name
        FROM bienestar_activities ba
        INNER JOIN bienestar_activity_types bat ON bat.bie_act_typ_id = ba.bie_act_typ_id
        WHERE ba.bie_act_id = $id;
        ");
        if ($bienestarActivity == null) {
            return response()->json([
               'status' => false,
                "data" => ['message' => 'The searched bienestar activity was not found']
            ],400);
        } else {
            Controller::NewRegisterTrigger("A search was performed on the Bienestar Activities table",4,2,1);

            return response()->json([
                'status' => true,
                'data' => $bienestarActivity
            ]);
        }

    }
    public function update(Request $request, $id)
    {
        $bienestarActivity = BienestarActivity::find($id);
        if ($bienestarActivity == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched bienestar activity was not found']
            ],400);
        } else {
            $rules = [
                'bie_act_date' =>'required|date',
                'bie_act_quotas' =>'string|max:25',
                'bie_act_description' =>'string|max:255',
                'bie_act_typ_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json()([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $bienestarActivity->bie_act_date = $request->bie_act_date;
                $bienestarActivity->bie_act_quotas = $request->bie_act_quotas;
                $bienestarActivity->bie_act_description = $request->bie_act_description;
                $bienestarActivity->bie_act_typ_id = $request->bie_act_typ_id;
                $bienestarActivity->save();
                Controller::NewRegisterTrigger("An update was made in the Bienestar Activities table",1,2,1);

                return response()->json([
                    'status' => True,
                    'message' => "The bienestar activity has been updated."
                ],200);
            }
        }

    }
    public function destroy(BienestarActivity $bienestarActivity)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ],400);

    }
    
}
