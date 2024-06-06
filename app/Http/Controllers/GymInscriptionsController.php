<?php

namespace App\Http\Controllers;
use App\Models\GymInscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GymInscriptionsController extends Controller
{
    public function index()
{
    $gymIns = GymInscription::select();


    return response()->json([
        'status' => true,
        'data' => $gymIns
    ], 200);
}


    public function store(Request $request)
    {
        
            if ($request->acc_administrator == 1) {

                $rules = [
                    'per_id' =>'required|exists:persons|numeric'
                ];
                $validator = Validator::make($request->input(), $rules);
                $existingInsg = DB::table('gym_inscriptions')
                            ->where('per_id', $request->per_id)
                            ->first();

                    if ($existingInsg) {
                        return response()->json([
                            'status' => false,
                            'message' => 'The person is already inscription in the gym'
                        ]);
                    }
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $gymIn = new GymInscription($request->input());
                    $gymIn->gym_ins_status=1;
                    $gymIn->gym_ins_date = now()->toDateString(); 

                    $gymIn->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Gym inscriptions table'$gymIn->gym_ins_id'",3,$request->use_id);
                    
                    return response()->json([
                        'status' => True,
                        'message' => "The Gym inscriptions has been created successfully.",

                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }


    public function show($id)
{
    $gymIn = GymInscription::find($id);

    if ($gymIn == null) {
        return response()->json([
            'status' => false,
            'message' => 'The requested Gym inscriptions was not found.'
        ], 404);
    }else{


        return response()->json([
            'status' => true,
            'data' => $gymIn
        ]);
    }



   
}


public function update(Request $request, $id)
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
            $existingInsg = DB::table('gym_inscriptions')
                            ->where('per_id', $request->per_id)
                            ->first();

                    if ($existingInsg) {
                        return response()->json([
                            'status' => false,
                            'message' => 'The person is already inscription in the gym'
                        ]);
                    }
            $rules = [
                    'per_id' =>'required|exists:persons|numeric'
            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $gymIns->gym_ins_date = now()->toDateString(); 
                $gymIns->per_id = $request->per_id;
                $gymIns->save();
                Controller::NewRegisterTrigger("An update was made in the Gym inscriptions table", 4, $request->use_id);

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
    public function destroy(Request $request,$id)
    {
        $gymIns = GymInscription::find($id);
        $newGy=($gymIns->gym_ins_status==1)?0:1;
                $gymIns->gym_ins_status = $newGy;
                $gymIns->save();
                Controller::NewRegisterTrigger("An change status was made in the Gym inscriptions type table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested Gym inscriptions type has been change status successfully'
                ]);
                
    }
}

