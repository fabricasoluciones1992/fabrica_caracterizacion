<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionsController extends Controller
{
    public function index()
    {
 
        $actions = action::all();
        Controller::NewRegisterTrigger("A search was performed on the actions table",4,2,1);
        return response()->json([
            'status' => true,
            'data' => $actions
        ],200);
    }
    public function store(Request $request)
    {
        session_start();
        if ($_SESSION['acc_administrator'] == 1) {
            $rules = [
                'act_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/'
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
                Controller::NewRegisterTrigger("An insertion was made in the actions table",3,2,1);
    
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
    public function show($id)
    {
        $action = action::find($id);
        if ($action == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested Action was not found']
            ],400);
        }else{
            Controller::NewRegisterTrigger("A search was performed on the actions table",4,2,1);

            return response()->json([
                'status' => true,
                'data' => $action
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $action = action::find($id);
        if ($action == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested Action was not found']
            ],400);
        }else{
            $rules = [
                'act_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/'
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
                Controller::NewRegisterTrigger("An update was made in the actions table",1,2,1);

                return response()->json([
                    'status' => True,
                    'data' => "The Action ".$action->act_name." has been successfully updated."
                ],200);
            };
        }
    }
    public function destroy(action $actions)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ],400);
    }
}
