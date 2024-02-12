<?php

namespace App\Http\Controllers;

use App\Models\action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionsController extends Controller
{
    public function index($proj_id)
    {
        $token = Controller::auth();

        $actions = action::all();
        if($token =='Token not found in session'){
            return response()->json([
                'status' => False,
                'message' => 'Token not found, please login and try again.'
            ],400);
        } else {       
            Controller::NewRegisterTrigger("A search was performed on the actions table",4,$proj_id, 1);
            return response()->json([
                'status' => true,
                'data' => $actions
            ],200);
        }
    }
    public function store($proj_id,Request $request)
    {
        $token = Controller::auth();
        if($token =='Token not found in session'){
            return response()->json([
                'status' => False,
                'message' => 'Token not found, please login and try again.'
            ],400);
        } else {
            if ($_SESSION['acc_administrator'] == 1) {
                $rules = [
                    'act_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑ\s]+$/',
                    'act_status' => 'required|integer|min:0|max:1'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                    ]);
                }else{
                    $action = new action($request->input());
                    $action->save();
                    Controller::NewRegisterTrigger("An insertion was made in the actions table",3,$proj_id, 1);
        
                    return response()->json([
                        'status' => True,
                        'message' => "The Action type ".$action->act_name." has been successfully created."
                    ],200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. This action can only be performed by active administrators.'
                ], 403); 
            }
        } 
    }
    public function show($proj_id,$id)
    {
        $token = Controller::auth();

        $action = action::find($id);
        if($token =='Token not found in session'){
            return response()->json([
                'status' => False,
                'message' => 'Token not found, please login and try again.'
            ],400);
        } else {
            if ($action == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested Action was not found']
                ],400);
            }else{
                Controller::NewRegisterTrigger("A search was performed on the actions table",4,$proj_id, 1);

                return response()->json([
                    'status' => true,
                    'data' => $action
                ]);
            }
        }
    }
    public function update($proj_id,Request $request, $id)
    {
        $token = Controller::auth();

        $action = action::find($id);
        if($token =='Token not found in session'){
            return response()->json([
                'status' => False,
                'message' => 'Token not found, please login and try again.'
            ],400);
        }else{
            if ($action == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested Action was not found']
                ],400);
            }else{
                $rules = [
                    'act_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑ\s]+$/',
                    'act_status' => 'required|integer|min:0|max:1'
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
                    Controller::NewRegisterTrigger("An update was made in the actions table",1,$proj_id, 1);

                    return response()->json([
                        'status' => True,
                        'data' => "The Action ".$action->act_name." has been successfully updated."
                    ],200);
                };
            }
        }
    }
    public function destroy($proj_id, string $id)
    {
        $token = Controller::auth();

        $action = action::find($id);
        if ($action == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested Action was not found']
            ],400);
        } else {
            if ($action->act_status == 1){
                $action->act_status = 0;
                $action->save();
                Controller::NewRegisterTrigger("An delete was made in the actions table",1,$proj_id, $token['use_id']);
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
}
