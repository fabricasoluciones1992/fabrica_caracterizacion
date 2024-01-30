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
        return response()->json([
            'status' => true,
            'data' => $bienestarActivity
        ],200);
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Bienestar Activities",4,2,1);

    }

    public function store(Request $request)
    {
        $rules = [
            'bie_act_date' =>'required|date',
            'bie_act_quotas' =>'string|max:25',
            'bie_act_description' =>'string|max:255',
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
            return response()->json([
                'status' => True,
                'message' => "The bienestar activity successfully has been created."
            ],200);
        }
        Controller::NewRegisterTrigger("Se realizo una insercion en la tabla Bienestar Activities",3,2,1);

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
            return response()->json([
                'status' => true,
                'data' => $bienestarActivity
            ]);
        }
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Bienestar Activities",4,2,1);

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
                return response()->json([
                    'status' => True,
                    'message' => "The bienestar activity has been updated."
                ],200);
            }
        }
        Controller::NewRegisterTrigger("Se realizo una actualizacion en la tabla Bienestar Activities",1,2,1);

    }
    public function destroy(BienestarActivity $bienestarActivity)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);
        Controller::NewRegisterTrigger("Se realizo una eliminacion en la tabla Bienestar Activities",2,2,1);

    }
    
}
