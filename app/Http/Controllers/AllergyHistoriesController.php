<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\AllergyHistory;
use Illuminate\Http\Request;

class AllergyHistoriesController extends Controller
{
    public function index()
{
    $aHistories = AllergyHistory::select();
    return response()->json([
        'status' => true,
        'data' => $aHistories
    ], 200);
}
    public function store($proj_id,$use_id,Request $request)
    {
            if ($request->acc_administrator == 1) {
                $rules = [
                    'per_id' =>'required|numeric',
                    'all_id' =>'required|numeric',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                } else {
                    $aHistory = new AllergyHistory($request->input());
                    $aHistory->save();
                    Controller::NewRegisterTrigger("An insertion was made in the Allergies Histories table'$aHistory->all_his_id'",3,$use_id);
                    // $id = $aHistory->all_his_id;
                    // $bienestar_news=AllergyHistoriesController::Getbienestar_news($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The Allergy History has been created successfully.",

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
//     $all_his_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the Allergies Histories table\'$all_his_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }
    public function show($id)
{
    $aHistory = AllergyHistory::find($id);
    // $bienestar_news=AllergyHistoriesController::Getbienestar_news($id);

    if ($aHistory == null) {
        return response()->json([
            'status' => false,
            'data' => ['message' => 'The requested Allergies Histories was not found']
        ],400);
    }else{
        // $aHistory->new_date = $bienestar_news->bie_new_date;
        // $aHistory->createdBy = $bienestar_news->per_name;
        return response()->json([
            'status' => true,
            'data' => $aHistory
        ]);
    }
}
public function update($proj_id, $use_id, Request $request, $id)
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
