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
                    $gymIn = new GymInscription($request->input());
                    $gymIn->gym_ins_status=1;
                    $gymIn->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Gym inscriptions table'$gymIn->gym_ins_id'",3,$use_id);
                    // $id = $gymIn->gym_ins_id;
                    // $bienestar_news=GymInscriptionsController::Getbienestar_news($id);
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
//     public function Getbienestar_news($id)
// {
//     $gym_ins_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Gym inscriptions table\'$gym_ins_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }

    public function show($proj_id, $use_id, $id)
{
    $gymIn = GymInscription::find($id);
    // $bienestar_news=GymInscriptionsController::Getbienestar_news($id);

    if ($gymIn == null) {
        return response()->json([
            'status' => false,
            'message' => 'The requested Gym inscriptions was not found.'
        ], 404);
    }else{
        // $gymIn->new_date = $bienestar_news->bie_new_date;
        // $gymIn->createdBy = $bienestar_news->per_name;

        return response()->json([
            'status' => true,
            'data' => $gymIn
        ]);
    }



   
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
                Controller::NewRegisterTrigger("An update was made in the Gym inscriptions table", 4, $use_id);

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
public function filtredforDocument($proj_id, $use_id, $id)
{
    try {
        $gymAss = Controller::findByDocument($id);
 
        return response()->json([
            'status' => true,
            'data' => $gymAss
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}

    public function destroy($proj_id,$use_id, $id)
    {
        $gymIns = GymInscription::find($id);
        $newGy=($gymIns->gym_ins_status==1)?0:1;
                $gymIns->gym_ins_status = $newGy;
                $gymIns->save();
                Controller::NewRegisterTrigger("An change status was made in the Gym inscriptions type table",2,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested Gym inscriptions type has been change status successfully'
                ]);
                
    }
}

