<?php

namespace App\Http\Controllers;

use App\Models\solicitudType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class solicitudesTypesController extends Controller
{
    public function index()
    {
        $solicitudTypes = solicitudType::all();
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla solicitudes types",4,2,1);
        return response()->json([
            'status' => true,
            'data' => $solicitudTypes
        ],200);
        

    }
    public function store(Request $request)
    {
        $rules = [
            'sol_typ_name' => 'required|string|min:1|max:100',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
 
             'status' => False,
             'message' => $validator->errors()->all()
            ]);
        } else {
            $solicitudTypes = new solicitudType($request->input());
            $solicitudTypes->save();
            Controller::NewRegisterTrigger("Se realizo una insercion en la tabla solicitudes types",3,2,1);
            return response()->json([
             'status' => True,
             'message' => "El tipo de razon '".$solicitudTypes->sol_typ_name."' ha sido creado exitosamente."
            ],200);
        }  
        

    }
    public function show($id)
    {
        $solicitudTypes = solicitudType::find($id);
        if ($solicitudTypes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se ha encontrado la razon solicitada']
            ],400);
        } else {
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla solicitudes types",4,2,1);
            return response()->json([
                'status' => true,
                'data' => $solicitudTypes
            ]);
        }
        

    }
    public function update(Request $request, $id)
    {
        $solicitudTypes = solicitudType::find($id);
        if ($solicitudTypes == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se ha encontrado la razon solicitada']
            ],400);
        } else {
            $rules = [
              'sol_typ_name' =>'required|string|min:1|max:100',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
                ]);
            } else {
                $solicitudTypes->sol_typ_name = $request->sol_typ_name;
                $solicitudTypes->save();
                Controller::NewRegisterTrigger("Se realizo una actualizacion en la tabla solicitudes types",1,2,1);
                return response()->json([
                    'status' => True,
                    'data' => "la razon ".$solicitudTypes->sol_typ_name." ha sido actualizado exitosamente."
                ],200);
            };
        }
        

    }
    public function destroy(solicitudType $solicitudTypes)
    {
        return response()->json([
          'status' => false,
          'message' => "Funcion no disponible"
        ],400);
        

    }
}
