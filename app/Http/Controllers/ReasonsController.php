<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReasonsController extends Controller
{
    public function index($proj_id,$use_id)
    {

        $reasons = reason::getbienestar_news(); 
        return response()->json([
                'status' => true,
                'data' => $reasons
         ],200);
        
    }
    
    public function store($proj_id,$use_id,Request $request)
    {
            if ($request->acc_administrator == 1) {
                $rules = [
                    'rea_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                    
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $reason = new reason($request->input());
                    $reason->rea_status=1;
                    $reason->save();
                    Controller::NewRegisterTrigger("An insertion was made in the reasons table'$reason->rea_id'",3,$use_id);
                    $id = $reason->rea_id;
                    $bienestar_news=ReasonsController::Getbienestar_news($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The reason type ".$reason->rea_name." has been successfully created.",
                        'data' => $bienestar_news

                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This reason can only be performed by active administrators.'
                ], 403); 
            }
        
    }
    public function Getbienestar_news($id)
{
    $rea_id = $id;
    $bienestar_news = DB::table('bienestar_news')
        ->join('persons', 'bienestar_news.use_id', '=', 'persons.use_id')
        ->select('bie_new_date', 'persons.per_name')
        ->whereRaw("TRIM(bie_new_description) LIKE 'An insertion was made in the reasons table\'$rea_id\''")
        ->get();

    if ($bienestar_news->count() > 0) {
        return $bienestar_news[0];
    } else {
        return null;
    }
}
    public function show($proj_id,$use_id,$id)
    {

        $reason = reason::find($id);
        $bienestar_news=ReasonsController::Getbienestar_news($id);

            
            if ($reason == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested reason was not found']
                ],400);
            }else{
                $reason->new_date = $bienestar_news->bie_new_date;
                $reason->createdBy = $bienestar_news->per_name;
                return response()->json([
                    'status' => true,
                    'data' => $reason
                ]);
            }
        
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {

        $reason = reason::find($id);
        if ($request->acc_administrator == 1) {
                $rules = [
                    'rea_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $reason->rea_name = $request->rea_name;
                    $reason->save();
                    Controller::NewRegisterTrigger("An update was made in the reasons table",4,$use_id);

                    return response()->json([
                        'status' => True,
                        'data' => "The reason ".$reason->rea_name." has been successfully updated."
                    ],200);
                }
     }else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This reason can only be performed by active administrators.'
        ], 403); 
    }
    }
        
    
    public function destroy($proj_id,$use_id, $id)
    {

        $reason = reason::find($id);
        
            if ($reason->rea_status == 1){
                $reason->rea_status = 0;
                $reason->save();
                Controller::NewRegisterTrigger("An delete was made in the reasons table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested reason has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested reason has already been disabled previously'
                ]);
            }  
    }
}
