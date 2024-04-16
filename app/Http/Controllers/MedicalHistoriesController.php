<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\MedicalHistory;
use Illuminate\Http\Request;

class MedicalHistoriesController extends Controller
{
    public function index()
{
    $mHistory = MedicalHistory::select();

    return response()->json([
        'status' => true,
        'data' => $mHistory
    ], 200);
}


    public function store(Request $request)
    {
        
            if ($request->acc_administrator == 1) {

                $rules = [
                    
                    'per_id' =>'required|numeric',
                    'dis_id' =>'required|numeric',


                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $mHistory = new MedicalHistory($request->input());
                    $mHistory->med_his_status=1;
                    $mHistory->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Medical Histories table'$mHistory->med_his_id'",3, $use_id);
                    // $id = $mHistory->med_his_id;
                    // $bienestar_news=MedicalHistoriesController::Getbienestar_news($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The Medical history has been created successfully.",

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
//     $med_his_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Medical Histories table\'$med_his_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }

    public function show($id)
{
    $mHistory = MedicalHistory::find($id);
    // $bienestar_news=MedicalHistoriesController::Getbienestar_news($id);
    if ($mHistory == null) {
        return response()->json([
            'status' => false,
            'data' => ['message' => 'The requested Medical Histories was not found']
        ],400);
    }else{
        // $mHistory->new_date = $bienestar_news->bie_new_date;
        // $mHistory->createdBy = $bienestar_news->per_name;
        return response()->json([
            'status' => true,
            'data' => $mHistory
        ]);
    }
    
}


public function update(Request $request, $id)
{

    return response()->json([
        'status' => false,
        'message' => 'Function not available'
    ]);
}

    public function destroy($proj_id,$use_id, $id)
    {
        
        return response()->json([
            'status' => false,
            'message' => 'Function not available'
        ]);
            
    }
}