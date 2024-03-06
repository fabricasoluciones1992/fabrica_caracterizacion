<?php

namespace App\Http\Controllers;
use App\Models\Gym_assistance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GymAssitancesController extends Controller
{
    public function index($proj_id, $use_id)
{
    $gymAss = Gym_assistance::select();

    Controller::NewRegisterTrigger("A search was performed on the Gym assistances table", 4, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $gymAss
    ], 200);
}


    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {

                $rules = [
                    'gym_ass_date' =>'required|date',
                    'per_id' =>'required|numeric'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $gymAss = new Gym_assistance($request->input());
                    $gymAss->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Gym assistances table",3,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'message' => "The Gym assistances has been created successfully."
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
    $gymAss = Gym_assistance::find($id);

    if (empty($gymAss)) {
        return response()->json([
            'status' => false,
            'message' => 'The requested Gym assistances was not found.'
        ], 404);
    }


    Controller::NewRegisterTrigger("A search was performed on the Gym assistances table", 4, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $gymAss
    ]);
}


public function update($proj_id, $use_id, Request $request, $id)
{



    $gymAss = Gym_assistance::find($id);

    if ($request->acc_administrator == 1) {
        $gymAss = Gym_assistance::find($id);
        if ($gymAss == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched Gym assistances was not found']
            ], 400);
        } else {
            $rules = [
                'gym_ass_date' =>'required|date',
                'per_id' =>'required|numeric'
            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $gymAss->gym_ass_date = $request->gym_ass_date;
                $gymAss->per_id = $request->per_id;
                $gymAss->save();
                Controller::NewRegisterTrigger("An update was made in the Gym assistances table", 1, $proj_id, $use_id);

                return response()->json([
                    'status' => true,
                    'message' => "The Gym assistances has been updated."
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
        
                return response()->json([
                    'status' => false,
                    'message' => 'The requested Gym assistances type has already been disabled previously'
                ]);
    }
}

