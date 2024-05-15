<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionsController extends Controller
{
    public function index()
{
    $actions = action::all();


    return response()->json([
        'status' => true,
        'data' => $actions
    ], 200);
}





    public function store(Request $request)
    {
        
            if ($request->acc_administrator == 1) {
                $rules = [

                    'act_name' => 'required|string|min:1|max:50|unique:actions|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
                    
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
                    Controller::NewRegisterTrigger("An insertion was made in the Actions table'$action->act_id'",3,$request->use_id);
                    // $id = $action->act_id;
                    // $bienestar_news=ActionsController::Getbienestar_news($id);
                    return response()->json([
                        'status' => True,
                        'message' => "The Action type ".$action->act_name." has been successfully created.",
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

        $action = action::find($id);
        
            if ($action == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested Action was not found']
                ],400);
            }else{
               
                return response()->json([
                    'status' => true,
                    'data' => $action
                ]);
            }
        
    }
    public function update(Request $request, $id)
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
                    Controller::NewRegisterTrigger("An update was made in the actions table",4,$request->use_id);

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

    public function destroy(Request $request,$id)
    {
        $action = action::find($id);
        $newAction=($action->act_status == 1)?0:1;
                $action->act_status = $newAction;
                $action->save();
                Controller::NewRegisterTrigger("An changes status was made in the actions table",2,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'The requested Action has been disabled successfully'
                ]);

               
}
}
    
