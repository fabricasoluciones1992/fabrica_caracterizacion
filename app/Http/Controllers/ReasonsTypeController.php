<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\ReasonType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReasonsTypeController extends Controller
{
    public function index()
    {

        $reasonsT = ReasonType::all(); 
        return response()->json([
                'status' => true,
                'data' => $reasonsT
         ],200);
        
    }
    
    public function store(Request $request)
    {
            if ($request->acc_administrator == 1) {
                $rules = [
                    'rea_typ_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'rea_typ_type' => 'required|integer|in:0,1'

                    
                ];
                
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $reasont = new ReasonType($request->input());
                    $reasont->save();
                    Controller::NewRegisterTrigger("An insertion was made in the reasons table'$reasont->rea_typ_id'",3,$request->use_id);
                    // $id = $reasont->rea_id;
                    // $bienestar_news=ReasonsTController::Getbienestar_news($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The reason type ".$reasont->rea_typ_name." has been successfully created.",

                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This reason can only be performed by active administrators.'
                ], 403); 
            }
        
    }
//     public function Getbienestar_news($id)
// {
//     $rea_id = $id;
//     $bienestar_news = DB::table('bienestar_news')
//         ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
//         ->select('bie_new_date', 'persons.per_name')
//         ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the reasons table\'$rea_id\''")
//         ->get();

//     if ($bienestar_news->count() > 0) {
//         return $bienestar_news[0];
//     } else {
//         return null;
//     }
// }
    public function show($id)
    {

        $reasont = ReasonType::find($id);
        // $bienestar_news=ReasonsTController::Getbienestar_news($id);

            
            if ($reasont == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested reason was not found']
                ],400);
            }else{
                // $reasont->new_date = $bienestar_news->bie_new_date;
                // $reasont->createdBy = $bienestar_news->per_name;
                return response()->json([
                    'status' => true,
                    'data' => $reasont
                ]);
            }
        
    }
    public function update(Request $request, $id)
    {

        $reasont = ReasonType::find($id);
        if ($request->acc_administrator == 1) {
                $rules = [
                    'rea_typ_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $reasont->rea_typ_name = $request->rea_typ_name;
                    $reasont->save();
                    Controller::NewRegisterTrigger("An update was made in the reasons table",4,$request->use_id);

                    return response()->json([
                        'status' => True,
                        'data' => "The reason ".$reasont->rea_typ_name." has been successfully updated."
                    ],200);
                }
     }else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This reason can only be performed by active administrators.'
        ], 403); 
    }
    }
        
    
    public function destroy(Request $request,$id)
    {

        
                return response()->json([
                    'status' => false,
                    'message' => 'Function not available'
                ]);
            
    }
}
