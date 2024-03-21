<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionsController extends Controller
{
    public function index($proj_id, $use_id)
{
    $actions = action::getNews();

    Controller::NewRegisterTrigger("A search was performed on the actions table", 4, $proj_id, $use_id);

    return response()->json([
        'status' => true,
        'data' => $actions
    ], 200);
}





    public function store($proj_id,$use_id,Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [
                    'act_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                    
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $action = new action($request->input());
                    $action->act_status=1;
                    $action->save();

                    Controller::NewRegisterTrigger("An insertion was made in the Action table'$action->act_id'",3,$proj_id, $use_id);
                    $id = $action->act_id;
                    $news=ActionsController::GetNews($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The Action type ".$action->act_name." has been successfully created.",
                        'data' => $news
                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        
    }
    public function GetNews($id){
        $act_id = $id;
    
        $news = DB::table('ViewNews')
            ->select('new_date', 'per_name')
            ->whereRaw("TRIM(new_description) LIKE 'An insertion was made in the Action table \'$act_id\''")
            ->get();
    
        if ($news->count() > 0) {
            return $news[0];
        } else {
            return null;
        }
    }
    
    public function show($proj_id,$use_id,$id)
    {

        $action = action::find($id);
        
        $news=ActionsController::GetNews($id);
            if ($action == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested Action was not found']
                ],400);
            }else{
                Controller::NewRegisterTrigger("A search was performed on the actions table",4,$proj_id, $use_id);
                $action->new_date = $news->new_date;
                $action->per_name = $news->per_name;
                return response()->json([
                    'status' => true,
                    'data' => $action
                ]);
            }
        
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {

        $action = action::find($id);
        if ($request->acc_administrator == 1) {
                $rules = [
                    'act_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $action->act_name = $request->act_name;
                    $action->save();
                    Controller::NewRegisterTrigger("An update was made in the actions table",1,$proj_id, $use_id);

                    return response()->json([
                        'status' => True,
                        'data' => "The Action ".$action->act_name." has been successfully updated."
                    ],200);
                }
     }else {
        return response()->json([
            'status' => false,
            'message' => 'Access denied. This action can only be performed by active administrators.'
        ], 403); 
    }
    }

    public function destroy($proj_id,$use_id, $id)
    {
        $action = action::find($id);
        
            if ($action->act_status == 1){
                $action->act_status = 0;
                $action->save();
                Controller::NewRegisterTrigger("An delete was made in the actions table",2,$proj_id, $use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested Action has been disabled successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The requested Action has already been disabled previously'
                ]);
            }  
    }
}
    
