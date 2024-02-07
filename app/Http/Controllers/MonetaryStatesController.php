<?php

namespace App\Http\Controllers;

use App\Models\MonetaryState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonetaryStatesController extends Controller
{
    public function index()
    {
        $monState = MonetaryState::all();
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla monetary states",4,2,1);

        return response()->json([
            'status' => true,
            'data' => $monState
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'mon_sta_name' =>'required|string|min:1|max:50|regex:/^[A-Z\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $monState = new MonetaryState($request->input());
            $monState->save();
            Controller::NewRegisterTrigger("Se realizo una insercion en la tabla monetary states",3,2,1);

            return response()->json([
                'status' => True,
                'message' => "El tipo de estado economico '".$monState->mon_sta_name."' ha sido creado exitosamente."
            ],200);
        } 

    }
    public function show($id)
    {
        $monState = MonetaryState::find($id);
        if ($monState == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se ha encontrado el estado economico solicitado']
            ],400);
        } else {
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla monetary states",4,2,1);

            return response()->json([
                'status' => true,
                'data' => $monState
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $monState = MonetaryState::find($id);
        if ($monState == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se ha encontrado el estado economico solicitado']
            ],400);
        } else {
            $rules = [
            'mon_sta_name' =>'required|string|min:1|max:50',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $monState->mon_sta_name = $request->mon_sta_name;
                $monState->save();
                Controller::NewRegisterTrigger("Se realizo una actualizacion en la tabla monetary states",1,2,1);

                return response()->json([
                    'status' => True,
                    'data' => "el estado economico ".$monState->mon_sta_name." ha sido actualizada exitosamente."
                ],200);
            };
        }
    }
    public function destroy(MonetaryState $monetaryState)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);

    }
}
