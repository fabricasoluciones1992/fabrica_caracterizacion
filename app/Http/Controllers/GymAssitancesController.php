<?php

namespace App\Http\Controllers;
use App\Models\Gym_assistance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GymAssitancesController extends Controller
{
    public function index()
{
    $gymAss = Gym_assistance::select();
    return response()->json([
        'status' => true,
        'data' => $gymAss
    ], 200);
}


    public function store(Request $request)//FECHA ACTUAL
    {
        
            if ($request->acc_administrator == 1) {

                $rules = [
                    'per_id' =>'required|numeric'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $currentDate = now()->toDateString();
                    $request->merge(['gym_ass_date' => $currentDate]);
                    $gymAs = new Gym_assistance($request->input());

                    $gymAs->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Gym assistances table'$gymAs->gym_ass_id'",3,$request->use_id);
                    // $id = $gymAs->gym_ass_id;
                    // $bienestar_news=GymAssitancesController::Getbienestar_news($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The Gym assistances has been created successfully.",
                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }
    
//     public function Getbienestar_news($id)
// {
//     $gym_ass_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Gym assistances table\'$gym_ass_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }
    public function show($id)
{
    $gymAs = Gym_assistance::find($id);


    if (empty($gymAs)) {
        return response()->json([
            'status' => false,
            'message' => 'The requested Gym assistances was not found.'
        ], 404);
    }else{
       
        
        return response()->json([
        'status' => true,
        'data' => $gymAs
    ]);}



    
}


public function update(Request $request, $id)
{


                
        return response()->json([
            'status' => false,
            'message' => 'function not available.'
        ], 403);
    
}


    public function destroy(Request $request,$id)
    {
        
                return response()->json([
                    'status' => false,
                    'message' => 'function not available'
                ]);
    }
}

