<?php

namespace App\Http\Controllers;

use App\Models\BienestarActivityTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BienestarActivityTypesController extends Controller
{
    public function index()
    {
        $bienestarActTypes = BienestarActivityTypes::all();
        return response()->json([
            'status' => true,
            'data' => $bienestarActTypes
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-Z\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $bienestarActTypes = new BienestarActivityTypes($request->input());
            $bienestarActTypes->save();
            Controller::NewRegisterTrigger("Se realizo una insercion en la tabla Bienestar Activities types",4,2,1);

            return response()->json([
                'status' => true,
                'message' => "El tipo de actividad de bienestar '".$bienestarActTypes->bie_act_typ_name."' ha sido creado exitosamente."
            ],200);
        }

    }
    public function show($id)
    {
        $bienestarActTypes = BienestarActivityTypes::find($id);
        if ($bienestarActTypes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se ha encontrado el tipo de actividad de bienestar solicitada']
            ],400);
        } else {
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Bienestar Activities types",4,2,1);

            return response()->json([
                'status' => true,
                'data' => $bienestarActTypes
            ]);
        }

    }
    public function update(Request $request, $id)
    {
        $bienestarActTypes = BienestarActivityTypes::find($id);
        if ($bienestarActTypes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se ha encontrado el tipo de actividad de bienestar solicitada']
            ],400);
        } else {
            $rules = [
                'bie_act_typ_name' =>'required|string|min:1|max:55|regex:/^[A-Z\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $bienestarActTypes->bie_act_typ_name = $request->bie_act_typ_name;
                $bienestarActTypes->save();
                Controller::NewRegisterTrigger("Se realizo una actualizacion en la tabla Bienestar Activities types",1,2,1);

                return response()->json([
                   'status' => True,
                    'data' => "el tipo de actividad de bienestar ".$bienestarActTypes->bie_act_typ_name." ha sido actualizado exitosamente."
                ],200);
            };
        }

    }
    public function destroy(BienestarActivityTypes $bienestarActTypes)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);

    }
}
