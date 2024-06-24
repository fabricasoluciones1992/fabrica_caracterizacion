<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionsController extends Controller
{
    public function index()
    {
        $Actions = Action::all();


        return response()->json([
            'status' => true,
            'data' => $Actions
        ], 200);
    }





    public function store(Request $request)
    {

        if ($request->acc_administrator == 1) {
            $rules = [

                'act_name' => 'required|string|min:1|max:250|unique:actions|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'use_id' => 'required|integer|exists:users'

            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $Action = new Action($request->input());
                $Action->act_status = 1;
                $Action->save();
                Controller::NewRegisterTrigger("An insertion was made in the Actions table ", 3, $request->use_id);
                // $id = $Action->act_id;
                // $bienestar_news=ActionsController::Getbienestar_news($id);
                return response()->json([
                    'status' => True,
                    'message' => "The Action type " . $Action->act_name . " has been successfully created.",
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This Action can only be performed by active administrators.'
            ], 403);
        }

    }



    public function show($id)
    {

        $Action = Action::find($id);

        if ($Action == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested Action was not found']
            ], 400);
        } else {

            return response()->json([
                'status' => true,
                'data' => $Action
            ]);
        }

    }
    public function update(Request $request, $id)
    {

        $Action = Action::find($id);
        if ($request->acc_administrator == 1) {
            $rules = [

                'act_name' => 'required|string|min:1|max:250|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->act_name, 'actions', 'act_name', 'act_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            } else {
                $Action->act_name = $request->act_name;
                $Action->save();
                Controller::NewRegisterTrigger("An update was made in the Actions table", 4, $request->use_id);

                return response()->json([
                    'status' => True,
                    'data' => "The Action " . $Action->act_name . " has been successfully updated."
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This Action can only be performed by active administrators.'
            ], 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        $Action = Action::find($id);
        $newAction = ($Action->act_status == 1) ? 0 : 1;
        $Action->act_status = $newAction;
        $Action->save();
        Controller::NewRegisterTrigger("An changes status was made in the Actions table", 2, $request->use_id);
        return response()->json([
            'status' => True,
            'message' => 'The requested Action has been disabled successfully'
        ]);


    }
}

