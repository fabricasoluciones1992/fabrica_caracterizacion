<?php

namespace App\Http\Controllers;

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
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'act_name' => 'required|string|min:1|max:50'
 
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
            return response()->json([
             'status' => True,
             'message' => "El tipo de Accion ".$action->act_name." ha sido creado exitosamente."
            ],200);
        }
    }
    public function show($id)
    {
        $action = action::find($id);
        if ($action == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra la Accion solicitada']
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
        if ($action == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra la Accion solicitada']
            ],400);
        }else{
            $rules = [
                'act_name' => 'required|string|min:1|max:50'
 
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
                return response()->json([
             'status' => True,
                   'data' => "la Accion ".$action->act_name." ha sido actualizado exitosamente."
                ],200);
            };
        }
    }
    public function destroy(action $actions)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
 
        ],400);
    }
}
