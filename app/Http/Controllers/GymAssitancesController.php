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
    $gymAss = Gym_assistance::getbienestar_news();
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
                    $gymAs = new Gym_assistance($request->input());
                    $gymAs->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Gym assistances table'$gymAs->gym_ass_id'",3,$use_id);
                    $id = $gymAs->gym_ass_id;
                    $bienestar_news=GymAssitancesController::Getbienestar_news($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The Gym assistances has been created successfully.",
                        'data' => $bienestar_news
                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }
    
    public function Getbienestar_news($id)
{
    $gym_ass_id = $id;
    $bienestar_news = DB::table('bienestar_news')
        ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
        ->select('bie_new_date', 'persons.per_name')
        ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Gym assistances table\'$gym_ass_id\''")
        ->get();

    if ($bienestar_news->count() > 0) {
        return $bienestar_news[0];
    } else {
        return null;
    }
}
    public function show($proj_id, $use_id, $id)
{
    $gymAs = Gym_assistance::find($id);
    $bienestar_news=GymAssitancesController::Getbienestar_news($id);


    if (empty($gymAs)) {
        return response()->json([
            'status' => false,
            'message' => 'The requested Gym assistances was not found.'
        ], 404);
    }else{
        $gymAs->new_date = $bienestar_news->bie_new_date;
        $gymAs->createdBy = $bienestar_news->per_name;
        
        return response()->json([
        'status' => true,
        'data' => $gymAs
    ]);}



    
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
                Controller::NewRegisterTrigger("An update was made in the Gym assistances table", 4, $use_id);

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
                    'message' => 'function not available'
                ]);
    }
}

