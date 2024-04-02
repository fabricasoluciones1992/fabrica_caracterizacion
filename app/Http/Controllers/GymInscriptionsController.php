<?php

namespace App\Http\Controllers;
use App\Models\GymInscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GymInscriptionsController extends Controller
{
    public function index($proj_id, $use_id)
{
    $gymIns = GymInscription::select();

    Controller::NewRegisterTrigger("A search was performed on the Gym inscriptions table", 1, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $gymIns
    ], 200);
}


    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {

                $rules = [
                    'gym_ins_date' =>'required|date',
                    'per_id' =>'required|numeric'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $gymIns = new GymInscription($request->input());
                    $gymIns->gym_ins_status=1;
                    $gymIns->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Gym inscriptions table",3,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The Gym inscriptions has been created successfully."
                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }

    public function show($proj_id, $use_id, $id)
{
    $gymIns = GymInscription::find($id);

    if (empty($gymIns)) {
        return response()->json([
            'status' => false,
            'message' => 'The requested Gym inscriptions was not found.'
        ], 404);
    }


    Controller::NewRegisterTrigger("A search was performed on the Gym inscriptions table", 1, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $gymIns
    ]);
}


public function update($proj_id, $use_id, Request $request, $id)
{



    $gymIns = GymInscription::find($id);

    if ($request->acc_administrator == 1) {
        $gymIns = GymInscription::find($id);
        if ($gymIns == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched Gym inscriptions was not found']
            ], 400);
        } else {
            $rules = [
                'gym_ins_date' =>'required|date',
                    'per_id' =>'required|numeric'
            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $gymIns->gym_ins_date = $request->gym_ins_date;
                $gymIns->per_id = $request->per_id;
                $gymIns->save();
                Controller::NewRegisterTrigger("An update was made in the Gym inscriptions table", 1, $proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'message' => "The Gym inscriptions has been updated."
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

    public function destroy($proj_id,$use_id, $id)
    {
        $gymIns = GymInscription::find($id);
        
            if ($gymIns->gym_ins_status == 1){
                $gymIns->gym_ins_status = 0;
                $gymIns->save();
                Controller::NewRegisterTrigger("An delete was made in the Gym inscriptions type table",2,$proj_id,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested Gym inscriptions type has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested Gym inscriptions type has already been disabled previously'
                ]);
            } 
    }
}

